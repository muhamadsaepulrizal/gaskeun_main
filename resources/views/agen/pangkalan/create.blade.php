@extends('layouts.app')
@section('title', 'Tambah Pangkalan Binaan')

@push('scripts')
<script>
    // Auto-fill koordinat via Geolocation
    function getLocation() {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung Geolocation.');
            return;
        }
        const btn = document.getElementById('btn-lokasi');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengambil...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitude').value  = pos.coords.latitude.toFixed(7);
            document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Lokasi Didapat';
            btn.classList.replace('bg-blue-50','bg-emerald-50');
            btn.classList.replace('text-blue-700','text-emerald-700');
        }, function(err) {
            alert('Gagal mendapatkan lokasi: ' + err.message);
            btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya';
            btn.disabled = false;
        });
    }
</script>
@endpush

@section('content')
<div class="p-6 lg:p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-mono mb-1 text-emerald-600 tracking-widest uppercase">// Agen LPG</p>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Pangkalan Binaan</h1>
            <p class="text-sm text-slate-500 mt-1">Akun login pangkalan akan dibuat otomatis oleh sistem.</p>
        </div>
        <a href="{{ route('agen.pangkalan-binaan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-semibold mb-1 flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> Terdapat Kesalahan:</div>
            <ul class="list-disc ml-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8 max-w-3xl">
        <form action="{{ route('agen.pangkalan-binaan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-sm flex items-start gap-3">
                <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                <div>
                    <strong>Informasi:</strong> Sistem akan otomatis membuatkan akun login untuk pangkalan ini.
                    Username dibuat dari nama pangkalan, password default: <code class="bg-amber-100 px-1 rounded">password123</code>.
                    Pangkalan wajib ganti password saat login pertama kali.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pangkalan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pangkalan" value="{{ old('nama_pangkalan') }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition"
                           placeholder="Contoh: UD Berkah Jaya">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Registrasi SPBE</label>
                    <input type="text" name="no_registrasi" value="{{ old('no_registrasi') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                           placeholder="Nomor registrasi resmi (opsional)">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="kontak" value="{{ old('kontak') }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                           placeholder="Contoh: 081234567890">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Pangkalan (untuk login)</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                           placeholder="email@pangkalan.com (opsional)">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kecamatan</label>
                    <select name="kecamatan_id" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition bg-white">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kuota Bulanan (Tabung) <span class="text-red-500">*</span></label>
                    <input type="number" name="kuota_bulanan" value="{{ old('kuota_bulanan', 100) }}" min="0" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" required rows="3"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition resize-none"
                              placeholder="Masukkan alamat lengkap pangkalan...">{{ old('alamat') }}</textarea>
                </div>
            </div>

            {{-- Koordinat GPS --}}
            <div class="border-t border-slate-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700">Koordinat Lokasi (GIS)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Digunakan untuk peta GIS dan heatmap distribusi</p>
                    </div>
                    <button type="button" id="btn-lokasi" onclick="getLocation()"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold transition">
                        <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                               placeholder="-7.0000000">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                               placeholder="107.0000000">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('agen.pangkalan-binaan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #0B5240, #14765C);">
                    <i class="fa-solid fa-store mr-1.5"></i> Simpan & Buat Akun Pangkalan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
