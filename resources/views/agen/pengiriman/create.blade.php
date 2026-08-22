@extends('layouts.app')
@section('title', 'Input Pengiriman LPG')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Custom Light Theme for Tom Select */
    .ts-control {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important; /* slate-200 */
        color: #334155 !important; /* slate-700 */
        border-radius: 0.75rem !important; /* rounded-xl */
        padding: 0.75rem 1rem !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
    }
    .ts-control.focus {
        border-color: #10B981 !important; /* brand border */
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important; /* focus:ring */
    }
    .ts-control > input {
        color: #334155 !important;
    }
    .ts-dropdown {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        color: #334155 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden;
        margin-top: 4px;
    }
    .ts-dropdown .option {
        padding: 0.5rem 1rem !important;
    }
    .ts-dropdown .option:hover, .ts-dropdown .option.active {
        background-color: #f8fafc !important; /* slate-50 */
        color: #10B981 !important; /* brand */
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#pangkalan_id',{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
</script>
@endpush

@section('content')

<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Input Pengiriman LPG</h1>
            <p class="text-sm text-slate-500 mt-1">Catat pengiriman tabung LPG 3 Kg ke Pangkalan binaan Anda.</p>
        </div>
        <a href="{{ route('agen.dashboard') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 max-w-4xl">
        @foreach($errors->all() as $error)
        <p class="text-red-700 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="max-w-4xl bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Tab Switcher --}}
        <div class="flex border-b border-slate-200 bg-slate-50">
            <button id="tab-manual" onclick="switchTab('manual')"
                class="tab-btn px-6 py-4 text-sm font-bold transition-all duration-300 border-b-2 text-brand border-brand flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Input Manual
            </button>
            <button id="tab-excel" onclick="switchTab('excel')"
                class="tab-btn px-6 py-4 text-sm font-bold transition-all duration-300 border-b-2 text-slate-500 border-transparent hover:text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Import Excel
            </button>
        </div>

        {{-- ============================================================ --}}
        {{-- FORM INPUT MANUAL --}}
        {{-- ============================================================ --}}
        <div id="panel-manual" class="tab-panel p-6 sm:p-8">
            <form action="{{ route('agen.pengiriman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Pangkalan Tujuan --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pangkalan Tujuan <span class="text-red-500">*</span></label>
                        <select id="pangkalan_id" name="pangkalan_id" required placeholder="-- Pilih Pangkalan Tujuan --">
                            <option value="">-- Pilih Pangkalan --</option>
                            @foreach($pangkalans as $p)
                                <option value="{{ $p->user_id }}">{{ $p->nama_pangkalan ?? $p->user->name }}</option>
                            @endforeach
                        </select>
                        @error('pangkalan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jumlah Tabung --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Tabung <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-box text-slate-400"></i>
                            </div>
                            <input type="number" name="jumlah_tabung" value="{{ old('jumlah_tabung') }}" min="1" required
                                class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all" 
                                placeholder="Masukkan jumlah tabung">
                        </div>
                        @error('jumlah_tabung') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tanggal Pengiriman --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pengiriman <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman', date('Y-m-d')) }}" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all">
                    @error('tanggal_pengiriman') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Upload Foto Bukti --}}
                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Upload Foto Bukti <span class="text-slate-400 font-normal text-xs ml-1">(Opsional)</span></label>
                    <div class="relative mt-2">
                        <input type="file" name="foto_bukti" accept="image/*" id="fotoInput"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition cursor-pointer">
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    @error('foto_bukti') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                    {{-- Preview gambar --}}
                    <div id="preview-container" class="mt-4 hidden relative w-max">
                        <img id="preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border-2 border-slate-200 shadow-sm">
                        <button type="button" onclick="clearPreview()" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow transition">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-brand hover:bg-brand-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Simpan Pengiriman
                    </button>
                    <a href="{{ route('agen.pengiriman.status') }}" class="px-6 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- ============================================================ --}}
        {{-- FORM IMPORT EXCEL --}}
        {{-- ============================================================ --}}
        <div id="panel-excel" class="tab-panel hidden p-6 sm:p-8">
            <div class="p-4 rounded-xl mb-6 bg-blue-50 border border-blue-200 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                <p class="text-sm text-blue-800">
                    <strong class="font-bold">Format Excel:</strong> Pastikan tabel Anda memiliki struktur berikut: Kolom A = ID Pangkalan, Kolom B = Jumlah Tabung, Kolom C = Tanggal (YYYY-MM-DD). Baris pertama digunakan sebagai header.
                </p>
            </div>

            <form action="{{ route('agen.pengiriman.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-center border-dashed">
                    <i class="fa-solid fa-file-excel text-4xl text-green-500 mb-3"></i>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File Excel <span class="text-red-500">*</span></label>
                    <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required
                           class="block w-full max-w-sm mx-auto text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 transition cursor-pointer">
                    <p class="mt-3 text-xs text-slate-500">Format: XLSX, XLS, CSV. Maksimal 5MB.</p>
                    @error('file_excel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-600/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Import Data Excel
                    </button>
                    <a href="{{ route('agen.pengiriman.status') }}" class="px-6 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('text-brand', 'border-brand');
            b.classList.add('text-slate-500', 'border-transparent');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.classList.remove('text-slate-500', 'border-transparent');
        btn.classList.add('text-brand', 'border-brand');
    }

    // Preview foto upload
    const fotoInput = document.getElementById('fotoInput');
    const previewContainer = document.getElementById('preview-container');
    const previewImg = document.getElementById('preview-img');

    fotoInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                previewImg.src = ev.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            clearPreview();
        }
    });

    function clearPreview() {
        fotoInput.value = '';
        previewImg.src = '';
        previewContainer.classList.add('hidden');
    }
</script>
@endsection
