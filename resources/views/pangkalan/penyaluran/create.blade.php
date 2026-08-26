@extends('layouts.app')
@section('title', 'Transaksi Penyaluran LPG')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kasir Penyaluran LPG</h1>
            <p class="text-sm text-slate-500 mt-1">Cari konsumen yang terdaftar dan catat penyaluran LPG 3 Kg.</p>
        </div>
        <a href="{{ route('pangkalan.stok') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 max-w-4xl mx-auto">
        @foreach($errors->all() as $error)
        <p class="text-red-700 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif
    
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 max-w-4xl mx-auto">
        <p class="text-red-700 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</p>
    </div>
    @endif
    
    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4 max-w-4xl mx-auto">
        <p class="text-emerald-700 text-sm flex items-center gap-2"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</p>
    </div>
    @endif

    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6" 
         x-data="kasirApp()">
         
        <!-- KOLOM PENCARIAN -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Kategori -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-users text-brand"></i> 1. Kategori Konsumen
                </h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['Rumah Tangga', 'Usaha Mikro', 'Petani Sasaran', 'Nelayan Sasaran'] as $kat)
                    <button type="button" 
                            @click="setKategori('{{ $kat }}')"
                            :class="kategori === '{{ $kat }}' ? 'border-brand bg-brand/5 shadow-sm text-brand' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50' relative flex items-center justify-center p-3 border-2 rounded-xl transition-all font-semibold text-sm">
                        {{ $kat }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Pencarian (Live Search) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 relative">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-brand"></i> 2. Cari Konsumen
                </h2>
                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-slate-400" x-show="!isSearching"></i>
                        <i class="fa-solid fa-spinner fa-spin text-brand" x-show="isSearching" style="display: none;"></i>
                    </div>
                    <input type="text" x-model="keyword" @input="debounceSearch()"
                           placeholder="Ketik Nama Lengkap atau NIK..."
                           :disabled="!kategori || selectedKonsumen"
                           class="w-full pl-11 pr-4 py-3.5 border-2 border-slate-200 rounded-xl text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all disabled:bg-slate-50 disabled:cursor-not-allowed">
                    
                    <button type="button" @click="resetSearch()" x-show="keyword.length > 0 && !selectedKonsumen" style="display:none" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <!-- Dropdown Hasil Pencarian -->
                <div x-show="results.length > 0 && !selectedKonsumen" style="display: none;"
                     class="absolute z-10 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden left-0">
                    <ul class="max-h-64 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="item in results" :key="item.id">
                            <li>
                                <button type="button" @click="selectKonsumen(item)"
                                        class="w-full text-left px-5 py-3 hover:bg-slate-50 transition flex justify-between items-center group">
                                    <div>
                                        <p class="font-bold text-slate-800 group-hover:text-brand transition" x-text="item.nama"></p>
                                        <p class="text-xs text-slate-500 mt-0.5">NIK: <span class="font-mono" x-text="item.identifier"></span> &bull; <span class="font-semibold text-brand" x-text="item.kategori"></span></p>
                                    </div>
                                    <div class="text-xs px-2 py-1 bg-slate-100 text-slate-600 rounded" x-text="item.alamat"></div>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
                
                <!-- State Kosong -->
                <div x-show="keyword.length > 2 && results.length === 0 && !isSearching && !selectedKonsumen" style="display: none;"
                     class="mt-3 p-4 bg-orange-50 text-orange-700 text-sm rounded-xl border border-orange-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <div>
                        <p class="font-bold">Konsumen tidak ditemukan!</p>
                        <p class="mt-1">Konsumen mungkin belum terdaftar. <a href="{{ route('pangkalan.konsumen.create') }}" class="underline font-bold hover:text-orange-800">Daftarkan konsumen baru</a>.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM TRANSAKSI -->
        <div class="space-y-6">
            <!-- Informasi Sisa Stok -->
            <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5 text-brand">
                    <i class="fa-solid fa-fire-flame-simple text-9xl"></i>
                </div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Sisa Stok Tabung</p>
                <div class="flex items-baseline gap-2 relative z-10">
                    <span class="text-4xl font-extrabold text-slate-800">{{ $jumlahStok }}</span>
                    <span class="text-slate-500 font-medium">/ 3 Kg</span>
                </div>
            </div>

            <!-- Form Proses Transaksi -->
            <form action="{{ route('pangkalan.penyaluran.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 relative">
                @csrf
                <input type="hidden" name="konsumen_id" :value="selectedKonsumen ? selectedKonsumen.id : ''">

                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-brand"></i> 3. Proses Pembelian
                </h2>

                <!-- Kartu Identitas Konsumen Terpilih -->
                <div x-show="selectedKonsumen" style="display: none;" class="mb-5 p-4 border-2 border-brand/20 bg-brand/5 rounded-xl relative">
                    <button type="button" @click="resetSelection()" class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-white text-slate-400 hover:text-red-500 shadow-sm hover:shadow transition">
                        <i class="fa-solid fa-times text-xs"></i>
                    </button>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-brand text-white flex items-center justify-center font-bold">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800" x-text="selectedKonsumen?.nama"></p>
                            <p class="text-xs font-semibold text-brand bg-white px-2 py-0.5 rounded inline-block mt-1 border border-brand/10" x-text="kategori"></p>
                        </div>
                    </div>
                    <div class="text-sm text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100">
                        <p><span class="text-slate-400 w-12 inline-block">ID:</span> <span class="font-mono font-bold" x-text="selectedKonsumen?.identifier"></span></p>
                        <p class="mt-1"><span class="text-slate-400 w-12 inline-block">Data:</span> <span x-text="selectedKonsumen?.alamat"></span></p>
                    </div>
                </div>

                <!-- Block UI jika belum pilih konsumen -->
                <div x-show="!selectedKonsumen" class="mb-5 p-5 border-2 border-dashed border-slate-200 bg-slate-50 rounded-xl text-center">
                    <i class="fa-solid fa-hand-pointer text-slate-300 text-2xl mb-2"></i>
                    <p class="text-sm text-slate-500">Pilih konsumen dari kotak pencarian terlebih dahulu.</p>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Pembelian (Tabung) <span class="text-red-500">*</span></label>
                    <div class="flex items-center">
                        <button type="button" @click="if(jumlah > 1) jumlah--" :disabled="!selectedKonsumen" class="w-12 h-12 flex items-center justify-center bg-slate-100 border border-slate-200 rounded-l-xl text-slate-600 hover:bg-slate-200 disabled:opacity-50 transition">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="number" name="jumlah_tabung" x-model="jumlah" min="1" max="{{ $jumlahStok }}" required
                               class="w-full h-12 text-center border-y border-slate-200 text-lg font-bold text-slate-800 focus:outline-none"
                               :disabled="!selectedKonsumen">
                        <button type="button" @click="if(jumlah < {{ $jumlahStok }}) jumlah++" :disabled="!selectedKonsumen" class="w-12 h-12 flex items-center justify-center bg-slate-100 border border-slate-200 rounded-r-xl text-slate-600 hover:bg-slate-200 disabled:opacity-50 transition">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    @error('jumlah_tabung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" 
                        :disabled="!selectedKonsumen || jumlah < 1 || {{ $jumlahStok }} < 1"
                        :class="(!selectedKonsumen || jumlah < 1 || {{ $jumlahStok }} < 1) ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-brand hover:bg-brand-dark hover:shadow-lg hover:shadow-brand/20 text-white hover:-translate-y-0.5' w-full py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all duration-200">
                    <i class="fa-solid fa-check-circle"></i> Selesaikan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function kasirApp() {
    return {
        kategori: 'Rumah Tangga',
        keyword: '',
        results: [],
        isSearching: false,
        selectedKonsumen: null,
        debounceTimer: null,
        jumlah: 1,

        setKategori(kat) {
            this.kategori = kat;
            this.resetSearch();
            this.resetSelection();
        },

        resetSearch() {
            this.keyword = '';
            this.results = [];
        },

        resetSelection() {
            this.selectedKonsumen = null;
            this.keyword = '';
            this.jumlah = 1;
            // set focus back to input
            setTimeout(() => {
                document.querySelector('input[type="text"]').focus();
            }, 100);
        },

        selectKonsumen(item) {
            this.selectedKonsumen = item;
            this.keyword = item.nama;
            this.kategori = item.kategori;
            this.results = [];
        },

        debounceSearch() {
            clearTimeout(this.debounceTimer);
            
            if (this.keyword.trim().length < 2) {
                this.results = [];
                return;
            }

            this.isSearching = true;
            this.debounceTimer = setTimeout(() => {
                this.fetchData();
            }, 300); // 300ms delay
        },

        fetchData() {
            fetch(`{{ route('pangkalan.konsumen.search') }}?q=${encodeURIComponent(this.keyword)}`)
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    this.results = [];
                })
                .finally(() => {
                    this.isSearching = false;
                });
        }
    }
}
</script>
@endsection
