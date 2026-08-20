@extends('layouts.app')
@section('title', 'Dashboard Pangkalan LPG')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Pangkalan</h1>
            <p class="text-sm text-slate-500 mt-1">Selamat datang, <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span>. Kelola penerimaan dan penyaluran LPG bersubsidi.</p>
        </div>
        <div class="flex items-center gap-3">
            @if($stokMenipis)
            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full border border-red-200 animate-pulse">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Peringatan: Stok Menipis!
            </span>
            @else
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">
                <i class="fa-solid fa-circle-check mr-1"></i> Stok Aman
            </span>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Stok Tersedia -->
        <div class="bg-white rounded-xl border {{ $stokMenipis ? 'border-red-300 ring-4 ring-red-50' : 'border-slate-200' }} p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 {{ $stokMenipis ? 'bg-red-50' : 'bg-emerald-50' }} rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Stok Gudang</p>
                    <h3 class="text-3xl font-bold {{ $stokMenipis ? 'text-red-600' : 'text-slate-800' }}">{{ number_format($stokTersedia) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg {{ $stokMenipis ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-gas-pump"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs {{ $stokMenipis ? 'text-red-500' : 'text-slate-500' }} flex items-center gap-1.5">
                <i class="fa-solid fa-boxes-stacked {{ $stokMenipis ? 'text-red-400' : 'text-slate-400' }}"></i> Tabung 3Kg Tersedia
            </div>
        </div>

        <!-- Total Penerimaan -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Diterima</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ number_format($totalPenerimaan) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-truck-ramp-box text-slate-400"></i> Akumulasi dari Agen
            </div>
        </div>

        <!-- Total Penyaluran -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Disalurkan</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ number_format($totalPenyaluran) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-users text-slate-400"></i> Disalurkan ke masyarakat
            </div>
        </div>

        <!-- Total Konsumen -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-cyan-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Konsumen Terdaftar</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ number_format($totalKonsumen) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-address-card"></i>
                </div>
            </div>
            <div class="relative mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-id-card-clip text-slate-400"></i> KTP/NIB divalidasi
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if($pengirimanMenunggu > 0)
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-4">
        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
            <i class="fa-solid fa-truck"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-blue-800">Ada Pengiriman Dalam Perjalanan!</h4>
            <p class="text-sm text-blue-700 mt-1">Terdapat <strong>{{ $pengirimanMenunggu }}</strong> pengiriman dari Agen yang sedang menuju ke pangkalan Anda. Segera cek dan konfirmasi penerimaan jika barang sudah sampai.</p>
        </div>
        <a href="{{ route('pangkalan.pengiriman.index') }}" class="shrink-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
            Cek Sekarang
        </a>
    </div>
    @endif

    <!-- Quick Actions -->
    <h3 class="font-bold text-lg text-slate-800 mb-4">Aksi Operasional</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:border-brand/30 transition-colors">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-xl">
                    <i class="fa-solid fa-clipboard-user"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">Salurkan LPG (Input Transaksi)</h4>
                    <p class="text-sm text-slate-500 mt-1 mb-4">Catat penjualan LPG ke konsumen yang sudah terdaftar. Sistem akan memotong stok secara otomatis.</p>
                    <a href="{{ route('pangkalan.penyaluran.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors inline-block">
                        <i class="fa-solid fa-plus mr-1"></i> Form Penyaluran
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:border-brand/30 transition-colors">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand/10 text-brand flex items-center justify-center shrink-0 text-xl">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">Daftarkan Konsumen Baru</h4>
                    <p class="text-sm text-slate-500 mt-1 mb-4">Registrasi warga (Rumah Tangga/UMKM/dll) dengan KTP/NIB agar bisa membeli LPG bersubsidi di pangkalan Anda.</p>
                    <div class="flex gap-2">
                        <a href="{{ route('pangkalan.konsumen.create') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white text-sm font-semibold rounded-lg transition-colors inline-block">
                            Registrasi Baru
                        </a>
                        <a href="{{ route('pangkalan.konsumen.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors inline-block">
                            Lihat Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
