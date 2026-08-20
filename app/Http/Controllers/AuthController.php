<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->username,
            'password' => $request->password
        ];

        // Tahap 1: Validasi kredensial
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'NIK / Username / Email atau password salah.',
            ])->onlyInput('username');
        }

        // BR-13: Tahap 2 — Validasi status_aktif pengguna
        $user = Auth::user();
        if (!$user->status_aktif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors([
                'username' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Administrator.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();
        activity()->log('User logged in');

        // Force Password Change: Jika password direset oleh Admin
        if ($user->force_password_change) {
            return redirect()->route('auth.force-change-password')
                ->with('warning', 'Password Anda telah direset oleh Admin. Harap ganti password sekarang.');
        }

        return redirect()->intended('/dashboard')->with('success', 'Berhasil login.');
    }

    public function showForceChangePassword()
    {
        if (!auth()->user()->force_password_change) {
            return redirect()->route('dashboard');
        }
        return view('auth.force-change-password');
    }

    public function forceChangePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();
        $user->update([
            'password'              => Hash::make($request->password),
            'force_password_change' => false,
        ]);

        activity()->performedOn($user)->log('User changed forced password');

        return redirect()->route('dashboard')->with('success', 'Password berhasil diubah. Selamat datang!');
    }

    public function logout(Request $request)
    {
        activity()->log('User logged out');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
