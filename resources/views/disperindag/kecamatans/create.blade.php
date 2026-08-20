@extends('layouts.app')
@section('title', 'Tambah Kecamatan')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.kecamatans.index') }}" class="btn-secondary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="card p-8 max-w-xl">
    <form action="{{ route('disperindag.kecamatans.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="label-field">Nama Kecamatan</label>
            <input type="text" name="nama_kecamatan" required class="input-field mt-1" placeholder="Nama kecamatan">
            @error('nama_kecamatan')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex justify-end gap-3" style="border-top:1px solid rgba(255,255,255,0.05);">
            <a href="{{ route('disperindag.kecamatans.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan Data</button>
        </div>
    </form>
</div>
@endsection