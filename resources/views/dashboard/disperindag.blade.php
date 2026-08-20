@extends('layouts.app')
@section('title', 'Dashboard Disperindag')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    

    <!-- Database Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="card p-6 border-b-4 border-b-blue-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Total UMKM</p>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">1,240</p>
                    <p class="text-xs font-semibold text-blue-600 mt-2">Berdasarkan verifikasi</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-blue-50 text-blue-600 border border-blue-100">
                    <i class="fa-solid fa-shop text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="card p-6 border-b-4 border-b-emerald-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Rumah Tangga</p>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">15,300</p>
                    <p class="text-xs font-semibold text-emerald-600 mt-2">Data DTKS Kemensos</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <i class="fa-solid fa-house-chimney text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6 border-b-4 border-b-indigo-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Nelayan</p>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">842</p>
                    <p class="text-xs font-semibold text-indigo-600 mt-2">Penerima Kartu Kusuka</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-ship text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6 border-b-4 border-b-amber-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Petani</p>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">2,105</p>
                    <p class="text-xs font-semibold text-amber-600 mt-2">Kelompok Terverifikasi</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-amber-50 text-amber-600 border border-amber-100">
                    <i class="fa-solid fa-wheat-awn text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Area (Placeholder) -->
    <div class="card p-6 border border-slate-200">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800">Tren Penyaluran LPG 3Kg (Bulan Ini)</h3>
            <select class="input-field max-w-[180px] py-2 text-sm">
                <option value="">Semua Kategori</option>
                <option value="umkm">UMKM</option>
                <option value="rt">Rumah Tangga</option>
            </select>
        </div>
        
        <div class="w-full h-64 rounded-xl flex items-center justify-center bg-slate-50 border-2 border-dashed border-slate-200">
            <div class="text-center">
                <i class="fa-solid fa-chart-line text-4xl text-slate-300 mb-3 block"></i>
                <p class="text-sm font-bold text-slate-500">Area Visualisasi Grafik</p>
                <p class="text-xs text-slate-400 mt-1 font-medium">Data akan diload secara dinamis dari API.</p>
            </div>
        </div>
    </div>
</div>
@endsection
