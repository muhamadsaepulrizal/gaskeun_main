@extends('layouts.app')
@section('title', 'Dashboard Super Admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 p-6 lg:p-8">

    <!-- 1. Grid 4 Card Statistik (Bagian Atas) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total User Aktif -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-sm font-medium text-slate-500">Total User Aktif</h3>
                <div class="p-2.5 bg-slate-100 rounded-lg text-slate-600">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-slate-900 mb-2"><span class="counter" data-target="{{ $totalAktif }}">0</span></p>
                <p class="text-xs font-semibold text-slate-500">Dari {{ number_format($totalUsers) }} total pengguna</p>
            </div>
        </div>

        <!-- Card 2: Total Agen Terdaftar -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-sm font-medium text-slate-500">Total Agen Terdaftar</h3>
                <div class="p-2.5 bg-slate-100 rounded-lg text-slate-600">
                    <i class="fa-solid fa-truck text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-slate-900 mb-2"><span class="counter" data-target="{{ $totalAgen }}">0</span></p>
            </div>
        </div>

        <!-- Card 3: Total Pangkalan Terdaftar -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-sm font-medium text-slate-500">Total Pangkalan Terdaftar</h3>
                <div class="p-2.5 bg-slate-100 rounded-lg text-slate-600">
                    <i class="fa-solid fa-store text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-slate-900 mb-2"><span class="counter" data-target="{{ $totalPangkalan }}">0</span></p>
            </div>
        </div>

        <!-- Card 4: Kecamatan Status Kritis (Highlight Merah) -->
        <div class="bg-red-50 rounded-xl p-6 border border-red-200 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-sm font-medium text-red-800">Kecamatan Status Kritis</h3>
                <div class="p-2.5 bg-white rounded-lg text-red-600 shadow-sm border border-red-100">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-red-700 mb-2"><span class="counter" data-target="{{ $kecamatanKritis }}">0</span></p>
            </div>
        </div>

    </div>

    <!-- 2. Grid Tengah (Tabel Ringkasan & Donut Chart) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        
        <!-- Tabel Ringkasan (Porsi 2 Kolom) -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm xl:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Ringkasan Status Wilayah</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="pb-3 font-semibold text-slate-500">Kecamatan</th>
                            <th class="pb-3 font-semibold text-slate-500">Jumlah Pangkalan</th>
                            <th class="pb-3 font-semibold text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($ringkasanWilayah as $wilayah)
                        <tr>
                            <td class="py-4 text-slate-800 font-medium">{{ $wilayah->kecamatan->nama_kecamatan ?? 'Tidak Diketahui' }}</td>
                            <td class="py-4 text-slate-600">{{ \App\Models\PangkalanProfile::where('kecamatan_id', $wilayah->kecamatan_id)->count() }}</td>
                            <td class="py-4">
                                @if($wilayah->level_risiko === 'Kritis')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Kritis</span>
                                @elseif($wilayah->level_risiko === 'Rawan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Waspada</span>
                                @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-slate-500 text-center text-sm">Tidak ada wilayah rawan atau kritis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Donut Chart Area (Porsi 1 Kolom) -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Distribusi Status</h3>
            
            <div class="flex-1 flex flex-col items-center justify-center">
                @php
                    $pctKritis = $totalKecamatan > 0 ? ($donutKritis / $totalKecamatan) * 100 : 0;
                    $pctWaspada = $totalKecamatan > 0 ? ($donutWaspada / $totalKecamatan) * 100 : 0;
                    $pctAman = $totalKecamatan > 0 ? ($donutAman / $totalKecamatan) * 100 : 100;
                    $stop1 = $pctKritis;
                    $stop2 = $pctKritis + $pctWaspada;
                @endphp
                <div class="relative w-44 h-44 rounded-full flex items-center justify-center mb-8" 
                     style="background: conic-gradient(#ef4444 0% {{ $stop1 }}%, #eab308 {{ $stop1 }}% {{ $stop2 }}%, #14b8a6 {{ $stop2 }}% 100%);">
                    <div class="w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                        <span class="text-4xl font-extrabold text-slate-900 leading-none mb-1">{{ $totalKecamatan }}</span>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Kecamatan</span>
                    </div>
                </div>

                <!-- Legend / Keterangan Chart -->
                <div class="flex gap-5 text-xs font-semibold text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span> Aman
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Waspada
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Kritis
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. Grid Bawah (Log Aktivitas) -->
    <div class="grid grid-cols-1 gap-6">
        
        <!-- Log Aktivitas Terbaru -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Log Aktivitas Terbaru</h3>
            
            <div class="space-y-6">
                @forelse($recentLogs as $log)
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-slate-100 rounded-full text-slate-500 shrink-0 border border-slate-200 w-10 h-10 flex items-center justify-center">
                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 mb-1 leading-snug">
                            {{ $log->description }}
                        </p>
                        <p class="text-xs text-slate-500 font-medium">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-slate-500 text-sm">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>


    </div>
</div>

@push('scripts')
<script>
    // Script Animasi Counter Angka
    const counters = document.querySelectorAll('.counter');
    const animationDuration = 2500; // Durasi animasi 2.5 detik

    const animateCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            let startTime = null;

            const step = (currentTime) => {
                if (!startTime) startTime = currentTime;
                const progress = Math.min((currentTime - startTime) / animationDuration, 1);
                
                const easeOutExpo = (x) => {
                    return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
                };
                
                const currentProgress = easeOutExpo(progress);
                const currentValue = Math.floor(currentProgress * target);

                counter.innerText = currentValue.toLocaleString('id-ID');

                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    counter.innerText = target.toLocaleString('id-ID');
                }
            };
            
            window.requestAnimationFrame(step);
        });
    }

    // Jalankan pas konten dimuat
    document.addEventListener("DOMContentLoaded", animateCounters);
</script>
@endpush
@endsection