<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Models\Kk;


class PendudukController extends Controller
{
    public function index()
    {
        $items = Penduduk::with(['kk'])->latest()->paginate(10);
        return view('disperindag.penduduks.index', compact('items'));
    }

    public function create()
    {
        $kks = Kk::all();
        
        return view('disperindag.penduduks.create', compact('kks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kk_id' => 'required',
            'nik' => 'required',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required'
        ]);

        Penduduk::create($request->all());
        return redirect()->route('disperindag.penduduks.index')->with('success', 'Penduduk berhasil ditambahkan.');
    }

    public function edit(Penduduk $penduduk)
    {
        $kks = Kk::all();
        
        $item = $penduduk;
        return view('disperindag.penduduks.edit', compact('item', 'kks'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $request->validate([
            'kk_id' => 'required',
            'nik' => 'required',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required'
        ]);

        $penduduk->update($request->all());
        return redirect()->route('disperindag.penduduks.index')->with('success', 'Penduduk berhasil diperbarui.');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return redirect()->route('disperindag.penduduks.index')->with('success', 'Penduduk berhasil dihapus.');
    }
}