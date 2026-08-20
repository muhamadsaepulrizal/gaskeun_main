<?php

namespace App\Http\Controllers;

use App\Models\ProfilAgen;
use App\Models\TransaksiPengiriman;
use App\Models\User;
use App\Models\PangkalanProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgenController extends Controller
{
    public function dashboard()
    {
        $agenId = Auth::id();

        $totalPengiriman = TransaksiPengiriman::where('agen_id', $agenId)->count();

        // Hanya pangkalan binaan agen ini
        $totalPangkalan = PangkalanProfile::where('agen_pembina_id', $agenId)->count();

        $pengirimanBulanIni = TransaksiPengiriman::where('agen_id', $agenId)
            ->whereMonth('tanggal_pengiriman', now()->month)
            ->whereYear('tanggal_pengiriman', now()->year)
            ->sum('jumlah_tabung');

        $pengirimanMenunggu = TransaksiPengiriman::where('agen_id', $agenId)
            ->where('status', 'Dikirim')->count();

        return view('agen.dashboard', compact(
            'totalPengiriman', 'totalPangkalan',
            'pengirimanBulanIni', 'pengirimanMenunggu'
        ));
    }

    // ============================================================
    // PROFIL AGEN
    // ============================================================
    public function profil()
    {
        $profil = ProfilAgen::firstOrCreate(
            ['user_id' => Auth::id()],
            ['nama_agen' => Auth::user()->name ?? Auth::user()->username]
        );
        return view('agen.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_agen'     => 'required|string|max:255',
            'no_registrasi' => 'nullable|string|max:100',
            'alamat'        => 'nullable|string',
            'kontak'        => 'nullable|string|max:50',
        ]);

        ProfilAgen::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('nama_agen', 'no_registrasi', 'alamat', 'kontak')
        );

        return redirect()->route('agen.profil')->with('success', 'Profil Agen berhasil diperbarui.');
    }

    // ============================================================
    // INPUT PENGIRIMAN LPG (BR-17, BR-21)
    // ============================================================
    public function pengirimanCreate()
    {
        // Hanya tampilkan pangkalan binaan agen ini (metode include)
        $pangkalans = PangkalanProfile::where('agen_pembina_id', Auth::id())
            ->with('user')
            ->get();

        // Jika belum ada profil/relasi, fallback ke semua pangkalan
        if ($pangkalans->isEmpty()) {
            $pangkalans = User::role('Pangkalan LPG')->get()->map(function ($u) {
                return (object)['user' => $u, 'nama_pangkalan' => $u->name, 'user_id' => $u->id];
            });
        }

        return view('agen.pengiriman.create', compact('pangkalans'));
    }

    public function pengirimanStore(Request $request)
    {
        $request->validate([
            'pangkalan_id'       => 'required|exists:users,id',
            'jumlah_tabung'      => 'required|integer|min:1',
            'tanggal_pengiriman' => 'required|date',
            // BR-21: Foto bukti wajib, maks 5MB
            'foto_bukti'         => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'foto_bukti.required' => 'Foto bukti pengiriman wajib dilampirkan.',
            'foto_bukti.max'      => 'Ukuran foto maksimal 5MB.',
        ]);

        // BR-17: Cek soft warning kuota (tidak blokir transaksi)
        $softWarning = null;
        $profil = PangkalanProfile::where('user_id', $request->pangkalan_id)->first();
        if ($profil && $profil->kuota_bulanan > 0) {
            $terkirimBulanIni = TransaksiPengiriman::where('agen_id', Auth::id())
                ->where('pangkalan_id', $request->pangkalan_id)
                ->whereMonth('tanggal_pengiriman', now()->month)
                ->sum('jumlah_tabung');

            $totalSetelah = $terkirimBulanIni + $request->jumlah_tabung;
            if ($totalSetelah > $profil->kuota_bulanan) {
                $softWarning = "⚠️ Peringatan: Pengiriman ini akan melampaui kuota bulan ini ({$profil->kuota_bulanan} tabung). Transaksi tetap diproses.";
            }
        }

        // Simpan foto bukti (BR-21 — kompresi via intervention/image jika tersedia)
        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $fotoPath = $file->store('pengiriman/foto', 'public');
        }

        $pengiriman = TransaksiPengiriman::create([
            'agen_id'            => Auth::id(),
            'pangkalan_id'       => $request->pangkalan_id,
            'jumlah_tabung'      => $request->jumlah_tabung,
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'foto_bukti'         => $fotoPath,
            'status'             => 'Dikirim', // Status awal MUTLAK (bukan 'Menunggu')
        ]);

        activity()->performedOn($pengiriman)->log("Pengiriman dibuat: {$request->jumlah_tabung} tabung ke pangkalan #{$request->pangkalan_id}");

        return redirect()->route('agen.pengiriman.status')
            ->with('success', 'Data pengiriman LPG berhasil disimpan dengan status "Dikirim".')
            ->with('warning', $softWarning);
    }

    // ============================================================
    // IMPORT EXCEL PENGIRIMAN (Partial Import)
    // ============================================================
    public function pengirimanImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $file        = $request->file('file_excel');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray();

            array_shift($rows); // Buang baris header

            $imported = 0;
            $errors   = [];

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 karena baris 1 = header

                if (empty($row[0])) continue; // Skip baris kosong

                try {
                    // Validasi setiap baris secara individual (Partial Import)
                    if (!is_numeric($row[0]) || !is_numeric($row[1])) {
                        throw new \Exception("Kolom A (ID Pangkalan) dan B (Jumlah) harus angka.");
                    }

                    $pangkalanId = (int) $row[0];
                    $jumlah      = (int) $row[1];
                    $tanggal     = isset($row[2]) ? date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[2])) : now()->toDateString();

                    if ($jumlah <= 0) {
                        throw new \Exception("Jumlah tabung harus lebih dari 0.");
                    }

                    // Cek pangkalan valid
                    if (!User::where('id', $pangkalanId)->role('Pangkalan LPG')->exists()) {
                        throw new \Exception("ID Pangkalan #{$pangkalanId} tidak ditemukan.");
                    }

                    TransaksiPengiriman::create([
                        'agen_id'            => Auth::id(),
                        'pangkalan_id'       => $pangkalanId,
                        'jumlah_tabung'      => $jumlah,
                        'tanggal_pengiriman' => $tanggal,
                        'status'             => 'Dikirim',
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    // Partial Import: catat error baris ini, lanjutkan ke baris berikutnya
                    $errors[] = "Baris {$rowNum}: " . $e->getMessage();
                }
            }

            $message = "Berhasil mengimpor {$imported} data pengiriman.";
            if (!empty($errors)) {
                $message .= ' ' . count($errors) . ' baris gagal diimpor.';
                session()->flash('import_errors', $errors);
            }

            return redirect()->route('agen.pengiriman.status')->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
    }

    // ============================================================
    // LIHAT STATUS PENGIRIMAN
    // ============================================================
    public function pengirimanStatus()
    {
        $pengiriman = TransaksiPengiriman::with(['pangkalan', 'koreksi'])
            ->where('agen_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('agen.pengiriman.status', compact('pengiriman'));
    }
}
