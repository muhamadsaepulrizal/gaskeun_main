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
        Schema::create('koreksi_pengirimans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_pengiriman_id')->constrained('transaksi_pengirimans')->cascadeOnDelete();
            $table->integer('jumlah_seharusnya');
            $table->text('keterangan_koreksi')->nullable();
            $table->enum('status_koreksi', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koreksi_pengirimans');
    }
};
