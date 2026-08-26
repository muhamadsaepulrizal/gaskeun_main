@extends('layouts.app')
@section('title', 'Tambah Pangkalan Binaan')

@push('styles')
<link href="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.css" rel="stylesheet" />
<style>
    .maplibregl-popup-content {
        font-family: 'Poppins', sans-serif;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pangkalanForm', () => ({
            kecamatan_id: '{{ old('kecamatan_id') }}',
            desa_id: '{{ old('desa_kelurahan_id') }}',
            desas: [],
            isLoadingDesa: false,
            searchQuery: '',
            lat: '{{ old('latitude') }}',
            lng: '{{ old('longitude') }}',
            map: null,
            marker: null,

            init() {
                if (this.kecamatan_id) {
                    this.fetchDesa();
                }
                setTimeout(() => {
                    this.initMap();
                }, 100);
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
            },

            initMap() {
                this.map = new maplibregl.Map({
                    container: 'map',
                    style: 'https://tiles.openfreemap.org/styles/liberty', // OpenFreeMap Liberty style
                    center: [this.lng || 107.9000, this.lat || -7.2167],
                    zoom: this.lng && this.lat ? 15 : 10,
                    attributionControl: false
                });

                this.map.addControl(new maplibregl.NavigationControl());

                if (this.lng && this.lat) {
                    this.setMarker(parseFloat(this.lng), parseFloat(this.lat));
                }

                // Click event to set marker
                this.map.on('click', (e) => {
                    this.setMarker(e.lngLat.lng, e.lngLat.lat);
                });
            },

            setMarker(lng, lat) {
                if (this.marker) {
                    this.marker.remove();
                }

                this.marker = new maplibregl.Marker({ color: '#DC2626' })
                    .setLngLat([lng, lat])
                    .addTo(this.map);

                this.lat = lat.toFixed(7);
                this.lng = lng.toFixed(7);
            },

            async searchLocation() {
                if (!this.searchQuery.trim()) return;

                const q = encodeURIComponent(this.searchQuery + ', Garut');
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${q}&limit=1`);
                    const data = await response.json();
                    
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        
                        this.map.flyTo({
                            center: [lng, lat],
                            zoom: 15,
                            essential: true
                        });
                        
                        this.setMarker(lng, lat);
                    } else {
                        alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    alert('Terjadi kesalahan saat mencari lokasi.');
                }
            },
            
            getLocation() {
                if (!navigator.geolocation) {
                    alert('Browser tidak mendukung Geolocation.');
                    return;
                }
                
                const btn = document.getElementById('btn-lokasi');
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengambil...';
                btn.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        this.setMarker(lng, lat);
                        this.map.flyTo({ center: [lng, lat], zoom: 15 });
                        
                        btn.innerHTML = '<i class="fa-solid fa-check"></i> Lokasi Didapat';
                        btn.classList.replace('bg-blue-50','bg-emerald-50');
                        btn.classList.replace('text-blue-700','text-emerald-700');
                        
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.replace('bg-emerald-50','bg-blue-50');
                            btn.classList.replace('text-emerald-700','text-blue-700');
                            btn.disabled = false;
                        }, 3000);
                    }, 
                    (err) => {
                        alert('Gagal mendapatkan lokasi: ' + err.message);
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                );
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="p-6 lg:p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
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
        <form action="{{ route('agen.pangkalan-binaan.store') }}" method="POST" class="space-y-6" x-data="pangkalanForm()">
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
                    <select name="kecamatan_id" x-model="kecamatan_id" @change="fetchDesa()" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition bg-white">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}">
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Desa/Kelurahan</label>
                    <select name="desa_kelurahan_id" x-model="desa_id" :disabled="isLoadingDesa || desas.length === 0" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition bg-white disabled:bg-slate-100 disabled:cursor-not-allowed">
                        <option value="">-- Pilih Desa --</option>
                        <template x-for="desa in desas" :key="desa.id">
                            <option :value="desa.id" x-text="desa.nama_desa" :selected="desa_id == desa.id"></option>
                        </template>
                    </select>
                    <span x-show="isLoadingDesa" class="text-xs text-emerald-600 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-notch fa-spin"></i> Memuat data desa...</span>
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
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700">Koordinat Lokasi (GIS)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Digunakan untuk peta GIS dan heatmap distribusi</p>
                    </div>
                    <button type="button" id="btn-lokasi" @click="getLocation()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold transition">
                        <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya
                    </button>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Cari Lokasi di Peta</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchLocation()" placeholder="Ketik nama tempat/alamat..."
                               class="flex-1 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                        <button type="button" @click="searchLocation()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 rounded-lg font-semibold text-sm transition">
                            <i class="fa-solid fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                
                <div class="relative w-full h-[250px] rounded-xl overflow-hidden border border-slate-200 shadow-inner z-10 mb-4" id="map-container">
                    <div id="map" class="w-full h-full"></div>
                    <div class="absolute top-2 left-2 z-[20] bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded shadow-sm border border-slate-200 text-slate-600">
                        <i class="fa-solid fa-hand-pointer text-emerald-600 mr-1"></i> Klik pada peta untuk set titik
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" x-model="lat" readonly
                               class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" x-model="lng" readonly
                               class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none transition">
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
