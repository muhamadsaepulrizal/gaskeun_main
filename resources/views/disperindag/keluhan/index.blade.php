@extends('layouts.app')
@section('title', 'Keluhan Masyarakat')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card overflow-hidden bg-white shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="data-table w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelapor & Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Keluhan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($keluhans as $k)
                    <tr class="align-top hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-bold text-sm text-slate-800">{{ $k->nama_pelapor ?? ($k->user->name ?? 'Publik Anonim') }}</p>
                            <p class="text-xs mt-1 text-slate-500 font-mono">{{ $k->created_at->format('d M Y · H:i') }}</p>
                            @if($k->latitude && $k->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $k->latitude }},{{ $k->longitude }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                <i class="fa-solid fa-map-location-dot"></i> Lihat Lokasi
                            </a>
                            @endif
                        </td>
                        <td class="px-6 py-5 max-w-xs">
                            <p class="text-sm leading-relaxed text-slate-600 line-clamp-3">{{ $k->isi_keluhan }}</p>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $cls = match($k->status_keluhan) {
                                    'Masuk'    => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                    'Diproses' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                    'Selesai'  => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                    'Ditolak'  => 'bg-red-100 text-red-700 border border-red-200',
                                    default    => 'bg-slate-100 text-slate-700 border border-slate-200'
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $cls }}">{{ strtoupper($k->status_keluhan) }}</span>
                            @if($k->tindak_lanjut)
                                <p class="text-xs mt-3 leading-relaxed text-slate-500 line-clamp-2 max-w-[200px] border-t border-slate-100 pt-2"><span class="font-semibold text-slate-700">Tindak Lanjut:</span> {{ $k->tindak_lanjut }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('disperindag.keluhan.show', $k->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-brand hover:bg-[#0B5240] transition rounded-lg shadow-sm gap-2">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Tindak Lanjuti
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-slate-500">
                            <i class="fa-solid fa-inbox text-3xl mb-3 text-slate-300 block"></i>
                            Belum ada laporan keluhan masyarakat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($keluhans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $keluhans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
