<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\DB;

class SyncWilayahJabarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jabar:sync-garut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Kode dan Nama Wilayah Desa/Kelurahan khusus Kabupaten Garut dari Open Data Jabar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mengambil data Kabupaten Garut dari Open Data Jabar...');

        // Cek cache dulu
        $cachePath = storage_path('app/garut_wilayah.json');
        $data = [];

        if (file_exists($cachePath)) {
            $this->line('Menggunakan data cache lokal...');
            $data = json_decode(file_get_contents($cachePath), true) ?? [];
        }

        // Jika tidak ada cache, ambil dari API dengan paginasi
        if (empty($data)) {
            $this->line('Mengambil dari API (paginasi per 1000 record)...');
            $baseUrl = 'https://data.jabarprov.go.id/api-backend/bigdata/diskominfo/od_kode_wilayah_dan_nama_wilayah_desa_kelurahan';
            $allData = [];

            for ($skip = 0; $skip < 6000; $skip += 1000) {
                $url = $baseUrl . "?limit=1000&skip=" . $skip;
                $cmd = 'curl.exe -s -A "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36" "' . $url . '"';
                $jsonStr = shell_exec($cmd);

                if (!$jsonStr) continue;

                $responseData = json_decode($jsonStr, true);
                $pageData = $responseData['data'] ?? [];
                $allData = array_merge($allData, $pageData);

                $this->line("  Page " . (($skip / 1000) + 1) . ": " . count($pageData) . " records");
                if (count($pageData) < 1000) break;
            }

            // Filter hanya Garut
            $data = array_values(array_filter($allData, function ($item) {
                return strtoupper($item['kemendagri_kota_nama'] ?? '') === 'KAB. GARUT';
            }));

            // Simpan cache
            if (count($data) > 0) {
                file_put_contents($cachePath, json_encode($data, JSON_PRETTY_PRINT));
            }
        }

        $totalData = count($data);
        $this->info('Ditemukan ' . $totalData . ' data desa/kelurahan di Kabupaten Garut untuk disinkronisasi.');

        if ($totalData === 0) {
            $this->warn('Data KAB. GARUT tidak ditemukan.');
            return;
        }

        $bar = $this->output->createProgressBar($totalData);
        $bar->start();

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                $namaKecamatan = trim($item['kemendagri_kecamatan_nama'] ?? '');
                $namaDesa = trim($item['kemendagri_kelurahan_nama'] ?? '');

                // Skip data yang tidak valid (nama mengandung tab, atau "BELUM TERIDENTIFIKASI")
                if (!$namaKecamatan || !$namaDesa) {
                    $bar->advance();
                    continue;
                }
                if (str_contains($namaKecamatan, "\t") || str_contains($namaKecamatan, 'BELUM TERIDENTIFIKASI')) {
                    $bar->advance();
                    continue;
                }

                // Cari atau buat Kecamatan
                $kecamatan = Kecamatan::firstOrCreate([
                    'nama_kecamatan' => $namaKecamatan
                ]);

                // Cari atau buat Desa
                Desa::firstOrCreate([
                    'kecamatan_id' => $kecamatan->id,
                    'nama_desa' => $namaDesa
                ]);

                $bar->advance();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\nTerjadi kesalahan: " . $e->getMessage());
            return;
        }

        $bar->finish();
        $this->newLine();
        $this->info('Data wilayah Garut (Kecamatan & Desa) berhasil disinkronisasi!');
    }
}
