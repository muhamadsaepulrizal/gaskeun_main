<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\RumahTanggaSasaran;
use Illuminate\Http\Request;
use App\Models\Kk;


class RumahTanggaSasaranController extends Controller
{
    public function index()
    {
        $items = RumahTanggaSasaran::with(['kk'])->latest()->paginate(10);
        return view('disperindag.rts.index', compact('items'));
    }

    public function create()
    {
        $kks = Kk::all();
        
        return view('disperindag.rts.create', compact('kks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kk_id' => 'required',
            'kriteria_bantuan' => 'required',
            'status_penerima' => 'required'
        ]);

        RumahTanggaSasaran::create($request->all());
        return redirect()->route('disperindag.rts.index')->with('success', 'RumahTanggaSasaran berhasil ditambahkan.');
    }

    public function edit(RumahTanggaSasaran $rumahTanggaSasaran)
    {
        $kks = Kk::all();
        
        $item = $rumahTanggaSasaran;
        return view('disperindag.rts.edit', compact('item', 'kks'));
    }

    public function update(Request $request, RumahTanggaSasaran $rumahTanggaSasaran)
    {
        $request->validate([
            'kk_id' => 'required',
            'kriteria_bantuan' => 'required',
            'status_penerima' => 'required'
        ]);

        $rumahTanggaSasaran->update($request->all());
        return redirect()->route('disperindag.rts.index')->with('success', 'RumahTanggaSasaran berhasil diperbarui.');
    }

    public function destroy(RumahTanggaSasaran $rumahTanggaSasaran)
    {
        $rumahTanggaSasaran->delete();
        return redirect()->route('disperindag.rts.index')->with('success', 'RumahTanggaSasaran berhasil dihapus.');
    }
}