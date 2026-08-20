@extends('layouts.app')
@section('title', 'Salurkan LPG')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori_konsumen');
        const kartuContainer = document.getElementById('kartu_container');
        const labelKartu = document.getElementById('label_kartu');
        const inputKartu = document.getElementById('nomor_kartu');

        function updateFields() {
            const kategori = kategoriSelect.value;
            if (kategori === 'Rumah Tangga') {
                kartuContainer.style.display = 'none';
                inputKartu.required = false;
            } else if (kategori === 'Usaha Mikro') {
                kartuContainer.style.display = 'block';
                labelKartu.innerText = 'NIB (Nomor Induk Berusaha)';
                inputKartu.required = true;
                inputKartu.placeholder = 'Masukkan NIB';
            } else if (kategori === 'Nelayan') {
                kartuContainer.style.display = 'block';
                labelKartu.innerText = 'Nomor Kartu Kusuka (Opsional)';
                inputKartu.required = false;
                inputKartu.placeholder = 'Masukkan Nomor Kartu Kusuka';
            } else if (kategori === 'Petani') {
                kartuContainer.style.display = 'block';
                labelKartu.innerText = 'Nomor Kartu Tani (Opsional)';
                inputKartu.required = false;
                inputKartu.placeholder = 'Masukkan Nomor Kartu Tani';
            } else {
                kartuContainer.style.display = 'none';
                inputKartu.required = false;
            }
        }

        kategoriSelect.addEventListener('change', updateFields);
        updateFields(); // Run on load
    });
</script>
@endpush

@section('content')


<div class="flex justify-end mb-6">
    <a href="{{ route('pangkalan.stok') }}" class="btn-secondary shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="card p-8 max-w-2xl">
    <form action="{{ route('pangkalan.penyaluran.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="label-field">Kategori Konsumen</label>
            <div class="relative mt-1">
                <select id="kategori_konsumen" name="kategori_konsumen" required class="select-field pr-10">
                    <option value="" style="background:#141E2E;">-- Pilih Kategori --</option>
                    <option value="Rumah Tangga" style="background:#141E2E;" {{ old('kategori_konsumen')=='Rumah Tangga'?'selected':'' }}>Rumah Tangga</option>
                    <option value="Usaha Mikro"  style="background:#141E2E;" {{ old('kategori_konsumen')=='Usaha Mikro'?'selected':'' }}>Usaha Mikro</option>
                    <option value="Nelayan"      style="background:#141E2E;" {{ old('kategori_konsumen')=='Nelayan'?'selected':'' }}>Nelayan</option>
                    <option value="Petani"       style="background:#141E2E;" {{ old('kategori_konsumen')=='Petani'?'selected':'' }}>Petani</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <svg class="w-4 h-4" style="color:#475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            @error('kategori_konsumen')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label-field">NIK Konsumen</label>
            <input type="text" name="nik" value="{{ old('nik') }}" required class="input-field mt-1" placeholder="Masukkan 16 Digit NIK">
            @error('nik')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label-field">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="input-field mt-1" placeholder="Nama sesuai KTP">
            @error('nama_lengkap')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>

        <div id="kartu_container" style="display: none;">
            <label id="label_kartu" class="label-field">Nomor Kartu / NIB</label>
            <input type="text" id="nomor_kartu" name="nomor_kartu" value="{{ old('nomor_kartu') }}" class="input-field mt-1" placeholder="">
            @error('nomor_kartu')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="label-field">Jumlah Tabung</label>
                <input type="number" name="jumlah_tabung" value="{{ old('jumlah_tabung', 1) }}"
                       min="1" max="{{ $jumlahStok }}" required
                       class="input-field mt-1"
                       placeholder="Jumlah tabung">
                @error('jumlah_tabung')<p class="mt-1.5 text-xs" style="color:#F43F5E;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="pt-2 flex justify-end gap-3" style="border-top:1px solid rgba(255,255,255,0.05);">
            <a href="{{ route('pangkalan.stok') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Salurkan LPG
            </button>
        </div>
    </form>
</div>
@endsection
