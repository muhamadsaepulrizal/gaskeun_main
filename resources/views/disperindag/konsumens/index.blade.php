@extends('layouts.app')
@section('title', 'Data Konsumen')
@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Konsumen LPG 3 Kg</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar konsumen yang telah diregistrasi oleh pangkalan di seluruh kabupaten.</p>
    </div>
</div>

<div class="card overflow-hidden">
    <!-- Filter Section -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('disperindag.konsumens.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                           placeholder="Cari NIK atau Nama Lengkap...">
                </div>
            </div>
            <div class="sm:w-64">
                <select name="kategori" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition bg-white" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="Rumah Tangga" {{ request('kategori') == 'Rumah Tangga' ? 'selected' : '' }}>Rumah Tangga</option>
                    <option value="Usaha Mikro" {{ request('kategori') == 'Usaha Mikro' ? 'selected' : '' }}>Usaha Mikro</option>
                    <option value="Petani Sasaran" {{ request('kategori') == 'Petani Sasaran' ? 'selected' : '' }}>Petani Sasaran</option>
                    <option value="Nelayan Sasaran" {{ request('kategori') == 'Nelayan Sasaran' ? 'selected' : '' }}>Nelayan Sasaran</option>
                </select>
            </div>
            @if(request()->anyFilled(['search', 'kategori']))
                <a href="{{ route('disperindag.konsumens.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-sm font-semibold transition flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-xmark mr-2"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Kategori</th>
                    <th>Lokasi (Kec/Desa)</th>
                    <th>Alamat</th>
                    <th>Terdaftar Di</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsumens as $item)
                <tr>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:0.8125rem; color:#64748B;">{{ $item->nik ? substr($item->nik, 0, 6) . str_repeat('*', 6) . substr($item->nik, -4) : '-' }}</td>
                    <td class="font-medium text-slate-800">{{ $item->nama_lengkap }}</td>
                    <td>
                        @php
                            $badgeClass = match($item->kategori) {
                                'Rumah Tangga' => 'badge-success',
                                'Usaha Mikro' => 'badge-warning',
                                'Petani Sasaran' => 'badge-info',
                                'Nelayan Sasaran' => 'badge-info',
                                default => 'badge-pending'
                            };
                        @endphp
                        <span class="{{ $badgeClass }}">{{ $item->kategori }}</span>
                    </td>
                    <td>
                        <div class="text-sm">
                            <div class="font-medium text-slate-700">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $item->desa->nama_desa ?? '-' }}</div>
                        </div>
                    </td>
                    <td class="text-sm text-slate-600 truncate max-w-[150px]" title="{{ $item->alamat }}">
                        {{ $item->alamat ?? '-' }}
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-700">{{ $item->pangkalan->pangkalanProfile->nama_pangkalan ?? ($item->pangkalan->name ?? '-') }}</div>
                                <div class="text-[10px] text-slate-500">Pangkalan</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('disperindag.konsumens.edit', $item->id) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('disperindag.konsumens.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konsumen ini? Peringatan: Seluruh riwayat transaksi penyaluran (pembelian gas) untuk konsumen ini juga akan terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-slate-500"><i class="fa-solid fa-inbox text-3xl mb-3 text-slate-300 block"></i>Belum ada data konsumen yang terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($konsumens->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $konsumens->links() }}</div>
    @endif
</div>
@endsection
