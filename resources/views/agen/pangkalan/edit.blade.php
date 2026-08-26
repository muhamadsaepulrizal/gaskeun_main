@extends('layouts.app')
@section('title', 'Edit Pangkalan Binaan')

@push('scripts')
<script>
    function getLocation() {
        if (!navigator.geolocation) { alert('Browser tidak mendukung Geolocation.'); return; }
        const btn = document.getElementById('btn-lokasi');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengambil...';
        btn.disabled = true;
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitude').value  = pos.coords.latitude.toFixed(7);
            document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Lokasi Diperbarui';
        }, function(err) {
            alert('Gagal: ' + err.message);
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
            <h1 class="text-2xl font-bold text-slate-800">Edit Pangkalan: {{ $pangkalan->nama_pangkalan }}</h1>
        </div>
        <a href="{{ route('agen.pangkalan-binaan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-semibold mb-1"><i class="fa-solid fa-circle-xmark mr-1"></i> Terdapat Kesalahan:</div>
            <ul class="list-disc ml-5 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8 max-w-3xl">
        <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-sm flex items-start gap-3">
            <i class="fa-solid fa-circle-info mt-0.5 text-slate-400 shrink-0"></i>
            <div>
                <strong>Username Login Saat Ini:</strong>
                <code class="bg-slate-100 px-2 py-0.5 rounded ml-1">{{ $pangkalan->user->username ?? 'N/A' }}</code>
                — Untuk reset password pangkalan, gunakan menu Super Admin.
            </div>
        </div>

        <form action="{{ route('agen.pangkalan-binaan.update', $pangkalan->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pangkalan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pangkalan" value="{{ old('nama_pangkalan', $pangkalan->nama_pangkalan) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Registrasi SPBE</label>
                    <input type="text" name="no_registrasi" value="{{ old('no_registrasi', $pangkalan->no_registrasi) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="kontak" value="{{ old('kontak', $pangkalan->kontak) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kecamatan</label>
                    <select name="kecamatan_id" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition bg-white">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan_id', $pangkalan->kecamatan_id) == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kuota Bulanan (Tabung) <span class="text-red-500">*</span></label>
                    <input type="number" name="kuota_bulanan" value="{{ old('kuota_bulanan', $pangkalan->kuota_bulanan) }}" min="0" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" required rows="3"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition resize-none">{{ old('alamat', $pangkalan->alamat) }}</textarea>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700">Koordinat Lokasi (GIS)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Klik tombol untuk update koordinat ke lokasi Anda saat ini</p>
                    </div>
                    <button type="button" id="btn-lokasi" onclick="getLocation()"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold transition">
                        <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $pangkalan->latitude) }}"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                               placeholder="-7.0000000">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $pangkalan->longitude) }}"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                               placeholder="107.0000000">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('agen.pangkalan-binaan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #0B5240, #14765C);">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
