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
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Kategori Konsumen <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach(['Rumah Tangga', 'Usaha Mikro', 'Petani Sasaran', 'Nelayan Sasaran'] as $kat)
                        <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 group"
                               :class="kategori === '{{ $kat }}' ? 'border-brand bg-brand/5 shadow-md shadow-brand/10' : 'border-slate-200 hover:border-brand/40 hover:bg-slate-50'">
                            <input type="radio" name="kategori" value="{{ $kat }}"
                                   x-model="kategori"
                                   class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-slate-800" :class="kategori === '{{ $kat }}' ? 'text-brand' : ''">{{ $kat }}</span>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors"
                                     :class="kategori === '{{ $kat }}' ? 'border-brand bg-brand' : 'border-slate-300'">
                                    <i class="fa-solid fa-check text-white text-xs" x-show="kategori === '{{ $kat }}'"></i>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500">
                                @if($kat == 'Rumah Tangga') Warga dengan NIK & Desa
                                @elseif($kat == 'Usaha Mikro') Usaha dengan NIB & Jenis Usaha
                                @elseif($kat == 'Petani Sasaran') Petani dengan NIK & Kelompok Tani
                                @elseif($kat == 'Nelayan Sasaran') Nelayan dengan NIK & Kapal
                                @endif
                            </p>
                        </label>
                        @endforeach
                    </div>
                    @error('kategori')<p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Dynamic Form Fields -->
                <div class="p-5 bg-slate-50 rounded-xl border border-slate-100 mb-6" x-show="kategori !== ''" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-brand"></i> Detail <span x-text="kategori"></span>
                    </h3>

                    <!-- NIK (Rumah Tangga, Petani, Nelayan) -->
                    <div class="mb-4" x-show="['Rumah Tangga', 'Petani Sasaran', 'Nelayan Sasaran'].includes(kategori)">
                        <label class="block text-sm font-bold text-slate-700 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" pattern="[0-9]{16}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition font-mono"
                               placeholder="16 digit angka NIK KTP">
                        @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- NIB (Usaha Mikro) -->
                    <div class="mb-4" x-show="kategori === 'Usaha Mikro'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">NIB (Nomor Induk Berusaha) <span class="text-red-500">*</span></label>
                        <input type="text" name="nib" value="{{ old('nib') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition font-mono"
                               placeholder="Masukkan NIB">
                        @error('nib')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nama Lengkap / Nama Usaha -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <span x-text="kategori === 'Usaha Mikro' ? 'Nama Usaha' : 'Nama Lengkap'">Nama Lengkap</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                               placeholder="Masukkan nama">
                        @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Desa (Rumah Tangga) -->
                    <div class="mb-2" x-show="kategori === 'Rumah Tangga'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Desa <span class="text-red-500">*</span></label>
                        <input type="text" name="desa" value="{{ old('desa') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                               placeholder="Nama Desa tempat tinggal">
                        @error('desa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Jenis Usaha (Usaha Mikro) -->
                    <div class="mb-2" x-show="kategori === 'Usaha Mikro'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Usaha <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                               placeholder="Contoh: Warung Nasi, Laundry, dll">
                        @error('jenis_usaha')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Kelompok Tani (Petani Sasaran) -->
                    <div class="mb-2" x-show="kategori === 'Petani Sasaran'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kelompok Tani <span class="text-red-500">*</span></label>
                        <input type="text" name="kelompok_tani" value="{{ old('kelompok_tani') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                               placeholder="Nama kelompok tani">
                        @error('kelompok_tani')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Kapal (Nelayan Sasaran) -->
                    <div class="mb-2" x-show="kategori === 'Nelayan Sasaran'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kapal <span class="text-red-500">*</span></label>
                        <input type="text" name="kapal" value="{{ old('kapal') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                               placeholder="Nama kapal nelayan">
                        @error('kapal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            :disabled="kategori === ''"
                            :class="kategori === '' ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-brand hover:bg-brand-dark text-white shadow-brand/30 shadow-lg hover:-translate-y-0.5' flex-1 font-bold py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Simpan Pendaftaran
                    </button>
                    <a href="{{ route('pangkalan.konsumen.index') }}"
                       class="px-6 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-colors flex items-center gap-2">
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
