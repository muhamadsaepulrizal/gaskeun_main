@extends('layouts.app')
@section('title', 'Pangkalan Binaan')

@section('content')
<div class="p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pangkalan Binaan Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola pangkalan yang berada di bawah tanggung jawab Anda.</p>
        </div>
        <a href="{{ route('agen.pangkalan-binaan.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition-all hover:scale-105"
           style="background: linear-gradient(135deg, #0B5240, #14765C);">
            <i class="fa-solid fa-plus"></i> Tambah Pangkalan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-semibold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-store text-emerald-600"></i>
                Daftar Pangkalan ({{ $pangkalans->total() }})
            </h3>
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama pangkalan..."
                       class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm transition">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
        </div>

        @if($pangkalans->isEmpty())
            <div class="p-16 text-center text-slate-500">
                <i class="fa-solid fa-store-slash text-4xl mb-4 text-slate-300"></i>
                <p class="font-semibold text-slate-600">Belum ada pangkalan binaan</p>
                <p class="text-sm mt-1">Klik "Tambah Pangkalan" untuk menambahkan pangkalan pertama Anda.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3 text-left font-semibold">Nama Pangkalan</th>
                        <th class="px-6 py-3 text-left font-semibold">Alamat</th>
                        <th class="px-6 py-3 text-left font-semibold">Kontak</th>
                        <th class="px-6 py-3 text-left font-semibold">Kuota Bulanan</th>
                        <th class="px-6 py-3 text-left font-semibold">Username Login</th>
                        <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pangkalans as $pangkalan)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $pangkalan->nama_pangkalan }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $pangkalan->no_registrasi ?? 'Belum ada no. registrasi' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $pangkalan->alamat }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $pangkalan->kontak }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                {{ number_format($pangkalan->kuota_bulanan) }} Tabung
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">
                                {{ $pangkalan->user->username ?? '-' }}
                            </code>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('agen.pangkalan-binaan.edit', $pangkalan->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold transition">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $pangkalans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
