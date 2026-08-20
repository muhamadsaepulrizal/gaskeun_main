@extends('layouts.app')
@section('title', 'Dashboard Disperindag')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Disperindag</h1>
        <p class="text-sm text-slate-500 mt-1">Pantau jaringan distribusi LPG bersubsidi dan tanggapi keluhan masyarakat.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full"></div>
            <div class="relative flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jaringan Distribusi</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-slate-800"><span class="counter" data-target="{{ $totalPangkalan }}">0</span></h3>
                        <span class="text-sm font-semibold text-slate-500">Pangkalan</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
            </div>
            <div class="relative text-xs text-slate-500 flex justify-between border-t border-slate-100 pt-3">
                <span>Tersebar di {{ $totalKecamatan }} Kecamatan</span>
                <span class="font-semibold text-slate-700">{{ $totalAgen }} Agen Pembina</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full"></div>
            <div class="relative flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Keluhan Masuk</p>
                    <h3 class="text-3xl font-bold text-slate-800"><span class="counter" data-target="{{ $keluhanBaru }}">0</span></h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-inbox"></i>
                </div>
            </div>
            <div class="relative text-xs text-amber-600 flex items-center gap-1.5 border-t border-slate-100 pt-3 font-semibold">
                @if($keluhanBaru > 0)
                <i class="fa-solid fa-bell animate-bounce"></i> Perlu Segera Ditindaklanjuti
                @else
                <i class="fa-solid fa-check-double"></i> Semua Keluhan Tertangani
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full"></div>
            <div class="relative flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Keluhan Selesai</p>
                    <h3 class="text-3xl font-bold text-slate-800"><span class="counter" data-target="{{ $keluhanSelesai }}">0</span> <span class="text-lg text-slate-500 font-normal">/ <span class="counter" data-target="{{ $totalKeluhan }}">0</span></span></h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>
            @php
                $persentase = $totalKeluhan > 0 ? round(($keluhanSelesai / $totalKeluhan) * 100) : 0;
            @endphp
            <div class="relative mt-2">
                <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $persentase }}%"></div>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">{{ $persentase }}% Tingkat Penyelesaian</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Peringatan Wilayah Rawan (Heatmap Snapshot) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-fire-flame-curved text-red-500"></i> Pantauan Wilayah Rawan
                </h3>
                <a href="{{ route('public.peta') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">Buka Peta GIS &rarr;</a>
            </div>
            <div class="p-0 flex-1">
                @if($heatmapRawan->isEmpty())
                <div class="p-8 text-center text-slate-500">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <p class="font-semibold text-slate-700">Kondisi Aman</p>
                    <p class="text-xs mt-1">Tidak ada wilayah dengan status rawan atau kritis hari ini.</p>
                </div>
                @else
                <ul class="divide-y divide-slate-100">
                    @foreach($heatmapRawan as $h)
                    <li class="p-4 hover:bg-slate-50 transition-colors flex justify-between items-center">
                        <div>
                            <div class="font-bold text-slate-800">Kec. {{ $h->kecamatan->nama ?? 'Tidak Diketahui' }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">Terakhir diupdate: {{ $h->created_at->diffForHumans() }}</div>
                        </div>
                        <div>
                            @if($h->level_risiko === 'Kritis')
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded border border-red-200 shadow-sm">KRITIS (Skor: {{ $h->skor_heatmap }})</span>
                            @else
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded border border-orange-200">RAWAN (Skor: {{ $h->skor_heatmap }})</span>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>

        <!-- Keluhan Terbaru -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-brand"></i> Keluhan Terbaru
                </h3>
                <a href="{{ route('disperindag.keluhan.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">Lihat Semua &rarr;</a>
            </div>
            <div class="p-0 flex-1">
                @if($keluhanTerbaru->isEmpty())
                <div class="p-8 text-center text-slate-500">
                    <p class="text-sm">Belum ada keluhan masyarakat yang masuk.</p>
                </div>
                @else
                <ul class="divide-y divide-slate-100">
                    @foreach($keluhanTerbaru as $k)
                    <li class="p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $k->nama_pelapor ?? ($k->user->name ?? 'Anonim') }}</span>
                            </div>
                            @php
                                $bCls = match($k->status_keluhan) {
                                    'Masuk' => 'bg-amber-100 text-amber-700',
                                    'Diproses' => 'bg-blue-100 text-blue-700',
                                    'Selesai' => 'bg-emerald-100 text-emerald-700',
                                    'Ditolak' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-700'
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest {{ $bCls }}">{{ $k->status_keluhan }}</span>
                        </div>
                        <p class="text-sm text-slate-700 line-clamp-2 mb-3">{{ $k->isi_keluhan }}</p>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-mono"><i class="fa-regular fa-clock"></i> {{ $k->created_at->format('d M Y, H:i') }}</span>
                            <a href="{{ route('disperindag.keluhan.show', $k->id) }}" class="text-brand font-semibold hover:underline">Tindaklanjuti</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
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
