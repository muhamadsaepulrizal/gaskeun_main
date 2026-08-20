@extends('layouts.public')
@section('title', 'Cek Status Laporan')

@section('page-title', 'Cek Status Laporan')
@section('page-subtitle', 'Masukkan Kode Tiket untuk melacak status pengaduan Anda')

@section('content')

    @if(session('error'))
        <div class="alert-error mb-6">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card p-6 sm:p-8">
        <form action="{{ route('public.keluhan.check-status') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="label-field text-center block text-lg">Kode Tiket Laporan</label>
                <input type="text" name="kode_tiket" required class="input-field mt-3 text-center text-2xl font-mono tracking-widest p-4 uppercase" placeholder="TKT-GAS-XXXXXX">
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 text-center">
                <button type="submit" class="btn-primary w-full sm:w-auto px-8 justify-center">Lacak Status</button>
            </div>
        </form>
    </div>
@endsection
