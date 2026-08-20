@extends('layouts.app')
@section('title', 'Terima Pengiriman LPG')

@section('content')


<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Agen Pengirim</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengiriman as $i => $item)
                <tr>
                    <td style="color:#334155; font-family:'JetBrains Mono',monospace; font-size:0.75rem;">{{ $pengiriman->firstItem() + $i }}</td>
                    <td class="font-medium" style="color:#CBD5E1;">{{ $item->agen ? ($item->agen->name ?? $item->agen->username) : 'Agen Dihapus' }}</td>
                    <td>
                        <span class="font-mono font-bold" style="color:#06B6D4;">{{ $item->jumlah_tabung }}</span>
                        <span class="text-xs ml-1" style="color:#475569;">tabung</span>
                    </td>
                    <td style="color:#475569; font-family:'JetBrains Mono',monospace; font-size:0.8125rem;">{{ $item->tanggal_pengiriman }}</td>
                    <td>
                        @if($item->foto_bukti)
                            <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank" class="btn-edit" style="padding:0.25rem 0.625rem; font-size:0.6875rem;">Lihat</a>
                        @else
                            <span style="color:#334155;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'Menunggu')
                            <span class="badge-pending">Menunggu</span>
                        @elseif($item->status == 'Diterima')
                            <span class="badge-active">Diterima</span>
                        @else
                            <span class="badge-danger">Dikoreksi</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($item->status == 'Menunggu')
                        <div x-data="{ showKoreksi: false }" class="flex flex-col items-end gap-2">
                            <form action="{{ route('pangkalan.pengiriman.konfirmasi', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Konfirmasi terima {{ $item->jumlah_tabung }} tabung?')">
                                @csrf
                                <button type="submit" class="btn-primary" style="padding:0.375rem 0.875rem; font-size:0.75rem;">✅ Konfirmasi</button>
                            </form>
                            <button @click="showKoreksi = !showKoreksi" class="btn-danger" style="padding:0.375rem 0.875rem; font-size:0.75rem;">⚠ Koreksi</button>

                            <div x-show="showKoreksi" x-cloak class="w-full mt-2 p-4 rounded-xl" style="background:rgba(244,63,94,0.05); border:1px solid rgba(244,63,94,0.15);">
                                <form action="{{ route('pangkalan.pengiriman.koreksi', $item->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="label-field" style="font-size:0.625rem;">Jumlah Seharusnya</label>
                                        <input type="number" name="jumlah_seharusnya" required min="0"
                                               class="input-field mt-1" style="font-size:0.8125rem;"
                                               placeholder="Jumlah tabung seharusnya">
                                    </div>
                                    <div>
                                        <label class="label-field" style="font-size:0.625rem;">Keterangan</label>
                                        <textarea name="keterangan_koreksi" rows="2" class="input-field mt-1" style="font-size:0.8125rem;"
                                                  placeholder="Alasan koreksi..."></textarea>
                                    </div>
                                    <button type="submit" class="btn-danger w-full" style="justify-content:center;">Kirim Koreksi</button>
                                </form>
                            </div>
                        </div>
                        @elseif($item->status == 'Dikoreksi' && $item->koreksi)
                            <div class="text-right">
                                <p class="text-xs" style="color:#F59E0B;">Seharusnya: <strong>{{ $item->koreksi->jumlah_seharusnya }}</strong> tabung</p>
                                @if($item->koreksi->keterangan_koreksi)
                                    <p class="text-xs mt-1" style="color:#475569;">{{ $item->koreksi->keterangan_koreksi }}</p>
                                @endif
                            </div>
                        @else
                            <span class="badge-active">Terkonfirmasi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12" style="color:#334155;">
                        <svg class="w-8 h-8 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        Belum ada pengiriman masuk dari Agen.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengiriman->hasPages())
    <div class="px-6 py-4" style="border-top:1px solid rgba(255,255,255,0.05);">
        {{ $pengiriman->links() }}
    </div>
    @endif
</div>
@endsection
