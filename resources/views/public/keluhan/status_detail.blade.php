@extends('layouts.public')
@section('title', 'Detail Status Laporan')

@section('header')
    
@endsection

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('public.keluhan.status') }}" class="btn-secondary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

    <div class="card p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <h3 class="text-xl font-bold text-emerald-400">Status Saat Ini</h3>
            @php
                $statusColor = 'bg-slate-500';
                if($keluhan->status_keluhan == 'Masuk') $statusColor = 'bg-yellow-500';
                if($keluhan->status_keluhan == 'Diproses') $statusColor = 'bg-blue-500';
                if($keluhan->status_keluhan == 'Selesai') $statusColor = 'bg-emerald-500';
                if($keluhan->status_keluhan == 'Ditolak') $statusColor = 'bg-red-500';
            @endphp
            <span class="px-4 py-2 rounded-full text-white text-sm font-bold {{ $statusColor }}">
                {{ $keluhan->status_keluhan }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-slate-400">Tanggal Pengaduan</p>
                <p class="font-semibold text-white">{{ $keluhan->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Kecamatan Lokasi</p>
                <p class="font-semibold text-white">{{ $keluhan->kecamatan->nama_kecamatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Nama Pangkalan (Terkait)</p>
                <p class="font-semibold text-white">{{ $keluhan->pangkalan->name ?? 'Tidak Diketahui' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Jenis Aduan</p>
                <p class="font-semibold text-white">{{ $keluhan->jenis_aduan ?? '-' }}</p>
            </div>
        </div>

        <div class="border-t border-white/10 pt-4">
            <p class="text-sm text-slate-400 mb-2">Detail Kejadian</p>
            <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-700/50">
                <p class="text-white whitespace-pre-wrap">{{ $keluhan->isi_keluhan }}</p>
            </div>
        </div>

        @if($keluhan->foto_bukti)
        <div class="border-t border-white/10 pt-4">
            <p class="text-sm text-slate-400 mb-2">Foto Bukti</p>
            <img src="{{ asset('storage/' . $keluhan->foto_bukti) }}" alt="Foto Bukti" class="rounded-lg max-h-64 object-cover border border-slate-700/50">
        </div>
        @endif

        @if($keluhan->tindak_lanjut)
        <div class="border-t border-emerald-500/20 pt-4 mt-6">
            <p class="text-sm text-emerald-400 mb-2 font-bold flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tindak Lanjut Disperindag
            </p>
            <div class="bg-emerald-900/20 p-4 rounded-lg border border-emerald-500/30">
                <p class="text-emerald-100 whitespace-pre-wrap">{{ $keluhan->tindak_lanjut }}</p>
            </div>
        </div>
        @endif

        @if($keluhan->alasan_penolakan)
        <div class="border-t border-red-500/20 pt-4 mt-6">
            <p class="text-sm text-red-400 mb-2 font-bold flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Alasan Penolakan
            </p>
            <div class="bg-red-900/20 p-4 rounded-lg border border-red-500/30">
                <p class="text-red-100 whitespace-pre-wrap">{{ $keluhan->alasan_penolakan }}</p>
            </div>
        </div>
        @endif

    </div>
@endsection
