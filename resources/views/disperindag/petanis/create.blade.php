@extends('layouts.app')
@section('title', 'Tambah Petani')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.petanis.index') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="max-w-2xl mx-auto">
<div class="card p-8 md:p-10 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
    <form action="{{ route('disperindag.petanis.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="label-field">Penduduk (NIK - Nama)</label>
            <div class="relative mt-1 group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-list"></i></span>
                <select name="penduduk_id" required class="select-field pl-11 pr-10">
                    <option value="" >-- Pilih Penduduk --</option>
                    @foreach($penduduks as $opt)
                        <option value="{{ $opt->id }}" >{{ $opt->nik }} - {{ $opt->nama_lengkap }}</option>
                    @endforeach
                </select>
                
            </div>
        </div>
        <div>
            <label class="label-field">Luas Lahan (m²)</label>
            <div class="relative group mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-pen"></i></span>
                <input type="number" name="luas_lahan_m2" required class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all" placeholder="Contoh: 1000">
            </div>
            @error('luas_lahan_m2')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label-field">Jenis Komoditas</label>
            <div class="relative group mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-pen"></i></span>
                <input type="text" name="jenis_komoditas" required class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all" placeholder="Contoh: Padi, Jagung">
            </div>
            @error('jenis_komoditas')<p class="mt-1.5 text-xs" >{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex justify-end gap-3 border-t border-slate-200">
            <a href="{{ route('disperindag.petanis.index') }}" class="inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">Batal</a>
            <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5">Simpan Data</button>
        </div>
    </form>
</div>
</div>
@endsection