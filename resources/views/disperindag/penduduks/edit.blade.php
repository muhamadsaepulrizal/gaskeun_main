@extends('layouts.app')
@section('title', 'Edit Penduduk')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.penduduks.index') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="card p-8 max-w-2xl">
    <form action="{{ route('disperindag.penduduks.update', $item->id) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="label-field">Nomor KK</label>
            <div class="relative mt-1 group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-list"></i></span>
                <select name="kk_id" required class="select-field pl-11 pr-10">
                    <option value="" >-- Pilih KK --</option>
                    @foreach($kks as $opt)
                        <option value="{{ $opt->id }}"  {{ $item->kk_id == $opt->id ? 'selected' : '' }}>{{ $opt->nomor_kk }}</option>
                    @endforeach
                </select>
                
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="label-field">NIK (16 digit)</label>
                <input type="text" name="nik" value="{{ old('nik', $item->nik) }}" required maxlength="16" class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all" style="font-family:'JetBrains Mono',monospace; letter-spacing:0.08em;">
                @error('nik')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label-field">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $item->nama_lengkap) }}" required class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all">
                @error('nama_lengkap')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="label-field">Jenis Kelamin</label>
                <div class="relative mt-1 group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-list"></i></span>
                <select name="jenis_kelamin" required class="select-field pl-11 pr-10">
                        <option value="Laki-laki"  {{ $item->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan"  {{ $item->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    
                </div>
            </div>
            <div>
                <label class="label-field">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $item->tanggal_lahir) }}" required class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all" style="color-scheme:dark;">
                @error('tanggal_lahir')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="label-field">Pekerjaan</label>
            <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $item->pekerjaan) }}" required class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all">
            @error('pekerjaan')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex justify-end gap-3 border-t border-slate-200">
            <a href="{{ route('disperindag.penduduks.index') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">Batal</a>
            <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection