@extends('layouts.app')
@section('title', 'Dashboard Eksekutif - Pengawas')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Eksekutif</h1>
            <p class="text-sm text-slate-500 mt-1">Ringkasan kebijakan strategis dan pantauan makro distribusi LPG bersubsidi.</p>
        </div>
        <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 text-sm font-semibold text-slate-600 shadow-sm">
            <i class="fa-regular fa-calendar mr-2 text-brand"></i> {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Status Keamanan Daerah (Berdasarkan Heatmap) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-xl border {{ $kecamatanKritis > 0 ? 'border-red-300 ring-2 ring-red-50' : 'border-slate-200' }} p-6 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kecamatan Status Kritis</p>
                <div class="w-8 h-8 rounded-full {{ $kecamatanKritis > 0 ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <h3 class="text-4xl font-black {{ $kecamatanKritis > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $kecamatanKritis }}</h3>
            <p class="text-xs mt-2 {{ $kecamatanKritis > 0 ? 'text-red-500 font-semibold' : 'text-slate-500' }}">
                @if($kecamatanKritis > 0)
                Membutuhkan Operasi Pasar Segera
                @else
                Tidak ada wilayah kritis
                @endif
            </p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kecamatan Status Rawan</p>
                <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i class="fa-solid fa-exclamation"></i>
                </div>
            </div>
            <h3 class="text-4xl font-black text-slate-800">{{ $kecamatanRawan }}</h3>
            <p class="text-xs mt-2 text-slate-500">Perlu pemantauan intensif Disperindag</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Indeks Kepuasan & Keluhan</p>
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
            </div>
            <h3 class="text-4xl font-black text-slate-800">{{ $rasioPenyelesaian }}<span class="text-xl text-slate-400 font-medium">%</span></h3>
            <p class="text-xs mt-2 text-slate-500">{{ $keluhanSelesai }} dari {{ $totalKeluhan }} keluhan terselesaikan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ranking Wilayah Kritis -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-brand"></i> Prioritas Intervensi Wilayah
                </h3>
            </div>
            <div class="p-0 flex-1">
                @if($rankingKritis->isEmpty())
                <div class="p-10 text-center text-slate-500">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="font-bold text-slate-700">Situasi Terkendali</p>
                    <p class="text-sm mt-1">Tidak ada wilayah dengan lonjakan keluhan atau kelangkaan signifikan saat ini.</p>
                </div>
                @else
                <ul class="divide-y divide-slate-100">
                    @foreach($rankingKritis as $index => $kritis)
                    <li class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 font-bold flex items-center justify-center text-xs border border-red-200">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800">Kec. {{ $kritis->kecamatan->nama ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">Prioritas Tinggi (Skor: {{ $kritis->skor_heatmap }})</div>
                            </div>
                        </div>
                        <a href="{{ route('public.peta') }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded transition-colors">Lihat Peta</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>

        <!-- Serapan Subsidi Tepat Sasaran -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-brand"></i> Serapan Subsidi (Bulan Ini)
                </h3>
            </div>
            <div class="p-0 flex-1">
                @if($penyaluranSubsidi->isEmpty())
                <div class="p-10 text-center text-slate-500">
                    <p class="text-sm">Belum ada data penyaluran dari pangkalan untuk bulan ini.</p>
                </div>
                @else
                <ul class="divide-y divide-slate-100">
                    @foreach($penyaluranSubsidi as $subsidi)
                    <li class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @php
                                $icon = match($subsidi->kategori_konsumen) {
                                    'Rumah Tangga' => 'fa-house text-blue-500 bg-blue-50',
                                    'Usaha Mikro' => 'fa-store text-emerald-500 bg-emerald-50',
                                    'Petani' => 'fa-wheat-awn text-amber-500 bg-amber-50',
                                    'Nelayan' => 'fa-fish text-cyan-500 bg-cyan-50',
                                    default => 'fa-circle-dot text-slate-500 bg-slate-50'
                                };
                            @endphp
                            <div class="w-10 h-10 rounded-lg {{ $icon }} flex items-center justify-center">
                                <i class="fa-solid {{ explode(' ', $icon)[0] }}"></i>
                            </div>
                            <div class="font-bold text-slate-700">{{ $subsidi->kategori_konsumen }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-black text-lg text-slate-800">{{ number_format($subsidi->total) }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tabung</div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
