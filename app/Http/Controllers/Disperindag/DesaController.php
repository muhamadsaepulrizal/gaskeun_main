<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use App\Models\Kecamatan;


class DesaController extends Controller
{
    public function index()
    {
        $items = Desa::with(['kecamatan'])->latest()->paginate(10);
        return view('disperindag.desas.index', compact('items'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::all();
        
        return view('disperindag.desas.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required',
            'nama_desa' => 'required'
        ]);

        Desa::create($request->all());
        return redirect()->route('disperindag.desas.index')->with('success', 'Desa berhasil ditambahkan.');
    }

    public function edit(Desa $desa)
    {
        $kecamatans = Kecamatan::all();
        
        $item = $desa;
        return view('disperindag.desas.edit', compact('item', 'kecamatans'));
    }

    public function update(Request $request, Desa $desa)
    {
        $request->validate([
            'kecamatan_id' => 'required',
            'nama_desa' => 'required'
        ]);

        $desa->update($request->all());
        return redirect()->route('disperindag.desas.index')->with('success', 'Desa berhasil diperbarui.');
    }

    public function destroy(Desa $desa)
    {
        $desa->delete();
        return redirect()->route('disperindag.desas.index')->with('success', 'Desa berhasil dihapus.');
    }
}