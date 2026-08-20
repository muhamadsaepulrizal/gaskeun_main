@extends('layouts.app')
@section('title', 'Tambah KK')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('disperindag.kks.index') }}" class="btn-secondary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="card p-8 max-w-xl">
    <form action="{{ route('disperindag.kks.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="label-field">Desa / Kelurahan</label>
            <div class="relative mt-1">
                <select name="desa_id" required class="select-field pr-10">
                    <option value="" style="background:#141E2E;">-- Pilih Desa --</option>
                    @foreach($desas as $opt)
                        <option value="{{ $opt->id }}" style="background:#141E2E;">{{ $opt->nama_desa }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center"><svg class="w-4 h-4" style="color:#475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
            </div>
        </div>
        <div>
            <label class="label-field">Nomor KK</label>
            <input type="text" name="nomor_kk" required class="input-field mt-1" style="font-family:'JetBrains Mono',monospace; letter-spacing:0.05em;" placeholder="16 digit Nomor KK">
            @error('nomor_kk')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label-field">Alamat Lengkap</label>
            <textarea name="alamat_lengkap" required rows="3" class="input-field mt-1" placeholder="Alamat lengkap sesuai KK"></textarea>
            @error('alamat_lengkap')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex justify-end gap-3" style="border-top:1px solid rgba(255,255,255,0.05);">
            <a href="{{ route('disperindag.kks.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection