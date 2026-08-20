@extends('layouts.app')
@section('title', 'Registrasi Konsumen')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Registrasi Konsumen Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan konsumen penerima LPG bersubsidi di pangkalan Anda.</p>
        </div>
        <a href="{{ route('pangkalan.konsumen.index') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Konsumen
        </a>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4">
        @foreach($errors->all() as $error)
        <p class="text-red-700 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <!-- Form dengan Alpine.js untuk tampilan dinamis -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6"
             x-data="{ kategori: '{{ old('kategori', '') }}' }">
            <form method="POST" action="{{ route('pangkalan.konsumen.store') }}">
                @csrf

                <!-- Kategori Konsumen -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Konsumen <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['Rumah Tangga', 'Usaha Mikro', 'Petani', 'Nelayan'] as $kat)
                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all"
                               :class="kategori === '{{ $kat }}' ? 'border-brand bg-brand/5' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="kategori" value="{{ $kat }}"
                                   x-model="kategori"
                                   class="sr-only">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                                 :class="kategori === '{{ $kat }}' ? 'border-brand' : 'border-slate-300'">
                                <div class="w-2 h-2 rounded-full bg-brand" x-show="kategori === '{{ $kat }}'"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ $kat }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Nama lengkap sesuai KTP">
                    @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- NIK — Muncul hanya untuk Rumah Tangga -->
                <div class="mb-5" x-show="kategori === 'Rumah Tangga'" x-transition>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        NIK <span class="text-red-500">*</span>
                        <span class="text-xs font-normal text-slate-400 ml-1">— Akan dienkripsi dan divalidasi unik secara global</span>
                    </label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" pattern="[0-9]{16}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition font-mono"
                           placeholder="16 digit angka NIK">
                    @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- NIB — Muncul hanya untuk Usaha Mikro -->
                <div class="mb-5" x-show="kategori === 'Usaha Mikro'" x-transition>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        NIB (Nomor Induk Berusaha) <span class="text-red-500">*</span>
                        <span class="text-xs font-normal text-slate-400 ml-1">— Divalidasi unik secara global</span>
                    </label>
                    <input type="text" name="nib" value="{{ old('nib') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition font-mono"
                           placeholder="Nomor Induk Berusaha">
                    @error('nib')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Alamat -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition resize-none"
                              placeholder="Alamat lengkap konsumen">{{ old('alamat') }}</textarea>
                </div>

                <!-- Kontak -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP</label>
                    <input type="text" name="kontak" value="{{ old('kontak') }}" maxlength="15"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="08xx-xxxx-xxxx">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="flex-1 bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Daftarkan Konsumen
                    </button>
                    <a href="{{ route('pangkalan.konsumen.index') }}"
                       class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors flex items-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-700">
            <i class="fa-solid fa-shield-halved mr-2"></i>
            <strong>Keamanan Data:</strong> NIK dan NIB akan dienkripsi sebelum disimpan ke database. Validasi dilakukan secara global untuk mencegah duplikasi pendaftaran di pangkalan berbeda.
        </div>
    </div>
</div>
@endsection
