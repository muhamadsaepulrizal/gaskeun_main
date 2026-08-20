@extends('layouts.app')
@section('title', 'Role & Permission')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    
    

    <div class="max-w-5xl mt-6">
        <!-- Roles -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-brand"></div>
                    <h3 class="font-bold text-sm text-slate-800">Daftar Role</h3>
                </div>
            </div>

            <!-- Add Role Form -->
            <div class="p-6 border-b border-slate-100 bg-white">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4">Tambah Role Baru</p>
                <form action="{{ route('superadmin.roles.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="name" required placeholder="Nama role kustom (contoh: Agen Cabang 2)" class="input-field sm:flex-1">
                    <select name="base_role" required class="input-field sm:w-64 shrink-0">
                        <option value="">-- Pilih Tipe Base Role --</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Disperindag">Disperindag</option>
                        <option value="Agen LPG">Agen LPG</option>
                        <option value="Pangkalan LPG">Pangkalan LPG</option>
                        <option value="Pengawas">Pengawas</option>
                        <option value="Publik">Publik</option>
                    </select>
                    <button type="submit" class="btn-primary shrink-0 justify-center">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Role
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto bg-white">
                <table class="data-table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Role</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Base Role</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Jumlah User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($roles as $role)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $role->name }}</td>
                            <td class="px-6 py-4">
                                <span class="badge-info">{{ $role->base_role ?? $role->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200">
                                    {{ $role->users_count ?? $role->users()->count() }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-8 text-slate-500">Belum ada role.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
