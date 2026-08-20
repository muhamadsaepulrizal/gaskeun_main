@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi dan hak akses untuk <span class="font-bold text-slate-700">{{ $user->name }}</span>.</p>
        </div>
        <a href="{{ route('superadmin.users.index') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar User
        </a>
    </div>

    <div class="max-w-2xl bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Informasi Akun</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Nama lengkap">
                    @error('name')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Username / NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Username atau NIK">
                    @error('username')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Hak Akses (Role) <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition">
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                                {{ (old('role') ?? ($user->roles->first()->name ?? '')) == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
                <p class="text-xs text-blue-700">
                    <span class="font-bold"><i class="fa-solid fa-circle-info mr-1"></i> Info:</span>
                    Untuk mengganti password pengguna ini, gunakan fitur "Reset Password" di halaman daftar pengguna.
                </p>
            </div>

            <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('superadmin.users.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-brand hover:bg-brand-dark transition-colors flex items-center">
                    <i class="fa-solid fa-check mr-2"></i> Update Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
