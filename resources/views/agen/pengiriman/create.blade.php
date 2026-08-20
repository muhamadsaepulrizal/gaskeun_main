@extends('layouts.app')
@section('title', 'Input Pengiriman LPG')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Custom Dark Theme for Tom Select */
    .ts-control {
        background-color: #0F172A !important; /* bg-slate-900 */
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #F8FAFC !important; /* text-slate-50 */
        border-radius: 0.5rem;
        padding: 0.75rem 1rem !important;
    }
    .ts-control > input {
        color: #F8FAFC !important;
    }
    .ts-dropdown {
        background-color: #1E293B !important; /* bg-slate-800 */
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #F8FAFC !important;
    }
    .ts-dropdown .option:hover, .ts-dropdown .option.active {
        background-color: #334155 !important; /* bg-slate-700 */
        color: #F8FAFC !important;
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


<div class="max-w-4xl">
    {{-- Tab Switcher --}}
    <div class="flex mb-6" style="border-bottom:1px solid rgba(255,255,255,0.05);">
        <button id="tab-manual" onclick="switchTab('manual')"
            class="tab-btn px-6 py-3 text-sm font-semibold transition-all duration-300" style="border-bottom:2px solid #10B981; color:#10B981;">
            📝 Input Manual
        </button>
        <button id="tab-excel" onclick="switchTab('excel')"
            class="tab-btn px-6 py-3 text-sm font-semibold transition-all duration-300" style="border-bottom:2px solid transparent; color:#64748B;">
            📊 Import Excel
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- FORM INPUT MANUAL --}}
    {{-- ============================================================ --}}
    <div id="panel-manual" class="tab-panel card p-8">
        <form action="{{ route('agen.pengiriman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pangkalan Tujuan --}}
                <div>
                    <label class="label-field">Pangkalan Tujuan <span style="color:#F43F5E;">*</span></label>
                    <select id="pangkalan_id" name="pangkalan_id" required placeholder="-- Pilih Pangkalan Tujuan --">
                        <option value="">-- Pilih Pangkalan --</option>
                        @foreach($pangkalans as $p)
                            <option value="{{ $p->id }}">{{ $p->name ?? $p->username }}</option>
                        @endforeach
                    </select>
                    @error('pangkalan_id') <p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p> @enderror
                </div>

                {{-- Jumlah Tabung --}}
                <div>
                    <label class="label-field">Jumlah Tabung <span style="color:#F43F5E;">*</span></label>
                    <input type="number" name="jumlah_tabung" value="{{ old('jumlah_tabung') }}" min="1" required
                        class="input-field" placeholder="Masukkan jumlah tabung">
                    @error('jumlah_tabung') <p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tanggal Pengiriman --}}
            <div>
                <label class="label-field">Tanggal Pengiriman <span style="color:#F43F5E;">*</span></label>
                <input type="date" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman', date('Y-m-d')) }}" required
                    class="input-field" style="color-scheme:dark;">
                @error('tanggal_pengiriman') <p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p> @enderror
            </div>

            {{-- Upload Foto Bukti --}}
            <div>
                <label class="label-field">Upload Foto Bukti</label>
                <div class="relative">
                    <input type="file" name="foto_bukti" accept="image/*" id="fotoInput"
                        class="w-full px-4 py-3 rounded-lg text-sm" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.1); color:#CBD5E1;">
                </div>
                <p class="mt-1.5 text-xs" style="color:#64748B;">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                @error('foto_bukti') <p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p> @enderror

                {{-- Preview gambar --}}
                <div id="preview-container" class="mt-4 hidden">
                    <img id="preview-img" src="" alt="Preview" class="w-48 h-48 object-cover rounded-lg" style="border:1px solid rgba(255,255,255,0.1);">
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-4 flex justify-end gap-3" style="border-top:1px solid rgba(255,255,255,0.05);">
                <a href="{{ route('agen.pengiriman.status') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengiriman
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- FORM IMPORT EXCEL --}}
    {{-- ============================================================ --}}
    <div id="panel-excel" class="tab-panel hidden card p-8">
        <div class="p-4 rounded-lg mb-6" style="background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.2);">
            <p class="text-sm" style="color:#3B82F6;">
                <strong style="color:#60A5FA;">📌 Format Excel:</strong> Kolom A = ID Pangkalan, Kolom B = Jumlah Tabung, Kolom C = Tanggal (YYYY-MM-DD). Baris pertama = header.
            </p>
        </div>

        <form action="{{ route('agen.pengiriman.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="label-field">Pilih File Excel <span style="color:#F43F5E;">*</span></label>
                <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required
                       class="w-full px-4 py-3 rounded-lg text-sm" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.1); color:#CBD5E1;">
                <p class="mt-1.5 text-xs" style="color:#64748B;">Format: XLSX, XLS, CSV. Maksimal 5MB.</p>
                @error('file_excel') <p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3" style="border-top:1px solid rgba(255,255,255,0.05);">
                <a href="{{ route('agen.pengiriman.status') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" style="background:#3B82F6; color:white;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.style.borderBottomColor = 'transparent';
            b.style.color = '#64748B';
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.style.borderBottomColor = '#10B981';
        btn.style.color = '#10B981';
    }

    // Preview foto upload
    document.getElementById('fotoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const container = document.getElementById('preview-container');
        const img = document.getElementById('preview-img');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                img.src = ev.target.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            container.classList.add('hidden');
        }
    });
</script>
@endsection
