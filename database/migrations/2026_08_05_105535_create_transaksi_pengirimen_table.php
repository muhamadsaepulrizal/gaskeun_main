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
        Schema::create('transaksi_pengirimans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pangkalan_id')->constrained('users')->cascadeOnDelete();
            $table->integer('jumlah_tabung');
            $table->date('tanggal_pengiriman');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['Menunggu', 'Diterima', 'Dikoreksi'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pengirimans');
    }
};
