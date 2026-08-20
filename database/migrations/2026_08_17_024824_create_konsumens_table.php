<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel konsumens sudah ada dari DB dump tapi perlu tambahan kolom
     * nik_hash dan nib_hash untuk validasi unik global (BR-19)
     */
    public function up(): void
    {
        if (!Schema::hasTable('konsumens')) {
            Schema::create('konsumens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pangkalan_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->nullOnDelete();
                $table->enum('kategori', ['Rumah Tangga', 'Usaha Mikro', 'Petani', 'Nelayan'])->index();
                $table->string('nama_lengkap');
                $table->text('nik_encrypted')->nullable()->comment('NIK terenkripsi AES - Rumah Tangga (BR-20)');
                $table->string('nik_hash', 64)->nullable()->index()->comment('Hash SHA256 NIK untuk lookup unik (BR-19)');
                $table->text('nib_encrypted')->nullable()->comment('NIB terenkripsi AES - Usaha Mikro (BR-20)');
                $table->string('nib_hash', 64)->nullable()->index()->comment('Hash SHA256 NIB untuk lookup unik (BR-19)');
                $table->string('alamat')->nullable();
                $table->string('kontak', 20)->nullable();
                $table->boolean('is_anomali')->default(false)->comment('Flag jika beli di luar pangkalan asal (BR-18)');
                $table->timestamps();
            });
        } else {
            // Tabel sudah ada, tambahkan kolom yang kurang
            Schema::table('konsumens', function (Blueprint $table) {
                if (!Schema::hasColumn('konsumens', 'nik_hash')) {
                    $table->string('nik_hash', 64)->nullable()->index()->after('nik_encrypted')
                          ->comment('Hash SHA256 NIK untuk lookup unik (BR-19)');
                }
                if (!Schema::hasColumn('konsumens', 'nib_hash')) {
                    $table->string('nib_hash', 64)->nullable()->index()->after('nib_encrypted')
                          ->comment('Hash SHA256 NIB untuk lookup unik (BR-19)');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('konsumens', function (Blueprint $table) {
            if (Schema::hasColumn('konsumens', 'nik_hash')) {
                $table->dropIndex(['nik_hash']);
                $table->dropColumn('nik_hash');
            }
            if (Schema::hasColumn('konsumens', 'nib_hash')) {
                $table->dropIndex(['nib_hash']);
                $table->dropColumn('nib_hash');
            }
        });
    }
};
