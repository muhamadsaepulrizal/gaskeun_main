<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilAgen;
use App\Models\PangkalanProfile;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Str;

class SyncAgenPangkalan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:agen-pangkalan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data dari tabel raw agen dan pangkalan ke tabel sistem';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data agen dan pangkalan...');

        // 0. Import SQL tables if they don't exist
        try {
            DB::table('agen')->first();
        } catch (\Exception $e) {
            $this->info('Tabel agen tidak ditemukan, mengimport dari agen.sql...');
            DB::unprepared(file_get_contents(base_path('agen.sql')));
        }

        try {
            DB::table('pangkalan')->first();
        } catch (\Exception $e) {
            $this->info('Tabel pangkalan tidak ditemukan, mengimport dari pangkalan.sql...');
            DB::unprepared(file_get_contents(base_path('pangkalan.sql')));
        }

        // 1. Sinkronisasi Agen
        $agens = DB::table('agen')->get();
        $agenMap = []; // Untuk memetakan id_agen mentah ke user_id agen yang baru

        $this->info('Sinkronisasi Agen (' . $agens->count() . ' data)');
        $barAgen = $this->output->createProgressBar($agens->count());
        $barAgen->start();

        foreach ($agens as $agenRaw) {
            $username = 'agen_' . $agenRaw->id_agen;
            
            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $agenRaw->nama_agen,
                    'email' => $username . '@gaskeun.com',
                    'password' => Hash::make('password'),
                    'force_password_change' => true,
                    'status_aktif' => true,
                ]
            );

            // Assign role
            if (!$user->hasRole('Agen LPG')) {
                $user->assignRole('Agen LPG');
            }

            // Update or create Profil Agen
            ProfilAgen::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_agen' => $agenRaw->nama_agen,
                    'no_registrasi' => $agenRaw->no_agen,
                    'alamat' => $agenRaw->alamat_agen,
                    'pso' => $agenRaw->pso,
                    'jumlah_mitra' => $agenRaw->jumlah_mitra,
                    'id_spbe' => $agenRaw->id_spbe,
                    'latitude' => $agenRaw->latitude ? (float) $agenRaw->latitude : null,
                    'longitude' => $agenRaw->longitude ? (float) $agenRaw->longitude : null,
                ]
            );

            // Simpan mapping
            $agenMap[$agenRaw->id_agen] = $user->id;

            $barAgen->advance();
        }
        $barAgen->finish();
        $this->newLine(2);

        // 2. Sinkronisasi Pangkalan
        $pangkalans = DB::table('pangkalan')->get();
        
        $this->info('Sinkronisasi Pangkalan (' . $pangkalans->count() . ' data)');
        $barPangkalan = $this->output->createProgressBar($pangkalans->count());
        $barPangkalan->start();

        foreach ($pangkalans as $pangkalanRaw) {
            // Handle Kecamatan
            $kecamatan = null;
            if (!empty(trim($pangkalanRaw->kecamatan))) {
                $kecamatan = Kecamatan::firstOrCreate([
                    'nama_kecamatan' => strtoupper(trim($pangkalanRaw->kecamatan))
                ]);
            }

            // Handle Desa
            $desa = null;
            if (!empty(trim($pangkalanRaw->kelurahan)) && $kecamatan) {
                // Hapus awalan "DESA " jika ada agar lebih konsisten, atau biarkan saja.
                $namaDesa = strtoupper(trim($pangkalanRaw->kelurahan));
                $desa = Desa::firstOrCreate([
                    'kecamatan_id' => $kecamatan->id,
                    'nama_desa' => $namaDesa
                ]);
            }

            $username = 'pangkalan_' . $pangkalanRaw->id_pangkalan;
            
            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $pangkalanRaw->pangkalan,
                    'email' => $username . '@gaskeun.com',
                    'password' => Hash::make('password'),
                    'force_password_change' => true,
                    'status_aktif' => true,
                ]
            );

            if (!$user->hasRole('Pangkalan LPG')) {
                $user->assignRole('Pangkalan LPG');
            }

            // Tentukan agen pembina
            $agenPembinaId = null;
            if (isset($agenMap[$pangkalanRaw->id_agen])) {
                $agenPembinaId = $agenMap[$pangkalanRaw->id_agen];
            }

            PangkalanProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'agen_pembina_id' => $agenPembinaId,
                    'kecamatan_id' => $kecamatan ? $kecamatan->id : null,
                    'desa_kelurahan_id' => $desa ? $desa->id : null,
                    'nama_pangkalan' => $pangkalanRaw->pangkalan,
                    'no_registrasi' => $pangkalanRaw->id_registrasi,
                    'alamat' => $pangkalanRaw->alamat_pangkalan,
                    'penyaluran' => $pangkalanRaw->penyaluran,
                    'latitude' => is_numeric($pangkalanRaw->latitude) ? (float) $pangkalanRaw->latitude : null,
                    'longitude' => is_numeric($pangkalanRaw->longitude) ? (float) $pangkalanRaw->longitude : null,
                ]
            );

            $barPangkalan->advance();
        }
        $barPangkalan->finish();
        $this->newLine(2);

        $this->info('Sinkronisasi selesai!');
    }
}
