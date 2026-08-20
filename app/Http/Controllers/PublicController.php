<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $tersalurkan = \App\Models\TransaksiPenyaluran::whereDate('created_at', today())->sum('jumlah_tabung') ?? 0;
        $pangkalanAktif = \App\Models\PangkalanProfile::count();
        $agenTerdaftar = \App\Models\ProfilAgen::count();
        $totalKecamatan = \App\Models\Kecamatan::count();
        $kecamatanAman = 0; // Sementara diset 0 karena stok kosong semua

        return view('welcome', compact('tersalurkan', 'pangkalanAktif', 'agenTerdaftar', 'totalKecamatan', 'kecamatanAman'));
    }

    public function peta()
    {
        // Real Data Pangkalan untuk Peta
        $pangkalans = \App\Models\PangkalanProfile::with('user.stokPangkalan', 'kecamatan', 'agenPembina.profilAgen')->get();
        $pangkalanList = $pangkalans->map(function($p) {
            $stok = optional($p->user->stokPangkalan)->jumlah_tabung ?? 0;
            return [
                'id' => $p->id,
                'nama' => $p->nama_pangkalan,
                'alamat' => $p->alamat,
                'stok' => $stok,
                'status' => $stok > 30 ? 'Aman' : ($stok > 0 ? 'Menipis' : 'Kosong'),
                'kecamatan' => optional($p->kecamatan)->nama_kecamatan ?? 'Garut',
                'agen' => optional($p->agenPembina->profilAgen)->nama_agen ?? (optional($p->agenPembina)->name ?? 'Tidak ada Agen'),
                'latitude' => (float) $p->latitude,
                'longitude' => (float) $p->longitude,
            ];
        });

        return view('public.peta', compact('pangkalanList'));
    }

    public function heatmap()
    {
        // Data Heatmap dari keluhan yang memiliki koordinat
        $heatmapData = \App\Models\Keluhan::selectRaw('latitude, longitude, count(*) as weight, kecamatan_id')
            ->with('kecamatan')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', '')
            ->where('longitude', '!=', '')
            ->groupBy('latitude', 'longitude', 'kecamatan_id')
            ->get()
            ->map(fn($h) => [
                (float)$h->latitude, 
                (float)$h->longitude, 
                (float)$h->weight,
                optional($h->kecamatan)->nama_kecamatan ?? ''
            ])
            ->toArray();

        // Data pangkalan untuk overlay marker di atas heatmap
        $pangkalanList = \App\Models\PangkalanProfile::with('user.stokPangkalan', 'kecamatan', 'agenPembina.profilAgen')
            ->get()
            ->filter(fn($p) => $p->latitude && $p->longitude)
            ->map(function($p) {
                $stok = optional($p->user->stokPangkalan)->jumlah_tabung ?? 0;
                return [
                    'id'        => $p->id,
                    'nama'      => $p->nama_pangkalan,
                    'alamat'    => $p->alamat ?? '-',
                    'stok'      => $stok,
                    'status'    => $stok > 30 ? 'Aman' : ($stok > 0 ? 'Menipis' : 'Kosong'),
                    'kecamatan' => optional($p->kecamatan)->nama_kecamatan ?? 'Garut',
                    'agen'      => optional($p->agenPembina->profilAgen)->nama_agen ?? (optional($p->agenPembina)->name ?? 'Tidak ada Agen'),
                    'latitude'  => (float) $p->latitude,
                    'longitude' => (float) $p->longitude,
                ];
            })
            ->values()
            ->toArray();

        return view('public.heatmap', compact('heatmapData', 'pangkalanList'));
    }
}
