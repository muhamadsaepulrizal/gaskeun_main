<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;


class KecamatanController extends Controller
{
    public function index()
    {
        $items = Kecamatan::latest()->paginate(10);
        return view('disperindag.kecamatans.index', compact('items'));
    }

    public function create()
    {
        
        return view('disperindag.kecamatans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required'
        ]);

        Kecamatan::create($request->all());
        return redirect()->route('disperindag.kecamatans.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        
        $item = $kecamatan;
        return view('disperindag.kecamatans.edit', compact('item'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan' => 'required'
        ]);

        $kecamatan->update($request->all());
        return redirect()->route('disperindag.kecamatans.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('disperindag.kecamatans.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}