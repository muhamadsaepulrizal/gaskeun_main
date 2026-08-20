@extends('layouts.app')
@section('title', 'Dashboard Agen LPG')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Agen</h1>
            <p class="text-sm text-slate-500 mt-1">Selamat datang, <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span>. Kelola distribusi LPG bersubsidi ke pangkalan binaan Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full flex items-center gap-1.5 border border-emerald-200">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                Agen Aktif
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Pangkalan Mitra -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-orange-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pangkalan Binaan</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalPangkalan }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-link text-slate-400"></i> Pangkalan terdaftar
            </div>
        </div>

        <!-- Total Pengiriman -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Pengiriman</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ number_format($totalPengiriman) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-truck"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-truck-fast text-slate-400"></i> Akumulasi semua pengiriman
            </div>
        </div>

        <!-- Pengiriman Bulan Ini -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Dikirim Bulan Ini</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ number_format($pengirimanBulanIni) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-gas-pump"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-day text-slate-400"></i> Tabung disalurkan bulan ini
            </div>
        </div>

        <!-- Menunggu Konfirmasi Pangkalan -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Menunggu Pangkalan</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $pengirimanMenunggu }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-hourglass-half text-slate-400"></i> Pengiriman belum dikonfirmasi
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h3 class="font-bold text-lg text-slate-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:border-brand/30 transition-colors">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand/10 text-brand flex items-center justify-center shrink-0 text-xl">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">Catat Pengiriman LPG Baru</h4>
                    <p class="text-sm text-slate-500 mt-1 mb-4">Input data pengiriman ke pangkalan binaan secara manual atau melalui import file Excel (Partial Import).</p>
                    <div class="flex gap-2">
                        <a href="{{ route('agen.pengiriman.create') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white text-sm font-semibold rounded-lg transition-colors">
                            <i class="fa-solid fa-plus mr-1"></i> Input Baru
                        </a>
                        <a href="{{ route('agen.pengiriman.status') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors">
                            Lihat Status
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:border-brand/30 transition-colors">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 text-xl">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">Profil Keagenan</h4>
                    <p class="text-sm text-slate-500 mt-1 mb-4">Perbarui informasi kontak, alamat, dan nomor registrasi keagenan Anda di sistem.</p>
                    <a href="{{ route('agen.profil') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fa-solid fa-user-pen mr-1"></i> Update Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
