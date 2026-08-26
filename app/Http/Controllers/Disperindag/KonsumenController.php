<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Konsumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class KonsumenController extends Controller
{
    /**
     * Tampilkan daftar seluruh konsumen yang telah didaftarkan Pangkalan
     */
    public function index(Request $request)
    {
        $query = Konsumen::with(['pangkalan.pangkalanProfile', 'kecamatan', 'desa'])->latest();

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            // Jika pencarian berupa angka (potensi NIK), gunakan hash untuk mencari (BR-20)
            if (is_numeric($search)) {
                $hash = hash('sha256', $search);
                $query->where(function($q) use ($hash, $search) {
                    $q->where('nik_hash', $hash)
                      ->orWhere('nama_lengkap', 'like', "%{$search}%");
                });
            } else {
                $query->where('nama_lengkap', 'like', "%{$search}%");
            }
        }
        
        // Filter by Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $konsumens = $query->paginate(15)->withQueryString();

        return view('disperindag.konsumens.index', compact('konsumens'));
    }
    public function edit(Konsumen $konsumen)
    {
        $kecamatans = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get();
        return view('disperindag.konsumens.edit', compact('konsumen', 'kecamatans'));
    }

    public function update(Request $request, Konsumen $konsumen)
    {
        $rules = [
            'kategori'          => 'required|in:Rumah Tangga,Usaha Mikro,Petani Sasaran,Nelayan Sasaran,Petani,Nelayan',
            'nama_lengkap'      => 'required|string|max:255',
            'kecamatan_id'      => 'required|exists:kecamatans,id',
            'desa_kelurahan_id' => 'required|exists:desas,id',
            'alamat'            => 'nullable|string|max:1000',
            'kontak'            => 'nullable|string|max:50',
        ];

        // Validasi NIK unik jika ada perubahan
        if ($request->filled('nik') && $request->nik !== $konsumen->nik) {
            if (Konsumen::nikSudahTerdaftar($request->nik, $konsumen->id)) {
                return redirect()->back()->withInput()->withErrors(['nik' => 'NIK ini sudah terdaftar di sistem.']);
            }
        }
        
        $request->validate($rules);

        $konsumen->kategori = $request->kategori;
        $konsumen->nama_lengkap = $request->nama_lengkap;
        $konsumen->kecamatan_id = $request->kecamatan_id;
        $konsumen->desa_kelurahan_id = $request->desa_kelurahan_id;
        $konsumen->alamat = $request->alamat;
        $konsumen->kontak = $request->kontak;
        
        if ($request->filled('nik') && $request->nik !== $konsumen->nik) {
            $konsumen->setNikAttribute($request->nik);
        }

        $konsumen->save();

        return redirect()->route('disperindag.konsumens.index')->with('success', 'Data Konsumen berhasil diperbarui.');
    }

    public function destroy(Konsumen $konsumen)
    {
        $konsumen->delete();
        return redirect()->route('disperindag.konsumens.index')->with('success', 'Data Konsumen berhasil dihapus.');
    }
}
