@extends('layouts.app')
@section('title', 'Data Rumah Tangga Sasaran')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.rts.create') }}" class="btn-primary shrink-0">
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
                    <td style="color:#06B6D4; font-family:'JetBrains Mono',monospace;">{{ $item->kk->nomor_kk ?? '-' }}</td>
                    <td style="color:#CBD5E1;">{{ $item->kriteria_bantuan }}</td>
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
                            <a href="{{ route('disperindag.rts.edit', $item->id) }}" class="btn-edit" style="padding:0.375rem 0.75rem;">Edit</a>
                            <form action="{{ route('disperindag.rts.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data RTS ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding:0.375rem 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-12" style="color:#334155;">Belum ada data RTS.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-6 py-4" style="border-top:1px solid rgba(255,255,255,0.05);">{{ $items->links() }}</div>
    @endif
</div>
@endsection