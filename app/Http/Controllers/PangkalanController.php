<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use App\Models\StokPangkalan;
use App\Models\TransaksiPengiriman;
use App\Models\TransaksiPenyaluran;
use App\Models\KoreksiPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PangkalanController extends Controller
{
    public function dashboard()
    {
        $pangkalanId = Auth::id();

        $stok = StokPangkalan::where('user_id', $pangkalanId)->first();
        $stokTersedia = $stok ? $stok->jumlah_tabung : 0;

        $totalPenerimaan = TransaksiPengiriman::where('pangkalan_id', $pangkalanId)
            ->where('status', 'Diterima')->sum('jumlah_tabung');

        $totalPenyaluran = TransaksiPenyaluran::where('pangkalan_id', $pangkalanId)
            ->sum('jumlah_tabung');

        $pengirimanMenunggu = TransaksiPengiriman::where('pangkalan_id', $pangkalanId)
            ->where('status', 'Dikirim')->count();

        $totalKonsumen = Konsumen::where('pangkalan_id', $pangkalanId)->count();

        // Peringatan stok menipis (< 20 tabung)
        $stokMenipis = $stokTersedia < 20;

        return view('pangkalan.dashboard', compact(
            'stokTersedia', 'totalPenerimaan', 'totalPenyaluran',
            'pengirimanMenunggu', 'totalKonsumen', 'stokMenipis'
        ));
    }

    // ============================================================
    // TERIMA PENGIRIMAN LPG
    // ============================================================
    public function terimaPengiriman()
    {
        $pengiriman = TransaksiPengiriman::with(['agen', 'koreksi'])
            ->where('pangkalan_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pangkalan.pengiriman.index', compact('pengiriman'));
    }

    /**
     * BR-06: Konfirmasi hanya jika status masih 'Dikirim' (anti-duplikasi)
     * BR-07: Tambah stok otomatis setelah konfirmasi
     */
    public function konfirmasiPenerimaan(TransaksiPengiriman $pengiriman)
    {
        if ($pengiriman->pangkalan_id !== Auth::id()) {
            abort(403);
        }

        // BR-06: Blokir jika status bukan 'Dikirim'
        if ($pengiriman->status !== 'Dikirim') {
            return redirect()->back()->with('error', 'Pengiriman ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($pengiriman) {
            $pengiriman->update(['status' => 'Diterima']);

            // BR-07: Tambah stok otomatis
            $stok = StokPangkalan::firstOrCreate(
                ['user_id' => Auth::id()],
                ['jumlah_tabung' => 0]
            );
            $stok->increment('jumlah_tabung', $pengiriman->jumlah_tabung);

            activity()->performedOn($pengiriman)->log(
                "Penerimaan dikonfirmasi. Stok +{$pengiriman->jumlah_tabung} tabung."
            );
        });

        return redirect()->route('pangkalan.pengiriman.index')
            ->with('success', "Pengiriman dikonfirmasi. Stok bertambah {$pengiriman->jumlah_tabung} tabung.");
    }

    /**
     * Ajukan koreksi jika fisik berbeda
     * Tidak ada "Tolak Total" — jika 100% rusak, input 0 (agar jejak tetap ada)
     */
    public function ajukanKoreksi(Request $request, TransaksiPengiriman $pengiriman)
    {
        if ($pengiriman->pangkalan_id !== Auth::id()) {
            abort(403);
        }

        // BR-06: Hanya bisa koreksi jika status 'Dikirim'
        if ($pengiriman->status !== 'Dikirim') {
            return redirect()->back()->with('error', 'Pengiriman ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'jumlah_diterima'     => 'required|integer|min:0',
            'keterangan_koreksi'  => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $pengiriman) {
            $pengiriman->update(['status' => 'Dikoreksi']);

            KoreksiPengiriman::create([
                'transaksi_pengiriman_id' => $pengiriman->id,
                'jumlah_seharusnya'       => $request->jumlah_diterima,
                'keterangan_koreksi'      => $request->keterangan_koreksi,
                'status_koreksi'          => 'Menunggu',
            ]);

            // Jika ada yang diterima (> 0), tambah stok sesuai jumlah riil
            if ($request->jumlah_diterima > 0) {
                $stok = StokPangkalan::firstOrCreate(
                    ['user_id' => Auth::id()],
                    ['jumlah_tabung' => 0]
                );
                $stok->increment('jumlah_tabung', $request->jumlah_diterima);
            }

            activity()->performedOn($pengiriman)->log(
                "Koreksi diajukan. Diterima: {$request->jumlah_diterima} tabung."
            );
        });

        return redirect()->route('pangkalan.pengiriman.index')
            ->with('success', 'Koreksi berhasil diajukan dan stok telah disesuaikan.');
    }

    // ============================================================
    // REGISTRASI KONSUMEN (BR-19, BR-20)
    // ============================================================
    public function konsumenIndex()
    {
        $konsumens = Konsumen::where('pangkalan_id', Auth::id())
            ->latest()->paginate(15);
        return view('pangkalan.konsumen.index', compact('konsumens'));
    }

    public function konsumenCreate()
    {
        return view('pangkalan.konsumen.create');
    }

    public function konsumenStore(Request $request)
    {
        $rules = [
            'kategori'     => 'required|in:Rumah Tangga,Usaha Mikro,Petani,Nelayan',
            'nama_lengkap' => 'required|string|max:255',
            'alamat'       => 'nullable|string|max:500',
            'kontak'       => 'nullable|string|max:20',
        ];

        // Validasi dokumen berdasarkan kategori (BR-19, BR-20)
        if ($request->kategori === 'Rumah Tangga') {
            $rules['nik'] = ['required', 'string', 'digits:16'];
        }
        if ($request->kategori === 'Usaha Mikro') {
            $rules['nib'] = ['required', 'string'];
        }

        $request->validate($rules, [
            'nik.required' => 'NIK wajib diisi untuk kategori Rumah Tangga.',
            'nik.digits'   => 'NIK harus 16 digit angka.',
            'nib.required' => 'NIB wajib diisi untuk kategori Usaha Mikro.',
        ]);

        // BR-19: Validasi unik global NIK/NIB lintas pangkalan
        if ($request->filled('nik') && Konsumen::nikSudahTerdaftar($request->nik)) {
            return back()->withErrors(['nik' => 'NIK ini sudah terdaftar di pangkalan lain. Tidak bisa didaftarkan ulang.'])->withInput();
        }
        if ($request->filled('nib') && Konsumen::nibSudahTerdaftar($request->nib)) {
            return back()->withErrors(['nib' => 'NIB ini sudah terdaftar di pangkalan lain. Tidak bisa didaftarkan ulang.'])->withInput();
        }

        // BR-20: Enkripsi NIK/NIB sebelum simpan
        $konsumen = Konsumen::create([
            'pangkalan_id' => Auth::id(),
            'kategori'     => $request->kategori,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat'       => $request->alamat,
            'kontak'       => $request->kontak,
        ]);

        // Gunakan setter untuk enkripsi otomatis
        if ($request->filled('nik')) {
            $konsumen->setNikAttribute($request->nik);
            $konsumen->save();
        }
        if ($request->filled('nib')) {
            $konsumen->setNibAttribute($request->nib);
            $konsumen->save();
        }

        return redirect()->route('pangkalan.konsumen.index')
            ->with('success', 'Konsumen berhasil diregistrasikan.');
    }

    // ============================================================
    // INPUT PENYALURAN LPG (FR-13, BR-18)
    // ============================================================
    public function penyaluranCreate()
    {
        $stok = StokPangkalan::where('user_id', Auth::id())->first();
        $jumlahStok = $stok ? $stok->jumlah_tabung : 0;

        return view('pangkalan.penyaluran.create', compact('jumlahStok'));
    }

    public function penyaluranStore(Request $request)
    {
        $request->validate([
            'kategori_konsumen' => 'required|in:Rumah Tangga,Usaha Mikro,Petani,Nelayan',
            'nik'               => 'required|string',
            'nama_lengkap'      => 'required|string|max:255',
            'jumlah_tabung'     => 'required|integer|min:1',
            'nomor_kartu'       => 'nullable|string', // Untuk NIB, Kartu Tani, Kartu Kusuka
        ]);

        $stok = StokPangkalan::where('user_id', Auth::id())->first();

        // FR-13: Hard block jika stok kosong atau 0
        if (!$stok || $stok->jumlah_tabung <= 0) {
            return back()->with('error', 'Stok LPG habis! Transaksi tidak dapat diproses.');
        }

        if ($stok->jumlah_tabung < $request->jumlah_tabung) {
            return back()->with('error', "Stok tidak mencukupi. Sisa stok: {$stok->jumlah_tabung} tabung.");
        }

        DB::transaction(function () use ($request, $stok) {
            // Cek apakah konsumen sudah ada berdasarkan NIK (simulasi, seharusnya by hash)
            $konsumen = Konsumen::where('nik_hash', hash('sha256', $request->nik))->first();
            
            if (!$konsumen) {
                // Buat konsumen baru
                $konsumen = Konsumen::create([
                    'pangkalan_id' => Auth::id(),
                    'kategori'     => $request->kategori_konsumen,
                    'nama_lengkap' => $request->nama_lengkap,
                ]);
                $konsumen->setNikAttribute($request->nik);
                if ($request->nomor_kartu && $request->kategori_konsumen === 'Usaha Mikro') {
                    $konsumen->setNibAttribute($request->nomor_kartu);
                }
                $konsumen->save();
            }

            // BR-18: Deteksi anomali — warga beli di luar pangkalan pendaftaran
            $isAnomali = $konsumen->pangkalan_id !== Auth::id();
            if ($isAnomali) {
                $konsumen->update(['is_anomali' => true]);
            }

            TransaksiPenyaluran::create([
                'pangkalan_id'      => Auth::id(),
                'kategori_konsumen' => $konsumen->kategori,
                'penduduk_id'       => $konsumen->id,
                'jumlah_tabung'     => $request->jumlah_tabung,
                'tanggal_penyaluran'=> now()->toDateString(),
            ]);

            // Potong stok otomatis
            $stok->decrement('jumlah_tabung', $request->jumlah_tabung);

            activity()->log("Penyaluran {$request->jumlah_tabung} tabung ke konsumen {$konsumen->nama_lengkap}" .
                ($isAnomali ? ' [ANOMALI: beli di luar pangkalan asal]' : ''));
        });

        return redirect()->route('pangkalan.stok')
            ->with('success', "Penyaluran {$request->jumlah_tabung} tabung berhasil. Stok dikurangi otomatis.");
    }

    // ============================================================
    // LIHAT SISA STOK LPG
    // ============================================================
    public function sisaStok()
    {
        $stok = StokPangkalan::where('user_id', Auth::id())->first();
        $jumlahStok = $stok ? $stok->jumlah_tabung : 0;

        $riwayatPenyaluran = TransaksiPenyaluran::with('konsumen')
            ->where('pangkalan_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pangkalan.stok.index', compact('jumlahStok', 'riwayatPenyaluran'));
    }
}
