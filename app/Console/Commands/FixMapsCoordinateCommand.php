<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\PangkalanProfile;
use App\Models\Keluhan;
use App\Models\Kecamatan;
use App\Models\Desa;

class FixMapsCoordinateCommand extends Command
{
    protected $signature = 'gaskeun:fix-maps';
    protected $description = 'Perbaiki koordinat latitude/longitude Pangkalan & Keluhan agar sesuai kecamatan masing-masing di Kabupaten Garut berdasarkan API Open Data Jabar';

    public function handle()
    {
        $this->info('Mengambil referensi koordinat resmi Garut dari Open Data Jabar...');

        $garut = $this->fetchGarutData();

        if (empty($garut)) {
            $this->error('Tidak ada data Garut yang ditemukan.');
            return;
        }

        $this->info('Berhasil mendapatkan ' . count($garut) . ' data desa/kelurahan Garut.');

        // ======================================================================
        // LANGKAH 1: Bangun lookup koordinat dari API
        // ======================================================================
        $desaCoords = [];
        $kecamatanCoordsTemp = [];

        foreach ($garut as $item) {
            $namaKec = strtoupper(trim($item['kemendagri_kecamatan_nama'] ?? ''));
            $namaDesa = strtoupper(trim($item['kemendagri_kelurahan_nama'] ?? ''));
            $lat = (float) ($item['latitude'] ?? 0);
            $lng = (float) ($item['longitude'] ?? 0);

            if ($lat == 0 && $lng == 0) continue;

            // Lookup per desa
            $desaCoords[$namaDesa] = ['lat' => $lat, 'lng' => $lng];

            // Kumpulkan semua koordinat per kecamatan (untuk rata-rata nanti)
            if (!isset($kecamatanCoordsTemp[$namaKec])) {
                $kecamatanCoordsTemp[$namaKec] = ['lats' => [], 'lngs' => []];
            }
            $kecamatanCoordsTemp[$namaKec]['lats'][] = $lat;
            $kecamatanCoordsTemp[$namaKec]['lngs'][] = $lng;
        }

        // Hitung titik pusat kecamatan = rata-rata semua desa di kecamatan tersebut
        $kecamatanCoords = [];
        foreach ($kecamatanCoordsTemp as $kec => $coords) {
            $kecamatanCoords[$kec] = [
                'lat' => array_sum($coords['lats']) / count($coords['lats']),
                'lng' => array_sum($coords['lngs']) / count($coords['lngs']),
                'desa_lats' => $coords['lats'],
                'desa_lngs' => $coords['lngs'],
            ];
        }

        $this->info('Koordinat referensi: ' . count($kecamatanCoords) . ' kecamatan, ' . count($desaCoords) . ' desa.');

        // ======================================================================
        // LANGKAH 2: Bersihkan data kecamatan yang salah (berisi nama desa)
        // ======================================================================
        $this->info("\nMembersihkan data kecamatan yang tidak valid...");
        $badKecamatans = Kecamatan::where('nama_kecamatan', 'LIKE', "%\t%")
            ->orWhere('nama_kecamatan', 'LIKE', '%DESA %')
            ->orWhere('nama_kecamatan', 'REGEXP', '^[0-9]+$')
            ->get();

        foreach ($badKecamatans as $badKec) {
            // Coba extract nama kecamatan asli (misalnya "CIBALONG\tDESA MAROKO" -> "CIBALONG")
            $parts = preg_split('/[\t]/', $badKec->nama_kecamatan);
            $realKecName = strtoupper(trim($parts[0]));

            $realKec = Kecamatan::where('nama_kecamatan', $realKecName)->first();
            if ($realKec && $realKec->id !== $badKec->id) {
                // Pindahkan pangkalan & desa yang terikat ke kecamatan yang benar
                PangkalanProfile::where('kecamatan_id', $badKec->id)->update(['kecamatan_id' => $realKec->id]);
                Desa::where('kecamatan_id', $badKec->id)->update(['kecamatan_id' => $realKec->id]);
                Keluhan::where('kecamatan_id', $badKec->id)->update(['kecamatan_id' => $realKec->id]);
                $badKec->delete();
                $this->line("  Dihapus: '{$badKec->nama_kecamatan}' → digabung ke '{$realKecName}' (ID:{$realKec->id})");
            } else {
                // Jika tidak ada padanan, rename saja
                if ($realKecName && $realKecName !== $badKec->nama_kecamatan) {
                    $badKec->nama_kecamatan = $realKecName;
                    $badKec->save();
                    $this->line("  Diubah nama: '{$badKec->getOriginal('nama_kecamatan')}' → '{$realKecName}'");
                }
            }
        }

        // ======================================================================
        // LANGKAH 3: Perbaiki koordinat semua Pangkalan
        // ======================================================================
        $this->info("\nMemperbarui koordinat Pangkalan...");
        $pangkalans = PangkalanProfile::with(['desaKelurahan', 'kecamatan'])->get();
        $bar1 = $this->output->createProgressBar($pangkalans->count());
        $bar1->start();

        $fixed = 0;
        DB::beginTransaction();
        try {
            foreach ($pangkalans as $p) {
                $namaKec = $p->kecamatan ? strtoupper(trim($p->kecamatan->nama_kecamatan)) : null;
                $namaDesa = $p->desaKelurahan ? strtoupper(trim($p->desaKelurahan->nama_desa)) : null;

                $baseLat = null;
                $baseLng = null;

                // Prioritas 1: Koordinat dari desa spesifik
                if ($namaDesa && isset($desaCoords[$namaDesa])) {
                    $baseLat = $desaCoords[$namaDesa]['lat'];
                    $baseLng = $desaCoords[$namaDesa]['lng'];
                }
                // Prioritas 2: Koordinat dari kecamatan (rata-rata)
                elseif ($namaKec && isset($kecamatanCoords[$namaKec])) {
                    // Pilih titik acak dari desa-desa di kecamatan tsb agar tersebar natural
                    $desaCount = count($kecamatanCoords[$namaKec]['desa_lats']);
                    $randIdx = rand(0, $desaCount - 1);
                    $baseLat = $kecamatanCoords[$namaKec]['desa_lats'][$randIdx];
                    $baseLng = $kecamatanCoords[$namaKec]['desa_lngs'][$randIdx];
                }
                // Fallback: Pusat Garut Kota
                else {
                    if (isset($kecamatanCoords['GARUT KOTA'])) {
                        $baseLat = $kecamatanCoords['GARUT KOTA']['lat'];
                        $baseLng = $kecamatanCoords['GARUT KOTA']['lng'];
                    } else {
                        $baseLat = -7.2278;
                        $baseLng = 107.9087;
                    }
                }

                // Jitter kecil (~500m radius) agar titik tidak bertumpuk persis
                $jitterLat = (rand(-50, 50) / 10000);
                $jitterLng = (rand(-50, 50) / 10000);

                $p->latitude  = round($baseLat + $jitterLat, 8);
                $p->longitude = round($baseLng + $jitterLng, 8);
                $p->save();
                $fixed++;

                $bar1->advance();
            }

            // ======================================================================
            // LANGKAH 4: Perbaiki koordinat semua Keluhan
            // ======================================================================
            $bar1->finish();
            $this->info("\n\nMemperbarui koordinat Keluhan (Heatmap)...");

            $keluhans = Keluhan::with(['kecamatan'])->get();
            $bar2 = $this->output->createProgressBar($keluhans->count());
            $bar2->start();

            foreach ($keluhans as $k) {
                $baseLat = null;
                $baseLng = null;

                // Jika terikat pangkalan, ikuti koordinat pangkalan yang sudah diperbaiki
                if ($k->pangkalan_id) {
                    $profile = PangkalanProfile::where('user_id', $k->pangkalan_id)->first();
                    if ($profile && $profile->latitude && $profile->longitude) {
                        $baseLat = (float) $profile->latitude;
                        $baseLng = (float) $profile->longitude;
                    }
                }

                // Jika belum dapat, gunakan kecamatan
                if (!$baseLat || !$baseLng) {
                    $namaKec = $k->kecamatan ? strtoupper(trim($k->kecamatan->nama_kecamatan)) : null;
                    if ($namaKec && isset($kecamatanCoords[$namaKec])) {
                        $desaCount = count($kecamatanCoords[$namaKec]['desa_lats']);
                        $randIdx = rand(0, $desaCount - 1);
                        $baseLat = $kecamatanCoords[$namaKec]['desa_lats'][$randIdx];
                        $baseLng = $kecamatanCoords[$namaKec]['desa_lngs'][$randIdx];
                    }
                }

                // Fallback
                if (!$baseLat || !$baseLng) {
                    $baseLat = -7.2278;
                    $baseLng = 107.9087;
                }

                $jitterLat = (rand(-30, 30) / 10000);
                $jitterLng = (rand(-30, 30) / 10000);

                $k->latitude  = round($baseLat + $jitterLat, 8);
                $k->longitude = round($baseLng + $jitterLng, 8);
                $k->save();

                $bar2->advance();
            }
            $bar2->finish();

            DB::commit();
            $this->newLine(2);
            $this->info('✅ Selesai! ' . $fixed . ' pangkalan dan ' . $keluhans->count() . ' keluhan telah disinkronisasi ke koordinat resmi Kabupaten Garut.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\nTerjadi kesalahan: " . $e->getMessage());
        }
    }

    /**
     * Ambil data Garut dari API Open Data Jabar (dengan paginasi).
     * Jika API gagal, gunakan file cache lokal.
     */
    private function fetchGarutData(): array
    {
        // Coba dari cache lokal dulu (lebih cepat & reliable)
        $cachePath = storage_path('app/garut_wilayah.json');
        if (file_exists($cachePath)) {
            $this->line('Menggunakan data cache dari: ' . $cachePath);
            $cached = json_decode(file_get_contents($cachePath), true);
            if (is_array($cached) && count($cached) > 0) {
                return $cached;
            }
        }

        // Jika tidak ada cache, ambil langsung dari API (paginasi per 1000)
        $this->line('Mengambil data langsung dari API (paginasi)...');
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

            if (count($pageData) < 1000) break; // Halaman terakhir
        }

        // Filter hanya Garut
        $garut = array_values(array_filter($allData, function ($item) {
            return strtoupper($item['kemendagri_kota_nama'] ?? '') === 'KAB. GARUT';
        }));

        // Simpan ke cache untuk penggunaan berikutnya
        if (count($garut) > 0) {
            file_put_contents($cachePath, json_encode($garut, JSON_PRETTY_PRINT));
            $this->line('Data Garut disimpan ke cache: ' . $cachePath);
        }

        return $garut;
    }
}
