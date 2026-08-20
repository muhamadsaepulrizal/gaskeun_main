@extends('layouts.app')
@section('title', 'Ubah Password')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    
    

    @if($errors->any())
    <div class="mb-6 alert-error max-w-2xl">
        <i class="fa-solid fa-circle-xmark text-lg"></i>
        <div>
            <p class="font-bold">Gagal memperbarui password:</p>
            <ul class="list-disc ml-5 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="card p-8 max-w-2xl">
        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="input-wrapper">
                <label class="label-field">Password Saat Ini</label>
                <input type="password" name="current_password" required autofocus
                       class="input-field" placeholder="Masukkan password Anda saat ini">
            </div>

            <hr class="border-slate-200">

            <div class="input-wrapper">
                <label class="label-field">Password Baru</label>
                <input type="password" name="password" required minlength="8"
                       class="input-field" placeholder="Minimal 8 karakter">
            </div>

            <div class="input-wrapper">
                <label class="label-field">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       class="input-field" placeholder="Ketik ulang password baru">
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
