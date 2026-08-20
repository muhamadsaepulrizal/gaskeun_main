<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use App\Models\Penduduk;


class UmkmController extends Controller
{
    public function index()
    {
        $items = Umkm::with(['penduduk'])->latest()->paginate(10);
        return view('disperindag.umkms.index', compact('items'));
    }

    public function create()
    {
        $penduduks = Penduduk::all();
        
        return view('disperindag.umkms.create', compact('penduduks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required',
            'nama_usaha' => 'required',
            'bidang_usaha' => 'required'
        ]);

        Umkm::create($request->all());
        return redirect()->route('disperindag.umkms.index')->with('success', 'Umkm berhasil ditambahkan.');
    }

    public function edit(Umkm $umkm)
    {
        $penduduks = Penduduk::all();
        
        $item = $umkm;
        return view('disperindag.umkms.edit', compact('item', 'penduduks'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $request->validate([
            'penduduk_id' => 'required',
            'nama_usaha' => 'required',
            'bidang_usaha' => 'required'
        ]);

        $umkm->update($request->all());
        return redirect()->route('disperindag.umkms.index')->with('success', 'Umkm berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        $umkm->delete();
        return redirect()->route('disperindag.umkms.index')->with('success', 'Umkm berhasil dihapus.');
    }
}