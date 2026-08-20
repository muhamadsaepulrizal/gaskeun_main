<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Tied to users table
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->text('isi_keluhan');
            $table->string('status_keluhan')->default('pending'); // pending, verifikasi, selesai, dll.
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhans');
    }
};
