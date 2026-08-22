@extends('layouts.app')
@section('title', 'Data Kartu Keluarga')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.kks.create') }}" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5 shrink-0">
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
                    <th>Desa / Kelurahan</th>
                    <th>Alamat</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:0.8125rem; color:#06B6D4;">{{ $item->nomor_kk }}</td>
                    <td >{{ $item->desa->nama_desa ?? '-' }}</td>
                    <td style="color:#64748B; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item->alamat_lengkap }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('disperindag.kks.edit', $item->id) }}" class="btn-edit" ><i class="fa-solid fa-pen-to-square mr-1"></i> Edit</a>
                            <form action="{{ route('disperindag.kks.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus KK ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" ><i class="fa-solid fa-trash-can mr-1"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-12" >Belum ada data kartu keluarga.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">{{ $items->links() }}</div>
    @endif
</div>
@endsection