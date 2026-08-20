@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan akun pengguna baru dan tentukan hak aksesnya.</p>
        </div>
        <a href="{{ route('superadmin.users.index') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar User
        </a>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 max-w-2xl">
        @foreach($errors->all() as $error)
        <p class="text-red-700 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="max-w-2xl bg-white rounded-xl border border-slate-200 shadow-sm p-6" x-data="{ role: '{{ old('role', '') }}' }">
        <form action="{{ route('superadmin.users.store') }}" method="POST">
            @csrf

            <!-- Informasi Dasar -->
            <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Informasi Akun</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Nama pengguna / instansi">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Username / NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Username login unik">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Email (Opsional)">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password Awal</label>
                    <input type="password" name="password" minlength="8"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="(Otomatis 'password' jika kosong)">
                </div>
            </div>

            <!-- Hak Akses -->
            <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Hak Akses & Relasi</h3>
            
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Role Sistem <span class="text-red-500">*</span></label>
                <select name="role" required x-model="role"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition bg-white">
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Field Khusus Pangkalan (BR-02) -->
            <div class="mb-5" x-show="role === 'Pangkalan LPG'" x-transition x-cloak>
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                    <label class="block text-sm font-bold text-orange-800 mb-2">
                        Pilih Agen Pembina <span class="text-red-500">*</span>
                        <span class="block text-xs font-normal text-orange-600 mt-1">Sesuai BR-02, Pangkalan wajib memiliki 1 Agen Pembina.</span>
                    </label>
                    <select name="agen_pembina_id" :required="role === 'Pangkalan LPG'"
                            class="w-full px-4 py-2.5 border border-orange-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition bg-white">
                        <option value="">-- Pilih Agen --</option>
                        @foreach($agens as $agen)
                            <option value="{{ $agen->id }}" {{ old('agen_pembina_id') == $agen->id ? 'selected' : '' }}>
                                {{ $agen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit"
                        class="flex-1 bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-check"></i> Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
