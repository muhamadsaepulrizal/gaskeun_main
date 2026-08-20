<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:users,username',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Validasi ke tabel penduduks jika NIK harus ada di master data
        $penduduk = \App\Models\Penduduk::where('nik', $request->nik)->first();
        if (!$penduduk) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan dalam data kependudukan.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->nik, // Use username column for NIK
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Publik');

        Auth::login($user);

        activity()->performedOn($user)->log('User registered via public registration');

        return redirect('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}
