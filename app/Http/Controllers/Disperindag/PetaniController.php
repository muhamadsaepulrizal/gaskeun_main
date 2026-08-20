<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use Illuminate\Http\Request;
use App\Models\Penduduk;


class PetaniController extends Controller
{
    public function index()
    {
        $items = Petani::with(['penduduk'])->latest()->paginate(10);
        return view('disperindag.petanis.index', compact('items'));
    }

    public function create()
    {
        $penduduks = Penduduk::all();
        
        return view('disperindag.petanis.create', compact('penduduks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required',
            'luas_lahan_m2' => 'required',
            'jenis_komoditas' => 'required'
        ]);

        Petani::create($request->all());
        return redirect()->route('disperindag.petanis.index')->with('success', 'Petani berhasil ditambahkan.');
    }

    public function edit(Petani $petani)
    {
        $penduduks = Penduduk::all();
        
        $item = $petani;
        return view('disperindag.petanis.edit', compact('item', 'penduduks'));
    }

    public function update(Request $request, Petani $petani)
    {
        $request->validate([
            'penduduk_id' => 'required',
            'luas_lahan_m2' => 'required',
            'jenis_komoditas' => 'required'
        ]);

        $petani->update($request->all());
        return redirect()->route('disperindag.petanis.index')->with('success', 'Petani berhasil diperbarui.');
    }

    public function destroy(Petani $petani)
    {
        $petani->delete();
        return redirect()->route('disperindag.petanis.index')->with('success', 'Petani berhasil dihapus.');
    }
}