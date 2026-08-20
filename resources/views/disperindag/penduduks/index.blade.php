@extends('layouts.app')
@section('title', 'Data Penduduk')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.penduduks.create') }}" class="btn-primary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah Data
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Nomor KK</th>
                    <th>JK</th>
                    <th>Tgl Lahir</th>
                    <th>Pekerjaan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:0.8125rem; color:#64748B;">{{ $item->nik }}</td>
                    <td class="font-medium" style="color:#CBD5E1;">{{ $item->nama_lengkap }}</td>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:0.75rem; color:#64748B;">{{ $item->kk->nomor_kk ?? '-' }}</td>
                    <td><span class="{{ $item->jenis_kelamin == 'Laki-laki' ? 'badge-info' : 'badge-pending' }}">{{ substr($item->jenis_kelamin, 0, 1) }}</span></td>
                    <td style="color:#64748B; font-size:0.8125rem;">{{ $item->tanggal_lahir }}</td>
                    <td style="color:#94A3B8;">{{ $item->pekerjaan }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('disperindag.penduduks.edit', $item->id) }}" class="btn-edit" style="padding:0.375rem 0.75rem;">Edit</a>
                            <form action="{{ route('disperindag.penduduks.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data {{ $item->nama_lengkap }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding:0.375rem 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12" style="color:#334155;">Belum ada data penduduk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-6 py-4" style="border-top:1px solid rgba(255,255,255,0.05);">{{ $items->links() }}</div>
    @endif
</div>
@endsection