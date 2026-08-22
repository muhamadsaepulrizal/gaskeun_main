@extends('layouts.app')
@section('title', 'Data Penduduk')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.penduduks.create') }}" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5 shrink-0">
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
                    <td class="font-medium" >{{ $item->nama_lengkap }}</td>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:0.75rem; color:#64748B;">{{ $item->kk->nomor_kk ?? '-' }}</td>
                    <td><span class="{{ $item->jenis_kelamin == 'Laki-laki' ? 'badge-info' : 'badge-pending' }}">{{ substr($item->jenis_kelamin, 0, 1) }}</span></td>
                    <td >{{ $item->tanggal_lahir }}</td>
                    <td >{{ $item->pekerjaan }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('disperindag.penduduks.edit', $item->id) }}" class="btn-edit" ><i class="fa-solid fa-pen-to-square mr-1"></i> Edit</a>
                            <form action="{{ route('disperindag.penduduks.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data {{ $item->nama_lengkap }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" ><i class="fa-solid fa-trash-can mr-1"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12" >Belum ada data penduduk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">{{ $items->links() }}</div>
    @endif
</div>
@endsection