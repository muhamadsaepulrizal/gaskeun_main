@extends('layouts.app')
@section('title', 'Ubah Password')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    
    

    @if($errors->any())
    <div class="mb-6 alert-error max-w-2xl">
        <i class="fa-solid fa-circle-xmark text-lg"></i>
        <div>
            <p class="font-bold">Gagal memperbarui password:</p>
            <ul class="list-disc ml-5 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <div class="card p-8 md:p-10 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
            
            <div class="mb-8 pb-6 border-b border-slate-100">
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Ubah Password</h2>
                <p class="text-slate-500 mt-2 text-sm">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-brand transition-colors">Password Saat Ini</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors">
                            <i class="fa-solid fa-unlock-keyhole"></i>
                        </span>
                        <input type="password" name="current_password" required autofocus
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all duration-300 placeholder:text-slate-400" 
                            placeholder="Masukkan password Anda saat ini">
                    </div>
                </div>

                <div class="pt-2"></div>

                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-brand transition-colors">Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required minlength="8"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all duration-300 placeholder:text-slate-400" 
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-brand transition-colors">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors">
                            <i class="fa-solid fa-lock-check"></i>
                        </span>
                        <input type="password" name="password_confirmation" required minlength="8"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all duration-300 placeholder:text-slate-400" 
                            placeholder="Ketik ulang password baru">
                    </div>
                </div>

                <div class="pt-8 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-100 mt-8">
                    <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
