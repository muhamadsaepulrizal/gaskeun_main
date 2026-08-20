@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Log Aktivitas Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">Lacak dan pantau semua riwayat aktivitas dari semua pengguna dalam sistem GASKEUN.</p>
        </div>
        <form method="GET" action="{{ route('superadmin.logs.index') }}" class="shrink-0">
            <!-- Hidden inputs to retain current filters during PDF export -->
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="description" value="{{ request('description') }}">
            <input type="hidden" name="causer_id" value="{{ request('causer_id') }}">
            
            <button type="submit" name="export_pdf" value="1" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-file-pdf"></i> Ekspor ke PDF
            </button>
        </form>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('superadmin.logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Filter Tanggal Awal -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-600">
            </div>
            
            <!-- Filter Tanggal Akhir -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-600">
            </div>

            <!-- Filter Kata Kunci Aksi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kata Kunci (Aksi)</label>
                <input type="text" name="description" value="{{ request('description') }}" placeholder="Contoh: login, tambah" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-600">
            </div>

            <!-- Filter User -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Pengguna (Aktor)</label>
                <div class="flex gap-2">
                    <select name="causer_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-600">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                        @php
                            $displayName = $user->pangkalanProfile->nama_pangkalan ?? $user->profilAgen->nama_perusahaan ?? $user->name;
                        @endphp
                        <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>{{ $displayName }} ({{ $user->username }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="shrink-0 bg-brand hover:bg-brand-dark text-white rounded-lg px-4 py-2 text-sm font-semibold transition flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date', 'description', 'causer_id']))
                    <a href="{{ route('superadmin.logs.index') }}" class="shrink-0 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg px-3 py-2 text-sm font-semibold transition flex items-center justify-center">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Waktu</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Pengguna (Aktor)</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Aktivitas</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Event</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Modul / Objek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-slate-500 font-mono text-xs">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-4 px-6">
                            @if($log->causer)
                            @php
                                $causerDisplayName = $log->causer->pangkalanProfile->nama_pangkalan ?? $log->causer->profilAgen->nama_perusahaan ?? $log->causer->name;
                            @endphp
                            <div class="font-bold text-slate-800">{{ $causerDisplayName }}</div>
                            <div class="text-xs text-slate-500">{{ $log->causer->roles->first()->name ?? 'Tanpa Role' }}</div>
                            @else
                            <div class="font-bold text-slate-500 italic">Sistem (Auto)</div>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-slate-700 max-w-xs truncate" title="{{ $log->description }}">
                            {{ $log->description }}
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $badgeCls = match($log->event) {
                                    'created' => 'bg-emerald-100 text-emerald-700',
                                    'updated' => 'bg-blue-100 text-blue-700',
                                    'deleted' => 'bg-red-100 text-red-700',
                                    default   => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeCls }}">
                                {{ $log->event ?? 'log' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-500 font-mono text-xs">
                            {{ class_basename($log->subject_type ?? '') }}
                            @if($log->subject_id) <span class="text-slate-400">#{{ $log->subject_id }}</span> @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-500">
                            <i class="fa-solid fa-clock-rotate-left text-3xl mb-3 opacity-30 block"></i>
                            Tidak ada log aktivitas pada periode yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
