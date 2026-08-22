@extends('layouts.app')
@section('title', 'Status Pengiriman LPG')

@section('content')


<div class="flex justify-end mb-6">
    <a href="{{ route('agen.pengiriman.create') }}" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5 shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Input Pengiriman Baru
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pangkalan Tujuan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Info Koreksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengiriman as $i => $item)
                <tr>
                    <td >{{ $pengiriman->firstItem() + $i }}</td>
                    <td class="font-medium" >{{ $item->pangkalan ? ($item->pangkalan->name ?? $item->pangkalan->username) : 'Pangkalan Dihapus' }}</td>
                    <td>
                        <span class="font-mono font-bold" >{{ $item->jumlah_tabung }}</span>
                        <span class="text-xs ml-1" >tabung</span>
                    </td>
                    <td >{{ $item->tanggal_pengiriman }}</td>
                    <td>
                        @if($item->status == 'Menunggu')
                            <span class="badge-pending">Menunggu</span>
                        @elseif($item->status == 'Diterima')
                            <span class="badge-active">Diterima</span>
                        @else
                            <span class="badge-danger">Dikoreksi</span>
                        @endif
                    </td>
                    <td>
                        @if($item->koreksi)
                            <p class="text-xs" >Seharusnya: <strong>{{ $item->koreksi->jumlah_seharusnya }}</strong> tabung</p>
                            @if($item->koreksi->keterangan_koreksi)
                                <p class="text-xs mt-1" >{{ $item->koreksi->keterangan_koreksi }}</p>
                            @endif
                        @else
                            <span >—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12" >
                        <svg class="w-8 h-8 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        Belum ada data pengiriman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengiriman->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $pengiriman->links() }}
    </div>
    @endif
</div>
@endsection
