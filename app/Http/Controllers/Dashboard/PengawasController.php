<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HeatmapSnapshot;
use App\Models\Keluhan;
use App\Models\TransaksiPenyaluran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengawasController extends Controller
{
    public function index()
    {
        // 1. Snapshot Kebijakan Strategis (Overall Heatmap Level)
        $kecamatanKritis = HeatmapSnapshot::whereDate('tanggal_snapshot', now()->toDateString())
            ->where('level_risiko', 'Kritis')
            ->count();

        $kecamatanRawan = HeatmapSnapshot::whereDate('tanggal_snapshot', now()->toDateString())
            ->where('level_risiko', 'Rawan')
            ->count();

        // 2. Ranking Kecamatan Kritis (Butuh intervensi / operasi pasar)
        $rankingKritis = HeatmapSnapshot::with('kecamatan')
            ->whereDate('tanggal_snapshot', now()->toDateString())
            ->orderByDesc('skor_heatmap')
            ->take(5)
            ->get();

        // 3. Ringkasan Pengaduan Publik
        $keluhanSelesai = Keluhan::where('status_keluhan', 'Selesai')->count();
        $totalKeluhan = Keluhan::count();
        
        $rasioPenyelesaian = $totalKeluhan > 0 
            ? round(($keluhanSelesai / $totalKeluhan) * 100, 1) 
            : 0;

        // 4. Penyaluran Subsidi Tepat Sasaran
        $penyaluranSubsidi = TransaksiPenyaluran::selectRaw('kategori_konsumen, SUM(jumlah_tabung) as total')
            ->whereMonth('tanggal_penyaluran', now()->month)
            ->whereYear('tanggal_penyaluran', now()->year)
            ->groupBy('kategori_konsumen')
            ->get();

        return view('pengawas.dashboard', compact(
            'kecamatanKritis', 'kecamatanRawan',
            'rankingKritis', 'rasioPenyelesaian',
            'penyaluranSubsidi', 'keluhanSelesai', 'totalKeluhan'
        ));
    }
}
