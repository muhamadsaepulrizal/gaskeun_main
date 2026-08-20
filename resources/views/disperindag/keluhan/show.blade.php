@extends('layouts.app')
@section('title', 'Detail Keluhan #' . $keluhan->kode_tiket)

@section('content')
<div class="p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-mono mb-1 text-emerald-600 tracking-widest uppercase">// Disperindag — Tiket Keluhan</p>
            <h1 class="text-2xl font-bold text-slate-800">Detail Pengaduan</h1>
            <p class="text-sm text-slate-500 mt-1">Kode Tiket: <span class="font-mono font-bold text-slate-700">{{ $keluhan->kode_tiket ?? 'N/A' }}</span></p>
        </div>
        <a href="{{ route('disperindag.keluhan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Detail Keluhan --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-700">Informasi Laporan</h3>
                    @php
                        $statusColor = match($keluhan->status_keluhan) {
                            'Masuk'               => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'Diproses'            => 'bg-blue-100 text-blue-700 border-blue-200',
                            'Selesai'             => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'Ditolak'             => 'bg-red-100 text-red-700 border-red-200',
                            default               => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                        {{ $keluhan->status_keluhan }}
                    </span>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Nama Pelapor</p>
                            <p class="font-semibold text-slate-800">{{ $keluhan->nama_pelapor }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Email Pelapor</p>
                            <p class="font-semibold text-slate-800">{{ $keluhan->email_pelapor }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Kecamatan Kejadian</p>
                            <p class="font-semibold text-slate-800">{{ $keluhan->kecamatan->nama_kecamatan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Pangkalan Terkait</p>
                            <p class="font-semibold text-slate-800">{{ $keluhan->pangkalan->name ?? 'Tidak Diketahui' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Jenis Aduan</p>
                            <p class="font-semibold text-slate-800">{{ $keluhan->jenis_aduan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Tanggal Lapor</p>
                            <p class="font-semibold text-slate-800">{{ $keluhan->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Detail Kejadian</p>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $keluhan->isi_keluhan }}</div>
                    </div>

                    @if($keluhan->foto_bukti)
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Foto Bukti</p>
                        <a href="{{ asset('storage/' . $keluhan->foto_bukti) }}" target="_blank">
                            <img src="{{ asset('storage/' . $keluhan->foto_bukti) }}" alt="Foto Bukti"
                                 class="rounded-xl max-h-80 object-cover border border-slate-200 hover:opacity-90 transition cursor-zoom-in">
                        </a>
                    </div>
                    @endif

                    @if($keluhan->latitude && $keluhan->longitude)
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Koordinat GPS Pelapor</p>
                        <a href="https://www.google.com/maps?q={{ $keluhan->latitude }},{{ $keluhan->longitude }}" target="_blank"
                           class="inline-flex items-center gap-2 text-sm text-blue-600 hover:underline font-medium">
                            <i class="fa-solid fa-map-location-dot"></i>
                            {{ $keluhan->latitude }}, {{ $keluhan->longitude }} — Buka di Google Maps
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tindak Lanjut / Riwayat --}}
            @if($keluhan->tindak_lanjut || $keluhan->alasan_penolakan)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                    <h3 class="font-bold text-slate-700">Catatan Tindak Lanjut</h3>
                </div>
                <div class="p-6">
                    @if($keluhan->tindak_lanjut)
                        <div class="mb-4">
                            <p class="text-xs text-emerald-600 uppercase font-bold tracking-wider mb-2">Tindak Lanjut</p>
                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-sm text-slate-700 whitespace-pre-wrap">{{ $keluhan->tindak_lanjut }}</div>
                        </div>
                    @endif
                    @if($keluhan->alasan_penolakan)
                        <div>
                            <p class="text-xs text-red-500 uppercase font-bold tracking-wider mb-2">Alasan Penolakan</p>
                            <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-sm text-slate-700 whitespace-pre-wrap">{{ $keluhan->alasan_penolakan }}</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan: Panel Aksi --}}
        <div class="space-y-4">
            {{-- Update Status --}}
            @if(!in_array($keluhan->status_keluhan, ['Selesai', 'Ditolak']))
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 bg-slate-50/50 border-b border-slate-100">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-circle-nodes text-emerald-600"></i> Panel Aksi
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    {{-- Tombol Tindak Lanjuti (→ Diproses) --}}
                    @if($keluhan->status_keluhan === 'Masuk')
                    <form action="{{ route('disperindag.keluhan.update', $keluhan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status_keluhan" value="Diproses">
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Tindak Lanjut (Opsional)</label>
                            <textarea name="tindak_lanjut" rows="2" placeholder="Tuliskan catatan atau rencana penanganan..."
                                      class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-circle-play"></i> Tandai Sedang Diproses
                        </button>
                    </form>
                    @endif

                    {{-- Tombol Selesai --}}
                    @if($keluhan->status_keluhan === 'Diproses')
                    <form action="{{ route('disperindag.keluhan.selesai', $keluhan->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Penyelesaian</label>
                            <textarea name="tindak_lanjut" rows="3" placeholder="Jelaskan apa yang sudah dilakukan untuk menyelesaikan keluhan ini..." required
                                      class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 resize-none">{{ $keluhan->tindak_lanjut }}</textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Tandai Selesai
                        </button>
                    </form>
                    @endif

                    <hr class="border-slate-100 my-2">

                    {{-- Tombol Tolak (selalu tersedia jika belum selesai/ditolak) --}}
                    <div x-data="{ openTolak: false }">
                        <button @click="openTolak = !openTolak" type="button"
                                class="w-full px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold transition border border-red-200 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban"></i> Tolak Laporan
                        </button>
                        <div x-show="openTolak" x-transition class="mt-3">
                            <form action="{{ route('disperindag.keluhan.tolak', $keluhan->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-xs font-semibold text-red-600 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                    <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan mengapa laporan ini ditolak (akan ditampilkan ke pelapor)..."
                                              class="w-full border border-red-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 resize-none bg-red-50"></textarea>
                                </div>
                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-ban"></i> Konfirmasi Penolakan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 text-center text-sm text-slate-500">
                <i class="fa-solid fa-lock-open text-2xl mb-2 text-slate-300 block"></i>
                Tiket ini sudah <strong class="{{ $keluhan->status_keluhan === 'Selesai' ? 'text-emerald-600' : 'text-red-500' }}">{{ $keluhan->status_keluhan }}</strong>
                dan tidak bisa diubah kembali.
            </div>
            @endif

            {{-- Info Metadata --}}
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 text-xs text-slate-500 space-y-2">
                <div class="flex justify-between"><span>Dibuat</span><span class="font-medium text-slate-700">{{ $keluhan->created_at->format('d M Y') }}</span></div>
                <div class="flex justify-between"><span>Diperbarui</span><span class="font-medium text-slate-700">{{ $keluhan->updated_at->format('d M Y') }}</span></div>
                @if($keluhan->verifikator)
                <div class="flex justify-between"><span>Ditangani oleh</span><span class="font-medium text-slate-700">{{ $keluhan->verifikator->name }}</span></div>
                @endif
                @if($keluhan->tanggal_respon_wa)
                <div class="flex justify-between"><span>Respon dikirim pada</span><span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($keluhan->tanggal_respon_wa)->format('d M Y') }}</span></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
