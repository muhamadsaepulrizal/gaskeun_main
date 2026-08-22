<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\OtpVerification;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class PublicKeluhanController extends Controller
{
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        return view('public.keluhan.create', compact('kecamatans'));
    }

    public function getPangkalans($kecamatan_id)
    {
        $pangkalans = \App\Models\User::role('Pangkalan LPG')
            ->whereHas('pangkalanProfile', function ($query) use ($kecamatan_id) {
                $query->where('kecamatan_id', $kecamatan_id);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($pangkalans);
    }

    public function getDesas($kecamatan_id)
    {
        $desas = \App\Models\Desa::where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama_desa')
            ->get(['id', 'nama_desa']);
            
        return response()->json($desas);
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->email;
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::updateOrCreate(
            ['email' => $email],
            [
                'otp_code' => $otpCode,
                'expires_at' => now()->addMinutes(5),
                'is_verified' => false
            ]
        );

        try {
            // Cek apakah mailer hanya log (belum dikonfigurasi)
            if (config('mail.default') === 'log' || env('MAIL_MAILER') === 'log') {
                return response()->json([
                    'success' => true,
                    'message' => 'Mode Simulasi (SMTP belum dikonfigurasi)',
                    'mode' => 'simulation',
                    'otp_demo' => $otpCode
                ]);
            }

            Mail::to($email)->send(new OtpMail($otpCode));
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Mode Simulasi (Koneksi SMTP Gagal)',
                'mode' => 'simulation',
                'otp_demo' => $otpCode
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'mode' => 'email'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric|digits:6',
        ]);

        $otp = OtpVerification::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('is_verified', false)
            ->first();

        if (!$otp) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah.']);
        }

        if (now()->greaterThan($otp->expires_at)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP kadaluarsa. Silakan minta ulang.']);
        }

        $otp->update(['is_verified' => true]);

        return response()->json(['success' => true, 'message' => 'OTP terverifikasi.']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nama_pelapor' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'pangkalan_id' => 'nullable|exists:users,id',
            'jenis_aduan' => 'required|string',
            'isi_keluhan' => 'required|string',
            'foto_bukti' => 'nullable|image|max:5120',
        ]);

        $otp = OtpVerification::where('email', $request->email)
            ->where('is_verified', true)
            ->first();

        if (!$otp) {
            return redirect()->back()->with('error', 'Verifikasi Email gagal atau belum dilakukan.');
        }

        $kodeTiket = 'TKT-GAS-' . strtoupper(Str::random(6));

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('keluhan', 'public');
        }

        Keluhan::create([
            'kode_tiket' => $kodeTiket,
            'email_pelapor' => $request->email,
            'nama_pelapor' => $request->nama_pelapor,
            'kecamatan_id' => $request->kecamatan_id,
            'desa_id' => $request->desa_id,
            'pangkalan_id' => $request->pangkalan_id,
            'jenis_aduan' => $request->jenis_aduan,
            'isi_keluhan' => $request->isi_keluhan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'foto_bukti' => $fotoPath,
            'status_keluhan' => 'Masuk',
            'otp_verified_at' => now(),
        ]);

        $otp->delete();

        return redirect()->route('public.keluhan.create')
            ->with('success_ticket', $kodeTiket);
    }

    public function status()
    {
        return view('public.keluhan.status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['kode_tiket' => 'required|string']);

        $keluhan = Keluhan::with(['pangkalan', 'kecamatan'])->where('kode_tiket', $request->kode_tiket)->first();

        if ($keluhan) {
            return view('public.keluhan.status_detail', compact('keluhan'));
        }

        return redirect()->back()->with('error', 'Kode Tiket tidak ditemukan.');
    }
}
