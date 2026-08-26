@extends('layouts.app')
@section('title', 'Daftar Konsumen')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Daftar Konsumen</h1>
            <p class="text-sm text-slate-500 mt-1">Konsumen terdaftar di pangkalan Anda sebagai penerima LPG bersubsidi.</p>
        </div>
        <a href="{{ route('pangkalan.konsumen.create') }}" class="bg-brand hover:bg-brand-dark text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-user-plus"></i> Registrasi Konsumen
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $kategoris = ['Rumah Tangga', 'Usaha Mikro', 'Petani', 'Nelayan'];
            $icons     = ['fa-house', 'fa-store', 'fa-wheat-awn', 'fa-fish'];
            $colors    = ['blue', 'emerald', 'amber', 'cyan'];
        @endphp
        @foreach($kategoris as $i => $kat)
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs text-slate-500 mb-1">{{ $kat }}</div>
            <div class="text-2xl font-bold text-slate-800">
                {{ $konsumens->where('kategori', $kat)->count() }}
            </div>
        </div>
        @endforeach
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">No</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Nama Lengkap</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Kategori</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Kontak</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Pangkalan</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($konsumens as $k)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-semibold text-slate-800">{{ $k->nama_lengkap }}</td>
                        <td class="py-4 px-6">
                            @php
                                $bc = match($k->kategori) {
                                    'Rumah Tangga' => 'bg-blue-100 text-blue-700',
                                    'Usaha Mikro'  => 'bg-emerald-100 text-emerald-700',
                                    'Petani'       => 'bg-amber-100 text-amber-700',
                                    'Nelayan'      => 'bg-cyan-100 text-cyan-700',
                                    default        => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $bc }}">{{ $k->kategori }}</span>
                        </td>
                        <td class="py-4 px-6 text-slate-500">{{ $k->kontak ?? '-' }}</td>
                        <td class="py-4 px-6">
                            @if($k->is_anomali)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Anomali
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Normal</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-slate-500">
                            {{ $k->pangkalan->pangkalanProfile->nama_pangkalan ?? ($k->pangkalan->name ?? '-') }}
                        </td>
                        <td class="py-4 px-6 text-slate-500 text-xs">{{ $k->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-500">
                            <i class="fa-solid fa-users-slash text-3xl mb-3 opacity-30 block"></i>
                            Belum ada konsumen terdaftar. Mulai registrasi sekarang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($konsumens->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $konsumens->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
