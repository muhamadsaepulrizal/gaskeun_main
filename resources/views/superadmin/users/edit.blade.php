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

    @php
        $currentRole = old('role') ?? ($user->roles->first()->name ?? '');
        $profil = null;
        if ($currentRole === 'Agen LPG') {
            $profil = $user->profilAgen;
        } elseif ($currentRole === 'Pangkalan LPG') {
            $profil = $user->pangkalanProfile;
        }
        
        $oldKecamatan = old('kecamatan_id', $profil->kecamatan_id ?? '');
        $oldDesa = old('desa_kelurahan_id', $profil->desa_kelurahan_id ?? '');
        $oldAlamat = old('alamat', $profil->alamat ?? '');
        $oldLat = old('latitude', $profil->latitude ?? '');
        $oldLng = old('longitude', $profil->longitude ?? '');
        $oldAgenPembina = old('agen_pembina_id', $profil->agen_pembina_id ?? '');
    @endphp

    <div class="max-w-2xl bg-white rounded-xl border border-slate-200 shadow-sm p-6" x-data="{ role: '{{ $currentRole }}' }">
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
                <select name="role" required x-model="role" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition">
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
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
                            <option value="{{ $agen->id }}" {{ $oldAgenPembina == $agen->id ? 'selected' : '' }}>
                                {{ $agen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Lokasi & Peta Khusus Agen / Pangkalan -->
            <div x-show="role === 'Agen LPG' || role === 'Pangkalan LPG'" x-transition x-cloak class="mt-6 border-t border-slate-200 pt-6" x-data="locationForm()">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Lokasi & Titik Koordinat</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="kecamatan_id" id="kecamatan_id" x-model="kecamatan_id" @change="fetchDesa()" :required="role === 'Agen LPG' || role === 'Pangkalan LPG'"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition bg-white">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Desa/Kelurahan <span class="text-red-500">*</span></label>
                        <select name="desa_kelurahan_id" id="desa_kelurahan_id" x-model="desa_id" :required="role === 'Agen LPG' || role === 'Pangkalan LPG'" :disabled="isLoadingDesa || desas.length === 0"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition bg-white disabled:bg-slate-100 disabled:cursor-not-allowed">
                            <option value="">-- Pilih Desa --</option>
                            <template x-for="desa in desas" :key="desa.id">
                                <option :value="desa.id" x-text="desa.nama_desa" :selected="desa_id == desa.id"></option>
                            </template>
                        </select>
                        <span x-show="isLoadingDesa" class="text-xs text-brand mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-notch fa-spin"></i> Memuat data desa...</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="3" :required="role === 'Agen LPG' || role === 'Pangkalan LPG'"
                              class="w-full border border-slate-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition resize-none"
                              placeholder="Masukkan alamat lengkap...">{{ $oldAlamat }}</textarea>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Cari Lokasi di Peta</label>
                    <div class="flex gap-2 mb-3">
                        <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchLocation()" placeholder="Ketik nama tempat/alamat..."
                               class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand">
                        <button type="button" @click="searchLocation()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 rounded-lg font-semibold text-sm transition">
                            <i class="fa-solid fa-search"></i> Cari
                        </button>
                    </div>
                    
                    <div class="relative w-full h-[300px] rounded-xl overflow-hidden border border-slate-300 shadow-inner z-10" id="map-container">
                        <!-- MapLibre container -->
                        <div id="map" class="w-full h-full"></div>
                        <div class="absolute top-2 left-2 z-[20] bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded shadow-sm border border-slate-200 text-slate-600">
                            <i class="fa-solid fa-hand-pointer text-brand mr-1"></i> Klik pada peta untuk set titik
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Latitude <span class="text-red-500">*</span></label>
                        <input type="text" name="latitude" id="latitude" x-model="lat" readonly :required="role === 'Agen LPG' || role === 'Pangkalan LPG'"
                               class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Longitude <span class="text-red-500">*</span></label>
                        <input type="text" name="longitude" id="longitude" x-model="lng" readonly :required="role === 'Agen LPG' || role === 'Pangkalan LPG'"
                               class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none">
                    </div>
                </div>
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
        Alpine.data('locationForm', () => ({
            kecamatan_id: '{{ $oldKecamatan }}',
            desa_id: '{{ $oldDesa }}',
            desas: [],
            isLoadingDesa: false,
            searchQuery: '',
            lat: '{{ $oldLat }}',
            lng: '{{ $oldLng }}',
            map: null,
            marker: null,

            init() {
                if (this.kecamatan_id) {
                    this.fetchDesa();
                }

                // Wait for the x-show transition to finish or just initialize map on first show
                this.$watch('role', (value) => {
                    if (value === 'Agen LPG' || value === 'Pangkalan LPG') {
                        setTimeout(() => {
                            if (!this.map) this.initMap();
                        }, 100);
                    }
                });
                
                // Also initialize on load if it's already Agen or Pangkalan
                if (this.role === 'Agen LPG' || this.role === 'Pangkalan LPG') {
                    setTimeout(() => {
                        if (!this.map) this.initMap();
                    }, 100);
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
            }
        }));
    });
</script>
@endpush
