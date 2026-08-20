<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Kk;
use Illuminate\Http\Request;
use App\Models\Desa;


class KkController extends Controller
{
    public function index()
    {
        $items = Kk::with(['desa'])->latest()->paginate(10);
        return view('disperindag.kks.index', compact('items'));
    }

    public function create()
    {
        $desas = Desa::all();
        
        return view('disperindag.kks.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required',
            'nomor_kk' => 'required',
            'alamat_lengkap' => 'required'
        ]);

        Kk::create($request->all());
        return redirect()->route('disperindag.kks.index')->with('success', 'Kk berhasil ditambahkan.');
    }

    public function edit(Kk $kk)
    {
        $desas = Desa::all();
        
        $item = $kk;
        return view('disperindag.kks.edit', compact('item', 'desas'));
    }

    public function update(Request $request, Kk $kk)
    {
        $request->validate([
            'desa_id' => 'required',
            'nomor_kk' => 'required',
            'alamat_lengkap' => 'required'
        ]);

        $kk->update($request->all());
        return redirect()->route('disperindag.kks.index')->with('success', 'Kk berhasil diperbarui.');
    }

    public function destroy(Kk $kk)
    {
        $kk->delete();
        return redirect()->route('disperindag.kks.index')->with('success', 'Kk berhasil dihapus.');
    }
}