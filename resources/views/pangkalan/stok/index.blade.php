@extends('layouts.app')
@section('title', 'Monitoring Stok LPG')

@section('content')


<div class="flex justify-end mb-6">
    <a href="{{ route('pangkalan.penyaluran.create') }}" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5 shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Salurkan LPG
    </a>
</div>

<!-- Stok Card -->
<div class="stat-card mb-6 max-w-sm" style="border-color:rgba(16,185,129,0.2);">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest mb-2" >Stok Tabung Tersedia</p>
            <p style="font-size:3.5rem; font-weight:800; line-height:1; letter-spacing:-0.04em; background:linear-gradient(135deg,#10B981,#06B6D4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
                {{ number_format($jumlahStok) }}
            </p>
            <p class="text-sm mt-2" >tabung LPG 3kg</p>
        </div>
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.2);">
            <svg class="w-8 h-8"  fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
    </div>
</div>

<!-- Riwayat Table -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.05);">
        <div class="w-2 h-2 rounded-full" ></div>
        <h3 class="font-semibold text-sm" >Riwayat Penyaluran</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Konsumen</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatPenyaluran as $i => $item)
                <tr>
                    <td >{{ $riwayatPenyaluran->firstItem() + $i }}</td>
                    <td><span class="badge-info">{{ $item->kategori_konsumen }}</span></td>
                    <td class="font-medium" >{{ $item->konsumen->nama_lengkap ?? '-' }}</td>
                    <td>
                        <span class="font-mono font-bold" >{{ $item->jumlah_tabung }}</span>
                        <span class="text-xs ml-1" >tabung</span>
                    </td>
                    <td >{{ $item->tanggal_penyaluran }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12" >
                        <svg class="w-8 h-8 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Belum ada riwayat penyaluran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riwayatPenyaluran->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $riwayatPenyaluran->links() }}
    </div>
    @endif
</div>
@endsection
