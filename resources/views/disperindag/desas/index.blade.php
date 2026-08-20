@extends('layouts.app')
@section('title', 'Data Desa / Kelurahan')

@section('content')


<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.desas.create') }}" class="btn-primary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah Data
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kecamatan</th>
                    <th>Nama Desa / Kelurahan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="color:#64748B;">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                    <td class="font-medium" style="color:#CBD5E1;">{{ $item->nama_desa }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('disperindag.desas.edit', $item->id) }}" class="btn-edit" style="padding:0.375rem 0.75rem;">Edit</a>
                            <form action="{{ route('disperindag.desas.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus desa {{ $item->nama_desa }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding:0.375rem 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center py-12" style="color:#334155;">Belum ada data desa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-6 py-4" style="border-top:1px solid rgba(255,255,255,0.05);">{{ $items->links() }}</div>
    @endif
</div>
@endsection