@extends('layouts.app')
@section('title', 'Role & Hak Akses')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Role & Hak Akses</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola pembagian peran dan struktur base role untuk pengguna sistem.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ session('error') }}
    </div>
    @endif

    <div class="max-w-6xl">
        
        <!-- Add Role Card -->
        <div class="mb-8 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shadow-inner">
                        <i class="fa-solid fa-shield-halved text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Tambah Role Baru</h2>
                        <p class="text-sm text-slate-500">Buat role spesifik yang diturunkan dari base role utama.</p>
                    </div>
                </div>

                <form action="{{ route('superadmin.roles.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <!-- Input Nama Role -->
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Nama Role Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-id-card"></i>
                                </div>
                                <input type="text" name="name" required placeholder="Contoh: Agen Cabang 2" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-teal-50 focus:border-teal-400 transition-all outline-none">
                            </div>
                        </div>
                        
                        <!-- Select Base Role -->
                        <div class="flex-1 w-full md:max-w-xs">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Base Role (Turunan)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <select name="base_role" required class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-4 focus:ring-teal-50 focus:border-teal-400 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Base Role --</option>
                                    <option value="Super Admin">Super Admin</option>
                                    <option value="Disperindag">Disperindag</option>
                                    <option value="Agen LPG">Agen LPG</option>
                                    <option value="Pangkalan LPG">Pangkalan LPG</option>
                                    <option value="Pengawas">Pengawas</option>
                                    <option value="Publik">Publik</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 focus:ring-4 focus:ring-teal-100 transition-all shadow-sm shadow-teal-600/20 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Roles Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list text-slate-400"></i> Daftar Role Tersedia
                </h3>
                <span class="text-xs font-semibold bg-white border border-slate-200 text-slate-600 px-3 py-1.5 rounded-full shadow-sm">
                    Total: {{ count($roles) }} Role
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Role</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Base Role Terkait</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Pengguna</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($roles as $role)
                        @php
                            $systemRoles = ['Super Admin', 'Disperindag', 'Agen LPG', 'Pangkalan LPG', 'Pengawas', 'Publik'];
                            $isSystemRole = in_array($role->name, $systemRoles);
                        @endphp
                        <tr x-data="{ openEdit: false }" class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 group-hover:text-teal-600 transition-colors">{{ $role->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $base = $role->base_role ?? $role->name;
                                    $colorClass = match($base) {
                                        'Super Admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'Disperindag' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Agen LPG' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Pangkalan LPG' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'Pengawas' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'Publik' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $colorClass }}">
                                    <i class="fa-solid fa-layer-group mr-1.5 opacity-60"></i> {{ strtoupper($base) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center min-w-[4rem] h-8 px-3 rounded-full bg-slate-50 text-slate-600 font-bold text-xs border border-slate-200 group-hover:bg-teal-50 group-hover:text-teal-700 group-hover:border-teal-200 transition-all shadow-sm">
                                    {{ $role->users_count ?? $role->users()->count() }} User
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(!$isSystemRole)
                                <div class="flex items-center justify-end gap-3">
                                    <button @click="openEdit = true" class="text-slate-400 hover:text-teal-600 transition-colors" title="Edit Role">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('superadmin.roles.destroy', $role->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini? Penghapusan tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Hapus Role">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Role Modal -->
                                <div x-show="openEdit" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
                                     x-transition>
                                    <div @click.away="openEdit = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-200" x-transition>
                                        <form action="{{ route('superadmin.roles.update', $role->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 border border-teal-100">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-lg text-slate-800">Edit Role</h3>
                                                        <p class="text-xs text-slate-500">Ubah detail role kustom ini.</p>
                                                    </div>
                                                </div>
                                                <button type="button" @click="openEdit = false" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-200">
                                                    <i class="fa-solid fa-xmark text-lg"></i>
                                                </button>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <div>
                                                    <label class="block mb-2 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Role</label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                            <i class="fa-regular fa-id-card"></i>
                                                        </div>
                                                        <input type="text" name="name" required value="{{ $role->name }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-4 focus:ring-teal-50 focus:border-teal-400 transition-all">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block mb-2 text-xs font-bold text-slate-700 uppercase tracking-wider">Base Role (Turunan)</label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                            <i class="fa-solid fa-layer-group"></i>
                                                        </div>
                                                        <select name="base_role" required class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-4 focus:ring-teal-50 focus:border-teal-400 transition-all appearance-none">
                                                            @foreach(['Super Admin', 'Disperindag', 'Agen LPG', 'Pangkalan LPG', 'Pengawas', 'Publik'] as $br)
                                                                <option value="{{ $br }}" {{ ($role->base_role ?? $role->name) == $br ? 'selected' : '' }}>{{ $br }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
                                                <button type="button" @click="openEdit = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                                                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                                                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @else
                                <span class="text-xs text-slate-400 italic">Sistem (Terkunci)</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 border border-slate-100 mb-4 shadow-inner">
                                    <i class="fa-solid fa-folder-open text-2xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada role yang terdaftar dalam sistem.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
