<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Nelayan;
use Illuminate\Http\Request;
use App\Models\Penduduk;


class NelayanController extends Controller
{
    public function index()
    {
        $items = Nelayan::with(['penduduk'])->latest()->paginate(10);
        return view('disperindag.nelayans.index', compact('items'));
    }

    public function create()
    {
        $penduduks = Penduduk::all();
        
        return view('disperindag.nelayans.create', compact('penduduks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required',
            'jenis_kapal' => 'required',
            'alat_tangkap' => 'required'
        ]);

        Nelayan::create($request->all());
        return redirect()->route('disperindag.nelayans.index')->with('success', 'Nelayan berhasil ditambahkan.');
    }

    public function edit(Nelayan $nelayan)
    {
        $penduduks = Penduduk::all();
        
        $item = $nelayan;
        return view('disperindag.nelayans.edit', compact('item', 'penduduks'));
    }

    public function update(Request $request, Nelayan $nelayan)
    {
        $request->validate([
            'penduduk_id' => 'required',
            'jenis_kapal' => 'required',
            'alat_tangkap' => 'required'
        ]);

        $nelayan->update($request->all());
        return redirect()->route('disperindag.nelayans.index')->with('success', 'Nelayan berhasil diperbarui.');
    }

    public function destroy(Nelayan $nelayan)
    {
        $nelayan->delete();
        return redirect()->route('disperindag.nelayans.index')->with('success', 'Nelayan berhasil dihapus.');
    }
}