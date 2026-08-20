<?php

namespace App\Http\Controllers\Agen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PangkalanProfile;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PangkalanBinaanController extends Controller
{
    public function index(Request $request)
    {
        $agenId = auth()->id();
        $query = PangkalanProfile::where('agen_pembina_id', $agenId)->with('user');

        if ($search = $request->input('search')) {
            $query->where('nama_pangkalan', 'like', "%{$search}%");
        }

        $pangkalans = $query->latest()->paginate(10);
        return view('agen.pangkalan.index', compact('pangkalans'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::all();
        return view('agen.pangkalan.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:255',
            'no_registrasi'  => 'nullable|string|max:100',
            'email'          => 'nullable|email|unique:users',
            'alamat'         => 'required|string',
            'kontak'         => 'required|string|max:20',
            'kecamatan_id'   => 'nullable|exists:kecamatans,id',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'kuota_bulanan'  => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // Generate username otomatis
            $baseUsername = Str::slug($request->nama_pangkalan);
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // Generate password acak 8 karakter atau default
            $defaultPassword = 'password123'; // Bisa diganti Str::random(8)
            
            // 1. Create User
            $user = User::create([
                'name'     => $request->nama_pangkalan,
                'username' => $username,
                'email'    => $request->email,
                'password' => Hash::make($defaultPassword),
                'status_aktif' => true,
                'force_password_change' => true, // Paksa pangkalan ganti password saat login pertama kali
            ]);

            $user->assignRole('Pangkalan LPG');

            // 2. Create Pangkalan Profile
            PangkalanProfile::create([
                'user_id'         => $user->id,
                'agen_pembina_id' => auth()->id(),
                'kecamatan_id'    => $request->kecamatan_id,
                'nama_pangkalan'  => $request->nama_pangkalan,
                'no_registrasi'   => $request->no_registrasi,
                'alamat'          => $request->alamat,
                'kontak'          => $request->kontak,
                'latitude'        => $request->latitude,
                'longitude'       => $request->longitude,
                'kuota_bulanan'   => $request->kuota_bulanan,
            ]);

            // 3. Create Stok Awal (0)
            \App\Models\StokPangkalan::create([
                'user_id'      => $user->id,
                'jumlah_tabung' => 0,
            ]);

            // Log activity
            activity()->performedOn($user)->log('Agen ' . auth()->user()->name . ' menambahkan pangkalan binaan: ' . $request->nama_pangkalan);
        });

        return redirect()->route('agen.pangkalan-binaan.index')->with('success', 'Pangkalan berhasil ditambahkan. Akun Pangkalan otomatis terbuat.');
    }

    public function edit($id)
    {
        $pangkalan = PangkalanProfile::where('agen_pembina_id', auth()->id())->findOrFail($id);
        $kecamatans = Kecamatan::all();
        return view('agen.pangkalan.edit', compact('pangkalan', 'kecamatans'));
    }

    public function update(Request $request, $id)
    {
        $pangkalan = PangkalanProfile::where('agen_pembina_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'nama_pangkalan' => 'required|string|max:255',
            'no_registrasi'  => 'nullable|string|max:100',
            'alamat'         => 'required|string',
            'kontak'         => 'required|string|max:20',
            'kecamatan_id'   => 'nullable|exists:kecamatans,id',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'kuota_bulanan'  => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $pangkalan) {
            $pangkalan->update($request->only([
                'nama_pangkalan', 'no_registrasi', 'alamat', 'kontak', 'kecamatan_id', 'latitude', 'longitude', 'kuota_bulanan'
            ]));

            // Update user name juga biar sinkron
            $pangkalan->user->update(['name' => $request->nama_pangkalan]);
        });

        return redirect()->route('agen.pangkalan-binaan.index')->with('success', 'Data pangkalan berhasil diperbarui.');
    }
}
