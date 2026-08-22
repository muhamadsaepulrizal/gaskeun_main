@extends('layouts.app')
@section('title', 'Data Rumah Tangga Sasaran')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.rts.create') }}" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5 shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah Data
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nomor KK</th>
                    <th>Kriteria Bantuan</th>
                    <th>Status Penerima</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td >{{ $item->kk->nomor_kk ?? '-' }}</td>
                    <td >{{ $item->kriteria_bantuan }}</td>
                    <td>
                        @php
                            $cls = match($item->status_penerima) {
                                'Layak', 'Menerima' => 'badge-active',
                                'Tidak Layak' => 'badge-danger',
                                default => 'badge-pending'
                            };
                        @endphp
                        <span class="{{ $cls }}">{{ $item->status_penerima }}</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('disperindag.rts.edit', $item->id) }}" class="btn-edit" ><i class="fa-solid fa-pen-to-square mr-1"></i> Edit</a>
                            <form action="{{ route('disperindag.rts.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data RTS ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" ><i class="fa-solid fa-trash-can mr-1"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-12" >Belum ada data RTS.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">{{ $items->links() }}</div>
    @endif
</div>
@endsection