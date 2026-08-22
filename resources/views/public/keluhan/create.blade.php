@extends('layouts.public')

@section('title', 'Ajukan Pengaduan LPG — GASKEUN')

@section('page-title', 'Ajukan Pengaduan LPG')

@section('page-subtitle', 'Sampaikan keluhan Anda terkait distribusi LPG 3kg bersubsidi di Garut')

@section('content')

    <!-- Success Ticket -->
    @if(session('success_ticket'))
    <div id="ticket-card" class="card p-8 text-center mb-6 border border-emerald-200 bg-emerald-50">
        <div class="text-5xl mb-4">✅</div>
        <h2 class="text-2xl font-bold text-emerald-700 mb-2">Laporan Berhasil Dikirim!</h2>
        <p class="text-slate-600 mb-6 text-sm">Tiket laporan Anda telah berhasil dibuat.</p>
        <div class="inline-block bg-white border-2 border-emerald-200 rounded-xl px-8 py-4 mb-6 shadow-sm">
            <p class="text-3xl font-mono font-bold tracking-widest text-emerald-600">{{ session('success_ticket') }}</p>
        </div>
        <p class="text-slate-500 text-xs mb-6 max-w-sm mx-auto">Kami sedang mengunduh tiket ini secara otomatis ke galeri/perangkat Anda. Gunakan kode ini untuk mengecek status laporan.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center" data-html2canvas-ignore>
            <button id="btn-download-ticket" onclick="downloadTicket()" class="inline-flex justify-center items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                <i class="fa-solid fa-download mr-2"></i> Unduh Tiket
            </button>
            <a href="{{ route('public.keluhan.status') }}" class="inline-flex justify-center items-center rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 transition-colors">
                <i class="fa-solid fa-search mr-2"></i> Cek Status Laporan
            </a>
            <a href="{{ route('home') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">Kembali</a>
        </div>
    </div>
    
    <!-- Script Auto Download Ticket -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script>
        function downloadTicket() {
            const btn = document.getElementById('btn-download-ticket');
            if(btn) {
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengunduh...';
                btn.disabled = true;
            }
            
            const ticketElement = document.getElementById('ticket-card');
            
            if (typeof domtoimage === 'undefined') {
                alert('Modul pengunduh belum siap. Pastikan koneksi internet stabil.');
                if(btn) {
                    btn.innerHTML = '<i class="fa-solid fa-download mr-2"></i> Unduh Tiket';
                    btn.disabled = false;
                }
                return;
            }

            // Gunakan dom-to-image
            domtoimage.toPng(ticketElement, { bgcolor: '#ffffff' })
                .then(function (dataUrl) {
                    const link = document.createElement('a');
                    link.download = "Tiket_Pengaduan_GASKEUN.png";
                    link.href = dataUrl;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    if(btn) {
                        btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Berhasil Diunduh';
                        setTimeout(() => {
                            btn.innerHTML = '<i class="fa-solid fa-download mr-2"></i> Unduh Ulang Tiket';
                            btn.disabled = false;
                        }, 3000);
                    }
                })
                .catch(function (error) {
                    console.error("Gagal mengunduh:", error);
                    alert("Gagal mengunduh otomatis. Terjadi kesalahan pada browser Anda.");
                    if(btn) {
                        btn.innerHTML = '<i class="fa-solid fa-download mr-2"></i> Unduh Tiket';
                        btn.disabled = false;
                    }
                });
        }

        // Auto download after page load
        window.addEventListener('load', () => {
            setTimeout(() => {
                downloadTicket();
            }, 1000); 
        });
    </script>
    @else

    <!-- Step Indicator -->
    <div class="step-indicator mb-8">
        <div class="step">
            <div class="step-circle" id="circle-1">1</div>
            <div>
                <div class="font-semibold text-sm" id="label-1">Verifikasi Email</div>
                <div class="step-label" id="sublabel-1">Masukkan alamat Email</div>
            </div>
        </div>
        <div class="step-line" id="line-1-2"></div>
        <div class="step">
            <div class="step-circle" id="circle-2">2</div>
            <div>
                <div class="font-semibold text-sm text-slate-500">Form Pengaduan</div>
                <div class="step-label">Isi detail laporan</div>
            </div>
        </div>
        <div class="step-line" id="line-2-3"></div>
        <div class="step">
            <div class="step-circle" id="circle-3">3</div>
            <div>
                <div class="font-semibold text-sm text-slate-500">Kirim & Tiket</div>
                <div class="step-label">Dapatkan kode tiket</div>
            </div>
        </div>
    </div>

    <!-- === STEP 1: OTP === -->
    <div id="step-otp" class="card p-6 sm:p-8">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Langkah 1: Verifikasi Email</h3>
        <p class="text-slate-500 text-sm mb-6">Kami akan mengirim kode OTP ke alamat Email Anda untuk memverifikasi identitas.</p>

        @if(session('error'))
        <div class="mb-4 alert-error">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
        @endif

        <!-- Input Email -->
        <div id="wa-input-section" class="space-y-4">
            <div class="input-wrapper">
                <label class="label-field">Alamat Email Aktif</label>
                <input type="email" id="email_input" class="input-field" placeholder="Contoh: budi@gmail.com">
            </div>
            <button type="button" id="btn-request-otp" onclick="requestOtp()" class="btn-primary w-full justify-center">
                <i class="fa-solid fa-envelope"></i> Kirim Kode OTP ke Email
            </button>
        </div>

        <!-- Demo Mode Alert (muncul saat mode simulasi) -->
        <div id="demo-alert" class="hidden mt-4 p-4 rounded-xl text-sm bg-yellow-50 border border-yellow-200">
            <p class="text-yellow-800 font-bold mb-1 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> MODE DEMO AKTIF</p>
            <p class="text-yellow-700">SMTP belum dikonfigurasi. Kode OTP Anda:</p>
            <p id="demo-otp-code" class="text-3xl font-mono font-bold text-yellow-600 text-center py-3 tracking-widest"></p>
        </div>

        <!-- Input OTP -->
        <div id="otp-input-section" class="hidden mt-6 space-y-4">
            <div class="p-4 rounded-xl text-sm bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium">
                <i class="fa-solid fa-circle-check mr-1"></i> <span id="otp-sent-msg">OTP berhasil dikirim ke Email Anda. Cek folder Inbox/Spam.</span>
            </div>
            <div class="input-wrapper">
                <label class="label-field text-center">Masukkan Kode OTP (6 digit)</label>
                <input type="text" id="otp_code" class="input-field text-center text-3xl font-mono tracking-widest py-3 font-bold"
                       maxlength="6" placeholder="• • • • • •"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="verifyOtp()" class="btn-primary flex-1 justify-center">
                    <i class="fa-solid fa-shield-check"></i> Verifikasi OTP
                </button>
                <button type="button" onclick="resetOtp()" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">
                    Ganti Email
                </button>
            </div>
        </div>
    </div>

    <!-- === STEP 2: Form Pengaduan === -->
    <div id="step-form" class="hidden">
        <div class="card p-4 mb-4 bg-emerald-50 border-emerald-200">
            <p class="text-emerald-700 text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-shield-check text-emerald-600"></i>
                Email <span id="verified-wa-display" class="font-bold underline decoration-emerald-300 underline-offset-2"></span> terverifikasi
            </p>
        </div>

        <div class="card p-6 sm:p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Langkah 2: Isi Detail Pengaduan</h3>

            <form action="{{ route('public.keluhan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="email" id="hidden_email">

                <div class="input-wrapper">
                    <label class="label-field">Nama Lengkap Pelapor <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelapor" required class="input-field" placeholder="Sesuai KTP">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="input-wrapper">
                        <label class="label-field">Kecamatan Lokasi Kejadian <span class="text-red-500">*</span></label>
                        <select name="kecamatan_id" id="kecamatan_id" required class="input-field">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-wrapper">
                        <label class="label-field">Desa / Kelurahan Kejadian <span class="text-red-500">*</span></label>
                        <select name="desa_id" id="desa_id" required class="input-field">
                            <option value="">-- Pilih Desa --</option>
                        </select>
                    </div>
                    <div class="input-wrapper">
                        <label class="label-field">Nama Pangkalan Terkait (Opsional)</label>
                        <select name="pangkalan_id" id="pangkalan_id" class="input-field">
                            <option value="">-- Tidak Tahu --</option>
                        </select>
                    </div>
                </div>

                <div class="input-wrapper">
                    <label class="label-field">Jenis Aduan <span class="text-red-500">*</span></label>
                    <select name="jenis_aduan" required class="input-field">
                        <option value="">-- Pilih Jenis Aduan --</option>
                        <option value="Harga di atas HET">Harga Jual di atas HET (Harga Eceran Tertinggi)</option>
                        <option value="Gas Langka / Sulit Didapat">Gas LPG Langka / Sulit Didapat</option>
                        <option value="Pangkalan Menolak Menjual">Pangkalan Menolak Menjual ke Warga</option>
                        <option value="Penimbunan">Indikasi Penimbunan LPG</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="input-wrapper">
                    <label class="label-field">Detail Kejadian <span class="text-red-500">*</span></label>
                    <textarea name="isi_keluhan" required rows="4" class="input-field resize-none"
                              placeholder="Ceritakan kejadian secara detail: waktu, tempat, dan apa yang terjadi..."></textarea>
                </div>

                <div class="input-wrapper">
                    <label class="label-field">Foto Bukti (Maks 5MB) <span class="text-red-500">*</span></label>
                    <input type="file" name="foto_bukti" accept="image/*" required
                           class="input-field p-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand/10 file:text-brand cursor-pointer hover:file:bg-brand/20 file:transition">
                </div>

                <div class="input-wrapper">
                    <label class="label-field">Tentukan Titik Lokasi Kejadian <span class="text-red-500">*</span></label>
                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col">
                        <!-- Toolbar Peta -->
                        <div class="p-3 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row gap-3 items-center relative z-20">
                            <div class="relative flex-1 w-full">
                                <div class="flex shadow-sm rounded-lg overflow-hidden border border-slate-200">
                                    <div class="relative flex-1">
                                        <input type="text" id="map_search" autocomplete="off" placeholder="Cari nama jalan atau daerah..." class="w-full px-4 py-2 text-sm border-0 border-r border-slate-200 focus:ring-0 outline-none text-slate-700">
                                    </div>
                                    <button type="button" onclick="searchLocation()" class="bg-brand text-white px-4 py-2 text-xs font-semibold hover:bg-brand-dark transition-colors border-0">Cari</button>
                                </div>
                                <!-- Autocomplete Dropdown -->
                                <ul id="search_suggestions" class="absolute z-50 w-full bg-white border border-slate-200 rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto divide-y divide-slate-100"></ul>
                            </div>
                            <button type="button" onclick="getGPS()" class="shrink-0 w-full sm:w-auto text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 px-4 py-2 rounded-lg transition flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya
                            </button>
                        </div>
                        <!-- Container Peta -->
                        <div id="formMap" class="w-full h-64 sm:h-80 relative z-10" style="min-height: 250px;"></div>
                        <!-- Info Koordinat Terpilih -->
                        <div class="p-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-600 font-mono gap-2 relative z-20">
                            <div class="flex items-center gap-2">
                                <span class="bg-slate-200 px-2 py-1 rounded">Lat: <span id="display_lat">-</span></span>
                                <span class="bg-slate-200 px-2 py-1 rounded">Lng: <span id="display_lng">-</span></span>
                            </div>
                            <div class="text-brand font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-hand-pointer"></i> Geser pin untuk akurasi
                            </div>
                        </div>
                    </div>
                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="latitude" id="gps_lat">
                    <input type="hidden" name="longitude" id="gps_lng">
                </div>

                <div class="pt-6 mt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary w-full justify-center text-base py-3.5">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Laporan Pengaduan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<link href="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.js"></script>

