<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman form ganti password
     */
    public function editPassword()
    {
        return view('profile.password');
    }

    /**
     * Proses update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.different' => 'Password baru tidak boleh sama dengan password saat ini.',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->password),
            'force_password_change' => false // Jika sebelumnya ada flag force_change, matikan
        ]);

        activity()->performedOn($user)->log('Berhasil mengubah password.');

        return back()->with('success', 'Password Anda berhasil diperbarui.');
    }
}
