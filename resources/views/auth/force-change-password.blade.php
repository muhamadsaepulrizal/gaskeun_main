@extends('layouts.guest')
@section('title', 'Ganti Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-brand/30 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-500/20 border border-amber-500/30 rounded-2xl mb-4">
                <i class="fa-solid fa-key text-amber-400 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Ganti Password</h1>
            <p class="text-slate-400 text-sm mt-1">Password Anda telah direset oleh Administrator</p>
        </div>

        <!-- Alert Info -->
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 mb-6 flex gap-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-400 mt-0.5 shrink-0"></i>
            <p class="text-amber-200 text-sm">
                Demi keamanan, Anda <strong>wajib mengganti password</strong> baru sebelum dapat menggunakan sistem. Password baru minimal 8 karakter.
            </p>
        </div>

        <!-- Flash Errors -->
        @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-4">
            @foreach ($errors->all() as $error)
                <p class="text-red-300 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $error }}
                </p>
            @endforeach
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-8">
            <form method="POST" action="{{ route('auth.force-change-password.post') }}">
                @csrf

                <div class="mb-5" x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fa-solid fa-lock text-brand mr-1"></i> Password Baru
                    </label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required minlength="8"
                               class="w-full pl-4 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition"
                               placeholder="Minimal 8 karakter">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-white focus:outline-none transition-colors">
                            <i class="fa-regular fa-eye" x-show="!show"></i>
                            <i class="fa-regular fa-eye-slash" x-show="show" x-cloak></i>
                        </button>
                    </div>
                </div>

                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fa-solid fa-lock text-brand mr-1"></i> Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required minlength="8"
                               class="w-full pl-4 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition"
                               placeholder="Ketik ulang password baru">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-white focus:outline-none transition-colors">
                            <i class="fa-regular fa-eye" x-show="!show"></i>
                            <i class="fa-regular fa-eye-slash" x-show="show" x-cloak></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-shield-check"></i>
                    Simpan Password Baru
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            Sistem GASKEUN &bull; Pengelolaan LPG Bersubsidi
        </p>
    </div>
</div>
@endsection
