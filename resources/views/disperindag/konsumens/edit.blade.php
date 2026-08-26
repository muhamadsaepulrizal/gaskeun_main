@extends('layouts.app')
@section('title', 'Edit Data Konsumen')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Data Konsumen</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui data konsumen penerima LPG bersubsidi secara global.</p>
        </div>
        <a href="{{ route('disperindag.konsumens.index') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
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
             x-data="konsumenForm()">
            <form method="POST" action="{{ route('disperindag.konsumens.update', $konsumen->id) }}">
                @csrf
                @method('PUT')

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
                        </label>
                        @endforeach
                    </div>
                    @error('kategori')<p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Form Fields -->
                <div class="p-5 bg-slate-50 rounded-xl border border-slate-100 mb-6">
                    <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-brand"></i> Detail Data Konsumen
                    </h3>

                    <!-- NIK -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $konsumen->nik) }}" maxlength="16" pattern="[0-9]{16}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition font-mono"
                               placeholder="16 digit angka NIK KTP">
                        @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $konsumen->nama_lengkap) }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                               placeholder="Masukkan nama lengkap">
                        @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Kecamatan -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="kecamatan_id" x-model="kecamatan_id" @change="fetchDesa()" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                        @error('kecamatan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Desa -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Desa/Kelurahan <span class="text-red-500">*</span></label>
                        <select name="desa_kelurahan_id" x-model="desa_id" required :disabled="isLoadingDesa || desas.length === 0"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition disabled:bg-slate-100 disabled:cursor-not-allowed">
                            <option value="">-- Pilih Desa --</option>
                            <template x-for="desa in desas" :key="desa.id">
                                <option :value="desa.id" x-text="desa.nama_desa" :selected="desa_id == desa.id"></option>
                            </template>
                        </select>
                        <span x-show="isLoadingDesa" class="text-xs text-brand mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-notch fa-spin"></i> Memuat data desa...</span>
                        @error('desa_kelurahan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition resize-none" placeholder="Masukkan alamat lengkap RT/RW (Opsional)">{{ old('alamat', $konsumen->alamat) }}</textarea>
                        @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            :disabled="kategori === ''"
                            :class="kategori === '' ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-brand hover:bg-brand-dark text-white shadow-brand/30 shadow-lg hover:-translate-y-0.5'"
                            class="flex-1 font-bold py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('disperindag.konsumens.index') }}"
                       class="px-6 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-colors flex items-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('konsumenForm', () => ({
            kategori: '{{ old('kategori', $konsumen->kategori) }}',
            kecamatan_id: '{{ old('kecamatan_id', $konsumen->kecamatan_id) }}',
            desa_id: '{{ old('desa_kelurahan_id', $konsumen->desa_kelurahan_id) }}',
            desas: [],
            isLoadingDesa: false,

            init() {
                if (this.kecamatan_id) {
                    this.fetchDesa();
                }
            },

            async fetchDesa() {
                this.desas = [];
                if (!this.kecamatan_id) {
                    this.desa_id = '';
                    return;
                }

                this.isLoadingDesa = true;
                try {
                    const response = await fetch(`/keluhan/get-desas/${this.kecamatan_id}`);
                    if (response.ok) {
                        this.desas = await response.json();
                    }
                } catch (error) {
                    console.error('Error fetching desas:', error);
                } finally {
                    this.isLoadingDesa = false;
                }
            }
        }));
    });
</script>
@endpush
