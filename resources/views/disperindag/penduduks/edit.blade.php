@extends('layouts.app')
@section('title', 'Edit Penduduk')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.penduduks.index') }}" class="btn-secondary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="card p-8 max-w-2xl">
    <form action="{{ route('disperindag.penduduks.update', $item->id) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="label-field">Nomor KK</label>
            <div class="relative mt-1">
                <select name="kk_id" required class="select-field pr-10">
                    <option value="" style="background:#141E2E;">-- Pilih KK --</option>
                    @foreach($kks as $opt)
                        <option value="{{ $opt->id }}" style="background:#141E2E;" {{ $item->kk_id == $opt->id ? 'selected' : '' }}>{{ $opt->nomor_kk }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center"><svg class="w-4 h-4" style="color:#475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="label-field">NIK (16 digit)</label>
                <input type="text" name="nik" value="{{ old('nik', $item->nik) }}" required maxlength="16" class="input-field mt-1" style="font-family:'JetBrains Mono',monospace; letter-spacing:0.08em;">
                @error('nik')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label-field">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $item->nama_lengkap) }}" required class="input-field mt-1">
                @error('nama_lengkap')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="label-field">Jenis Kelamin</label>
                <div class="relative mt-1">
                    <select name="jenis_kelamin" required class="select-field pr-10">
                        <option value="Laki-laki" style="background:#141E2E;" {{ $item->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" style="background:#141E2E;" {{ $item->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center"><svg class="w-4 h-4" style="color:#475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                </div>
            </div>
            <div>
                <label class="label-field">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $item->tanggal_lahir) }}" required class="input-field mt-1" style="color-scheme:dark;">
                @error('tanggal_lahir')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="label-field">Pekerjaan</label>
            <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $item->pekerjaan) }}" required class="input-field mt-1">
            @error('pekerjaan')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex justify-end gap-3" style="border-top:1px solid rgba(255,255,255,0.05);">
            <a href="{{ route('disperindag.penduduks.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection