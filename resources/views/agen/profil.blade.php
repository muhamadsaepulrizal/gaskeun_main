@extends('layouts.app')
@section('title', 'Profil Agen')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    

    <div class="card p-8 max-w-2xl bg-white shadow-sm border border-slate-200">
        <form action="{{ route('agen.profil.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="label-field">Nama Agen</label>
                <input type="text" name="nama_agen" value="{{ old('nama_agen', $profil->nama_agen) }}" required
                       class="input-field mt-1" placeholder="Nama resmi agen LPG">
                @error('nama_agen')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label-field">No. Registrasi</label>
                <input type="text" name="no_registrasi" value="{{ old('no_registrasi', $profil->no_registrasi) }}"
                       class="input-field mt-1 font-mono tracking-wider"
                       placeholder="Nomor registrasi resmi agen">
                @error('no_registrasi')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label-field">Alamat Agen</label>
                <textarea name="alamat" rows="3" class="input-field mt-1"
                          placeholder="Alamat lengkap agen LPG">{{ old('alamat', $profil->alamat) }}</textarea>
                @error('alamat')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label-field">Nomor Kontak</label>
                <input type="text" name="kontak" value="{{ old('kontak', $profil->kontak) }}"
                       class="input-field mt-1" placeholder="Nomor telepon / WhatsApp">
                @error('kontak')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('agen.dashboard') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">Batal</a>
                <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-check mr-2"></i> Simpan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
