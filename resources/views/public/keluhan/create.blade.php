@extends('layouts.public')

@section('title', 'Ajukan Pengaduan LPG — GASKEUN')

@section('page-title', 'Ajukan Pengaduan LPG')

@section('page-subtitle', 'Sampaikan keluhan Anda terkait distribusi LPG 3kg bersubsidi di Garut')

@section('content')

    <!-- Success Ticket -->
    @if(session('success_ticket'))
    <div class="card p-8 text-center mb-6 border border-emerald-200 bg-emerald-50">
        <div class="text-5xl mb-4">✅</div>
        <h2 class="text-2xl font-bold text-emerald-700 mb-2">Laporan Berhasil Dikirim!</h2>
        <p class="text-slate-600 mb-6 text-sm">Simpan kode tiket berikut untuk melacak status laporan Anda:</p>
        <div class="inline-block bg-white border-2 border-emerald-200 rounded-xl px-8 py-4 mb-6 shadow-sm">
            <p class="text-3xl font-mono font-bold tracking-widest text-emerald-600">{{ session('success_ticket') }}</p>
        </div>
        <div class="p-4 rounded-xl mb-6 bg-yellow-50 border border-yellow-200 text-left">
            <p class="text-yellow-800 text-sm font-bold flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> PENTING: Screenshot atau catat kode tiket di atas!</p>
            <p class="text-yellow-700 text-xs mt-1 ml-6">Kode ini diperlukan untuk mengecek status laporan Anda.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('public.keluhan.status') }}" class="btn-primary">
                <i class="fa-solid fa-search"></i> Cek Status Laporan
            </a>
            <a href="{{ route('home') }}" class="btn-secondary">Kembali ke Beranda</a>
        </div>
    </div>
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
                <button type="button" onclick="resetOtp()" class="btn-secondary">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="input-wrapper">
                        <label class="label-field">Foto Bukti (Maks 5MB, Opsional)</label>
                        <input type="file" name="foto_bukti" accept="image/*"
                               class="input-field p-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand/10 file:text-brand cursor-pointer hover:file:bg-brand/20 file:transition">
                    </div>
                    <div class="input-wrapper">
                        <label class="label-field">Koordinat GPS Lokasi</label>
                        <div class="flex gap-2">
                            <input type="text" name="latitude" id="gps_lat" class="input-field font-mono text-xs bg-slate-50" placeholder="Latitude" readonly>
                            <input type="text" name="longitude" id="gps_lng" class="input-field font-mono text-xs bg-slate-50" placeholder="Longitude" readonly>
                        </div>
                        <button type="button" onclick="getGPS()" class="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya Sekarang
                        </button>
                    </div>
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

    function getGPS() {
        if (!navigator.geolocation) { alert('Browser tidak mendukung GPS.'); return; }
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('gps_lat').value = pos.coords.latitude.toFixed(7);
            document.getElementById('gps_lng').value = pos.coords.longitude.toFixed(7);
        }, function(err) {
            alert('Gagal mendapatkan lokasi: ' + err.message);
        });
    }

    // Dynamic Dropdown Pangkalan by Kecamatan
    document.getElementById('kecamatan_id')?.addEventListener('change', function() {
        const kecamatanId = this.value;
        const pangkalanSelect = document.getElementById('pangkalan_id');
        
        // Reset pangkalan options
        pangkalanSelect.innerHTML = '<option value="">-- Tidak Tahu --</option>';
        
        if (!kecamatanId) return;
        
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
    });
</script>
@endsection
