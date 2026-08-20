<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisperindagKeluhanController extends Controller
{
    public function index(Request $request)
    {
        $query = Keluhan::with('verifikator');

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status_keluhan', $status);
        }

        $keluhans = $query->latest()->paginate(15)->withQueryString();
        
        return view('disperindag.keluhan.index', compact('keluhans'));
    }

    /**
     * Tampilkan detail keluhan dan jalankan BR 3.2
     */
    public function show(Keluhan $keluhan)
    {
        // BR 3.2: Jika status masih 'Masuk', otomatis ubah jadi 'Diproses' 
        // dan kunci (lock) ID verifikator ke user yang sedang membuka tiket
        if ($keluhan->status_keluhan === 'Masuk' && empty($keluhan->diverifikasi_oleh)) {
            $keluhan->update([
                'status_keluhan'    => 'Diproses',
                'diverifikasi_oleh' => Auth::id(),
            ]);
            activity()->performedOn($keluhan)->log('Staf membuka tiket. Status diubah otomatis ke Diproses.');
        }

        return view('disperindag.keluhan.show', compact('keluhan'));
    }

    public function update(Request $request, Keluhan $keluhan)
    {
        $request->validate([
            'status_keluhan' => 'required|in:Masuk,Diproses,Selesai,Ditolak',
            'tindak_lanjut'  => 'nullable|string|max:1000'
        ]);

        // Cegah user lain menimpa jika sudah dilock
        if ($keluhan->diverifikasi_oleh && $keluhan->diverifikasi_oleh !== Auth::id()) {
            return redirect()->back()->with('error', 'Keluhan ini sedang ditangani oleh staf lain.');
        }

        $dataUpdate = [
            'status_keluhan' => $request->status_keluhan,
            'tindak_lanjut'  => $request->tindak_lanjut,
        ];

        // FR-21: Catat SLA jika status diubah ke Selesai / Ditolak (menandakan respon Email akan/sudah dikirim)
        if (in_array($request->status_keluhan, ['Selesai', 'Ditolak']) && empty($keluhan->tanggal_respon_wa)) {
            $dataUpdate['tanggal_respon_wa'] = now();
        }

        DB::transaction(function () use ($keluhan, $dataUpdate) {
            $keluhan->update($dataUpdate);
            activity()->performedOn($keluhan)->log("Status keluhan diubah menjadi {$dataUpdate['status_keluhan']}");
        });

        return redirect()->route('disperindag.keluhan.index')
            ->with('success', "Status keluhan berhasil diperbarui menjadi {$request->status_keluhan}.");
    }

    public function tolak(Request $request, Keluhan $keluhan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($keluhan, $request) {
            $keluhan->update([
                'status_keluhan'    => 'Ditolak',
                'alasan_penolakan'  => $request->alasan_penolakan,
                'tanggal_respon_wa' => now(),
                'diverifikasi_oleh' => Auth::id(),
            ]);
            activity()->performedOn($keluhan)->log("Keluhan ditolak oleh " . Auth::user()->name . ". Alasan: " . $request->alasan_penolakan);
        });

        return redirect()->route('disperindag.keluhan.show', $keluhan->id)
            ->with('success', 'Laporan berhasil ditolak.');
    }

    public function selesai(Request $request, Keluhan $keluhan)
    {
        $request->validate([
            'tindak_lanjut' => 'required|string|max:2000',
        ]);

        DB::transaction(function () use ($keluhan, $request) {
            $keluhan->update([
                'status_keluhan'    => 'Selesai',
                'tindak_lanjut'     => $request->tindak_lanjut,
                'tanggal_respon_wa' => now(),
                'diverifikasi_oleh' => Auth::id(),
            ]);
            activity()->performedOn($keluhan)->log("Keluhan diselesaikan oleh " . Auth::user()->name);
        });

        return redirect()->route('disperindag.keluhan.show', $keluhan->id)
            ->with('success', 'Laporan berhasil ditandai sebagai Selesai.');
    }
}
