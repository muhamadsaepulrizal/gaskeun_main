@extends('layouts.app')
@section('title', 'Edit Kecamatan')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.kecamatans.index') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="max-w-2xl mx-auto">
<div class="card p-8 md:p-10 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
    <form action="{{ route('disperindag.kecamatans.update', $item->id) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="label-field">Nama Kecamatan</label>
            <input type="text" name="nama_kecamatan" value="{{ old('nama_kecamatan', $item->nama_kecamatan) }}" required class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all">
            @error('nama_kecamatan')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex justify-end gap-3 border-t border-slate-200">
            <a href="{{ route('disperindag.kecamatans.index') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">Batal</a>
            <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5">Simpan Perubahan</button>
        </div>
    </form>
</div>
</div>
@endsection