<script>
    // Update Step Indicator
    function setStep(step) {
        [1,2,3].forEach(i => {
            const circle = document.getElementById('circle-' + i);
            if (!circle) return;
            circle.classList.remove('active', 'done');
            if (i < step) circle.classList.add('done'), circle.innerHTML = '✓';
            else if (i === step) circle.classList.add('active');
        });
        if (step > 1) document.getElementById('line-1-2')?.classList.add('done');
        if (step > 2) document.getElementById('line-2-3')?.classList.add('done');
    }
    setStep(1);

    // === OTP Request ===
    async function requestOtp() {
        const email = document.getElementById('email_input').value.trim();
        if (!email || !email.includes('@')) {
            alert('Masukkan alamat Email yang valid');
            return;
        }
        const btn = document.getElementById('btn-request-otp');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim OTP...';

        try {
            const res = await fetch('{{ route("public.keluhan.otp-request") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('wa-input-section').classList.add('hidden');
                document.getElementById('otp-input-section').classList.remove('hidden');
                document.getElementById('otp-sent-msg').textContent = data.mode === 'simulation'
                    ? 'MODE DEMO: Kode OTP tampil di bawah ini.'
                    : 'OTP berhasil dikirim ke Email Anda!';

                if (data.mode === 'simulation' && data.otp_demo) {
                    document.getElementById('demo-alert').classList.remove('hidden');
                    document.getElementById('demo-otp-code').textContent = data.otp_demo;
                }
            } else {
                alert(data.message || 'Gagal mengirim OTP. Coba lagi.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-envelope"></i> Kirim Kode OTP ke Email';
            }
        } catch(e) {
            alert('Terjadi kesalahan koneksi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-envelope"></i> Kirim Kode OTP ke Email';
        }
    }

    // === OTP Verify ===
    async function verifyOtp() {
        const email = document.getElementById('email_input').value.trim();
        const otp = document.getElementById('otp_code').value.trim();
        if (!otp || otp.length !== 6) { alert('Masukkan 6 digit kode OTP'); return; }

        const btn = document.querySelector('#otp-input-section .btn-primary');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memverifikasi...';

        try {
            const res = await fetch('{{ route("public.keluhan.otp-verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email, otp_code: otp })
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('step-otp').classList.add('hidden');
                document.getElementById('step-form').classList.remove('hidden');
                document.getElementById('hidden_email').value = email;
                document.getElementById('verified-wa-display').textContent = email;
                setStep(2);
                
                // Initialize map when step 2 is visible
                setTimeout(() => {
                    if (!map) initMap();
                    else map.resize();
                }, 200);
            } else {
                alert(data.message || 'OTP tidak valid!');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> Verifikasi OTP';
            }
        } catch(e) {
            alert('Terjadi kesalahan koneksi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> Verifikasi OTP';
        }
    }

    function resetOtp() {
        document.getElementById('otp-input-section').classList.add('hidden');
        document.getElementById('demo-alert').classList.add('hidden');
        document.getElementById('wa-input-section').classList.remove('hidden');
        document.getElementById('email_input').value = '';
        document.getElementById('otp_code').value = '';
        const btn = document.getElementById('btn-request-otp');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-envelope"></i> Kirim Kode OTP ke Email';
    }

    // === Map Initialization ===
    let map, marker;
    const defaultCenter = [107.9087, -7.2279]; // Pusat Garut

    function initMap() {
        map = new maplibregl.Map({
            container: 'formMap',
            style: 'https://tiles.openfreemap.org/styles/liberty', // 3D support style
            center: defaultCenter,
            zoom: 12,
            pitch: 55, // 3D View
            bearing: -15,
            antialias: true,
            maxPitch: 85,
            attributionControl: false
        });

        map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'bottom-right');

        // Create draggable marker
        marker = new maplibregl.Marker({ color: '#0B5240', draggable: true })
            .setLngLat(defaultCenter)
            .addTo(map);

        // Update inputs when marker is dragged
        marker.on('dragend', updateCoordinates);

        // Allow clicking on map to move marker
        map.on('click', (e) => {
            marker.setLngLat(e.lngLat);
            updateCoordinates();
        });
        
        // Perbaiki map resize saat pertama kali dirender
        setTimeout(() => map.resize(), 100);
    }

    function updateCoordinates() {
        const lngLat = marker.getLngLat();
        const lat = lngLat.lat.toFixed(7);
        const lng = lngLat.lng.toFixed(7);
        
        // Update hidden inputs
        document.getElementById('gps_lat').value = lat;
        document.getElementById('gps_lng').value = lng;
        
        // Update display
        document.getElementById('display_lat').textContent = lat;
        document.getElementById('display_lng').textContent = lng;
    }

    // Geocoding Search using Nominatim OpenStreetMap
    async function searchLocation() {
        const query = document.getElementById('map_search').value.trim();
        if (!query) return;

        const btn = document.querySelector('button[onclick="searchLocation()"]');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled = true;

        try {
            // Append Garut to make search localized
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Garut')}&limit=1`;
            const response = await fetch(url);
            const data = await response.json();

            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                
                map.flyTo({ center: [lon, lat], zoom: 15 });
                marker.setLngLat([lon, lat]);
                updateCoordinates();
            } else {
                alert('Lokasi tidak ditemukan. Coba gunakan kata kunci yang berbeda.');
            }
        } catch (error) {
            console.error('Geocoding error:', error);
            alert('Gagal mencari lokasi. Periksa koneksi internet Anda.');
        } finally {
            btn.innerHTML = 'Cari';
            btn.disabled = false;
        }
    }

    // Trigger search on Enter key
    const searchInput = document.getElementById('map_search');
    searchInput?.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchLocation();
        }
    });

    // === Autocomplete Logic ===
    let searchTimeout;
    const suggestionsBox = document.getElementById('search_suggestions');

    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 3) {
            suggestionsBox.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                // Fetch up to 5 results
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Garut')}&limit=5`;
                const response = await fetch(url);
                const data = await response.json();
                
                suggestionsBox.innerHTML = '';
                if (data && data.length > 0) {
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 cursor-pointer flex items-start gap-2 transition-colors';
                        
                        // Parse address
                        const nameParts = item.display_name.split(',');
                        const mainName = nameParts[0];
                        const subName = nameParts.slice(1,3).join(',').trim();
                        
                        li.innerHTML = `
                            <i class="fa-solid fa-location-dot text-slate-400 mt-0.5"></i> 
                            <div class="flex-1">
                                <div class="font-semibold text-slate-800">${mainName}</div>
                                <div class="text-[10px] text-slate-500">${subName}</div>
                            </div>
                        `;
                        
                        li.addEventListener('click', () => {
                            const lat = parseFloat(item.lat);
                            const lon = parseFloat(item.lon);
                            
                            map.flyTo({ center: [lon, lat], zoom: 15 });
                            marker.setLngLat([lon, lat]);
                            updateCoordinates();
                            
                            searchInput.value = mainName;
                            suggestionsBox.classList.add('hidden');
                        });
                        
                        suggestionsBox.appendChild(li);
                    });
                    suggestionsBox.classList.remove('hidden');
                } else {
                    suggestionsBox.innerHTML = '<li class="px-4 py-3 text-sm text-slate-500 italic text-center">Lokasi tidak ditemukan</li>';
                    suggestionsBox.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Autocomplete error:', error);
            }
        }, 400); // 400ms debounce
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (searchInput && suggestionsBox) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('hidden');
            }
        }
    });

    function getGPS() {
        if (!navigator.geolocation) { alert('Browser tidak mendukung GPS.'); return; }
        
        const btn = document.querySelector('button[onclick="getGPS()"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencari Lokasi...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            
            map.flyTo({ center: [lng, lat], zoom: 15 });
            marker.setLngLat([lng, lat]);
            updateCoordinates();
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, function(err) {
            alert('Gagal mendapatkan lokasi: ' + err.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Dynamic Dropdown Pangkalan & Desa by Kecamatan
    document.getElementById('kecamatan_id')?.addEventListener('change', function() {
        const kecamatanId = this.value;
        const pangkalanSelect = document.getElementById('pangkalan_id');
        const desaSelect = document.getElementById('desa_id');
        
        // Reset options
        pangkalanSelect.innerHTML = '<option value="">-- Tidak Tahu --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
        
        if (!kecamatanId) return;
        
        // Fetch Pangkalans
        fetch(`{{ url('/keluhan/get-pangkalans') }}/${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(pangkalan => {
                    const option = document.createElement('option');
                    option.value = pangkalan.id;
                    option.textContent = pangkalan.name;
                    pangkalanSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching pangkalans:', error));

        // Fetch Desas
        fetch(`{{ url('/keluhan/get-desas') }}/${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(desa => {
                    const option = document.createElement('option');
                    option.value = desa.id;
                    option.textContent = desa.nama_desa;
                    desaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching desas:', error));
    });
</script>
@endsection
