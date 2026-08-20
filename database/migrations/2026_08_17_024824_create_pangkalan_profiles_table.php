<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pangkalan_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('agen_pembina_id')->nullable()->constrained('users')->nullOnDelete()->comment('BR-02: Agen pembina pangkalan ini');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->nullOnDelete();
            $table->string('nama_pangkalan')->nullable();
            $table->string('no_registrasi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kontak', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('kuota_bulanan')->default(0)->comment('Kuota tabung per bulan dari Pertamina');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pangkalan_profiles');
    }
};
