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

        // BR-06: Blokir jika status bukan 'Menunggu'
        if ($pengiriman->status !== 'Menunggu') {
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

        // BR-06: Hanya bisa koreksi jika status 'Menunggu'
        if ($pengiriman->status !== 'Menunggu') {
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
        $konsumens = Konsumen::with(['kecamatan', 'desa', 'pangkalan.pangkalanProfile'])
            ->latest()->paginate(15);
        return view('pangkalan.konsumen.index', compact('konsumens'));
    }

    public function konsumenCreate()
    {
        $kecamatans = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get();
        return view('pangkalan.konsumen.create', compact('kecamatans'));
    }

    public function konsumenStore(Request $request)
    {
        $kategori = $request->kategori;
        $rules = [
            'kategori'          => 'required|in:Rumah Tangga,Usaha Mikro,Petani Sasaran,Nelayan Sasaran',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|string|digits:16',
            'kecamatan_id'      => 'required|exists:kecamatans,id',
            'desa_kelurahan_id' => 'required|exists:desas,id',
            'alamat'            => 'nullable|string|max:500',
        ];

        $request->validate($rules, [
            'nik.required'               => 'NIK wajib diisi.',
            'nik.digits'                 => 'NIK harus 16 digit angka.',
            'kecamatan_id.required'      => 'Kecamatan wajib dipilih.',
            'desa_kelurahan_id.required' => 'Desa wajib dipilih.',
        ]);

        // Validasi unik global NIK lintas pangkalan
        if ($request->filled('nik') && Konsumen::nikSudahTerdaftar($request->nik)) {
            return back()->withErrors(['nik' => 'NIK ini sudah terdaftar di sistem. Silakan langsung cari pada menu penyaluran.'])->withInput();
        }

        // Enkripsi NIK sebelum simpan
        $konsumen = Konsumen::create([
            'pangkalan_id'      => Auth::id(),
            'kategori'          => $kategori,
            'nama_lengkap'      => $request->nama_lengkap,
            'kecamatan_id'      => $request->kecamatan_id,
            'desa_kelurahan_id' => $request->desa_kelurahan_id,
            'alamat'            => $request->alamat,
        ]);

        if ($request->filled('nik')) {
            $konsumen->setNikAttribute($request->nik);
        }
        
        // Kosongkan NIB karena sudah tidak digunakan
        $konsumen->setNibAttribute(null);
        
        $konsumen->save();

        return redirect()->route('pangkalan.konsumen.index')
            ->with('success', 'Konsumen berhasil diregistrasikan.');
    }

    public function searchKonsumen(Request $request)
    {
        $keyword = trim($request->get('q'));
        
        if (empty($keyword)) {
            return response()->json([]);
        }

        $query = Konsumen::query();

        $hashKeyword = hash('sha256', $keyword);

        $query->where(function($q) use ($keyword, $hashKeyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
              ->orWhere('nik_hash', $hashKeyword);
        });

        $konsumens = $query->limit(10)->get()->map(function($k) {
            $identifier = $k->nik;
            return [
                'id' => $k->id,
                'nama' => $k->nama_lengkap,
                'identifier' => $identifier ? substr($identifier, 0, 6) . str_repeat('*', max(0, strlen($identifier) - 6)) : '-',
                'alamat' => $k->alamat,
                'kategori' => $k->kategori
            ];
        });

        return response()->json($konsumens);
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
            'konsumen_id'   => 'required|exists:konsumens,id',
            'jumlah_tabung' => 'required|integer|min:1',
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
            $konsumen = Konsumen::findOrFail($request->konsumen_id);
            
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

        return redirect()->route('pangkalan.penyaluran.create')
            ->with('success', "Transaksi berhasil. Stok dipotong otomatis.");
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
