@extends('layouts.app')
@section('title', 'Terima Pengiriman LPG')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Terima Pengiriman LPG</h1>
            <p class="text-sm text-slate-500 mt-1">Konfirmasi dan koreksi pengiriman tabung gas dari Agen Pembina Anda.</p>
        </div>
        <a href="{{ route('pangkalan.dashboard') }}" class="text-sm text-slate-500 hover:text-brand flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 max-w-full">
        <p class="text-red-700 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</p>
    </div>
    @endif
    
    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4 max-w-full">
        <p class="text-emerald-700 text-sm flex items-center gap-2"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Agen Pengirim</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Foto</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengiriman as $i => $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono text-xs">{{ $pengiriman->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800">{{ $item->agen ? ($item->agen->name ?? $item->agen->username) : 'Agen Dihapus' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-brand text-lg">{{ $item->jumlah_tabung }}</span>
                            <span class="text-xs text-slate-500">tabung</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-500 text-xs">
                            {{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->isoFormat('D MMM YYYY') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->foto_bukti)
                                <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition border border-blue-100">
                                    <i class="fa-regular fa-image"></i> Lihat
                                </a>
                            @else
                                <span class="text-slate-400 italic text-xs">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status == 'Menunggu')
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded-full text-xs font-bold uppercase tracking-wide">Menunggu</span>
                            @elseif($item->status == 'Diterima')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full text-xs font-bold uppercase tracking-wide">Diterima</span>
                            @else
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-full text-xs font-bold uppercase tracking-wide">Dikoreksi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($item->status == 'Menunggu')
                            <div x-data="{ showKoreksi: false }" class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('pangkalan.pengiriman.konfirmasi', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Konfirmasi terima {{ $item->jumlah_tabung }} tabung?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand text-white hover:bg-brand-dark rounded-xl text-xs font-bold shadow-sm hover:shadow-md hover:shadow-brand/20 transition hover:-translate-y-0.5">
                                            <i class="fa-solid fa-check"></i> Konfirmasi
                                        </button>
                                    </form>
                                    <button type="button" @click="showKoreksi = !showKoreksi" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 rounded-xl text-xs font-bold shadow-sm transition">
                                        <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Koreksi
                                    </button>
                                </div>

                                <div x-show="showKoreksi" x-cloak style="display: none;" class="w-full max-w-xs mt-3 p-4 rounded-xl bg-slate-50 border border-slate-200 text-left shadow-lg shadow-slate-200/50">
                                    <form action="{{ route('pangkalan.pengiriman.koreksi', $item->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah yang Diterima <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input type="number" name="jumlah_diterima" required min="0"
                                                       class="w-full pl-3 pr-10 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                                                       placeholder="0">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-slate-400 text-xs">tabung</span>
                                                </div>
                                            </div>
                                            @error('jumlah_diterima') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Alasan</label>
                                            <textarea name="keterangan_koreksi" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition"
                                                      placeholder="Misal: 2 tabung bocor..."></textarea>
                                            @error('keterangan_koreksi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex gap-2 pt-1">
                                            <button type="button" @click="showKoreksi = false" class="flex-1 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition">Batal</button>
                                            <button type="submit" class="flex-1 py-2 bg-amber-500 text-white rounded-lg text-xs font-bold hover:bg-amber-600 transition shadow-sm hover:shadow shadow-amber-500/20">Kirim Koreksi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @elseif($item->status == 'Dikoreksi' && $item->koreksi)
                                <div class="text-right inline-block bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">
                                    <p class="text-xs text-slate-600">Terima aktual: <strong class="text-slate-900 text-sm">{{ $item->koreksi->jumlah_seharusnya }}</strong> <span class="text-slate-500">tabung</span></p>
                                    @if($item->koreksi->keterangan_koreksi)
                                        <p class="text-xs mt-1.5 text-slate-500 italic bg-white p-2 rounded-lg border border-slate-100">"{{ $item->koreksi->keterangan_koreksi }}"</p>
                                    @endif
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-600 border border-slate-200 rounded-full text-xs font-bold"><i class="fa-solid fa-check text-brand"></i> Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                            </div>
                            <h3 class="text-slate-700 font-bold mb-1">Tidak ada pengiriman masuk</h3>
                            <p class="text-slate-500 text-sm">Belum ada data pengiriman LPG dari Agen Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengiriman->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $pengiriman->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
