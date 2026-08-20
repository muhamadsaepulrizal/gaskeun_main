<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluhans', function (Blueprint $table) {
            // BR 3.2: Kunci ID verifikator saat staf klik tiket
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete()->after('tindak_lanjut');
            // FR-21: Timestamp untuk perhitungan SLA 2x24 jam
            $table->timestamp('tanggal_respon_wa')->nullable()->after('diverifikasi_oleh');
            // OTP Layer 2: Verifikasi keaslian pengaduan publik
            $table->string('otp_code', 10)->nullable()->after('tanggal_respon_wa');
            $table->timestamp('otp_verified_at')->nullable()->after('otp_code');
            // Throttle: nomor HP pelapor untuk pembatasan per-HP
            $table->string('no_hp_pelapor', 20)->nullable()->after('otp_verified_at');
            $table->string('nama_pelapor')->nullable()->after('no_hp_pelapor');
        });
    }

    public function down(): void
    {
        Schema::table('keluhans', function (Blueprint $table) {
            $table->dropForeign(['diverifikasi_oleh']);
            $table->dropColumn(['diverifikasi_oleh', 'tanggal_respon_wa', 'otp_code', 'otp_verified_at', 'no_hp_pelapor', 'nama_pelapor']);
        });
    }
};
