@extends('layouts.app')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kelola Data User</h1>
        <p class="text-sm text-slate-500 mt-1">Manajemen data pengguna dan pengaturan hak akses sistem GASKEUN.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3 flex items-center gap-3">
        <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Filter & Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Toolbar / Filters — Terhubung ke DB -->
        <form method="GET" action="{{ route('superadmin.users.index') }}">
        <div class="p-5 border-b border-slate-200 flex flex-col xl:flex-row xl:items-center justify-between gap-4 flex-wrap">
            <div class="flex flex-col sm:flex-row gap-3 flex-1 flex-wrap">
                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau username..."
                           class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand">
                </div>
                <!-- Role Filter -->
                <select name="role" onchange="this.form.submit()"
                        class="w-full sm:w-44 px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-600">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()"
                        class="w-full sm:w-40 px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-600">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('superadmin.users.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
                @endif
            </div>
            
            <div class="flex gap-2 flex-wrap">
                <button type="submit" class="shrink-0 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg px-4 py-2 text-sm font-semibold transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                <a href="{{ route('superadmin.users.create') }}" class="shrink-0 bg-brand hover:bg-brand-dark text-white rounded-lg px-4 py-2 text-sm font-semibold transition flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Tambah User Baru
                </a>
            </div>
        </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">No</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Nama</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Username</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Role</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 font-semibold text-slate-600 text-xs tracking-wider uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $user)
                    <tr x-data="{ openReset: false }" class="hover:bg-slate-50/50 transition-colors {{ !$user->status_aktif ? 'opacity-60' : '' }}">
                        <td class="py-4 px-6 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800">{{ $user->name }}</div>
                            @if($user->force_password_change)
                            <span class="text-xs text-amber-600 flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-key"></i> Wajib ganti password
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-slate-600">{{ $user->username }}</td>
                        <td class="py-4 px-6">
                            @php
                                $roleName = $user->roles->first()->name ?? 'Tanpa Role';
                                $badgeClass = match($roleName) {
                                    'Super Admin'    => 'bg-emerald-100 text-emerald-700',
                                    'Disperindag'    => 'bg-cyan-100 text-cyan-700',
                                    'Agen LPG'       => 'bg-amber-100 text-amber-700',
                                    'Pangkalan LPG'  => 'bg-orange-100 text-orange-700',
                                    'Pengawas'       => 'bg-purple-100 text-purple-700',
                                    default          => 'bg-slate-100 text-slate-700'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($user->status_aktif)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-circle text-emerald-500 text-[6px] mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <i class="fa-solid fa-circle text-red-400 text-[6px] mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-slate-400 hover:text-brand transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button @click="openReset = true" class="text-slate-400 hover:text-slate-700 transition-colors" title="Reset Password">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                    @if($user->status_aktif)
                                    <!-- Tombol Nonaktifkan -->
                                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Nonaktifkan pengguna {{ $user->name }}? Sesi aktifnya akan langsung dihapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Nonaktifkan">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                    @else
                                    <!-- Tombol Aktifkan Kembali -->
                                    <form action="{{ route('superadmin.users.activate', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-slate-400 hover:text-emerald-500 transition-colors" title="Aktifkan Kembali">
                                            <i class="fa-solid fa-rotate-right"></i>
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>

                            <!-- Reset Password Modal -->
                            <div x-show="openReset" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
                                 x-transition>
                                <div @click.away="openReset = false" class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" x-transition>
                                    <form action="{{ route('superadmin.users.reset-password', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                            <div>
                                                <h3 class="font-bold text-lg text-slate-800">Reset Password</h3>
                                                <p class="text-xs text-slate-500">{{ $user->name }} &bull; User akan wajib ganti password setelah login</p>
                                            </div>
                                            <button type="button" @click="openReset = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                                <i class="fa-solid fa-xmark text-lg"></i>
                                            </button>
                                        </div>
                                        <div class="p-6">
                                            <label class="block mb-2 text-xs font-bold text-slate-700">Password Sementara</label>
                                            <input type="password" name="password" required minlength="8"
                                                   class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand"
                                                   placeholder="Masukkan password sementara (min. 8 karakter)">
                                        </div>
                                        <div class="px-6 pb-6 flex justify-end gap-3">
                                            <button type="button" @click="openReset = false" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Batal</button>
                                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand hover:bg-brand-dark rounded-lg transition-colors shadow-sm">Simpan Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-500">
                            <i class="fa-solid fa-users-slash text-3xl mb-3 opacity-30 block"></i>
                            Belum ada pengguna terdaftar atau tidak ada hasil pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection