<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\HeatmapSnapshot;
use App\Models\PangkalanProfile;
use App\Models\ProfilAgen;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DisperindagController extends Controller
{
    public function index()
    {
        // 1. Statistik Keluhan
        $totalKeluhan = Keluhan::count();
        $keluhanBaru = Keluhan::where('status_keluhan', 'Masuk')->count();
        $keluhanSelesai = Keluhan::where('status_keluhan', 'Selesai')->count();

        // 2. Statistik Jaringan Distribusi
        $totalAgen = ProfilAgen::count();
        $totalPangkalan = PangkalanProfile::count();
        $totalKecamatan = PangkalanProfile::distinct('kecamatan_id')->count('kecamatan_id');

        // 3. Heatmap Data Terbaru (Kecamatan Rawan)
        $heatmapRawan = HeatmapSnapshot::with('kecamatan')
            ->whereDate('tanggal_snapshot', now()->toDateString())
            ->whereIn('level_risiko', ['Rawan', 'Kritis'])
            ->orderByDesc('skor_heatmap')
            ->take(5)
            ->get();

        // 4. Riwayat Keluhan Terbaru
        $keluhanTerbaru = Keluhan::with(['user', 'verifikator'])
            ->latest()
            ->take(5)
            ->get();

        return view('disperindag.dashboard', compact(
            'totalKeluhan', 'keluhanBaru', 'keluhanSelesai',
            'totalAgen', 'totalPangkalan', 'totalKecamatan',
            'heatmapRawan', 'keluhanTerbaru'
        ));
    }
}
