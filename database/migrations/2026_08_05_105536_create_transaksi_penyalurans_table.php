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
        Schema::create('transaksi_penyalurans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pangkalan_id')->constrained('users')->cascadeOnDelete();
            $table->enum('kategori_konsumen', ['Rumah Tangga', 'UMKM', 'Nelayan', 'Petani']);
            $table->foreignId('penduduk_id')->constrained('penduduks')->cascadeOnDelete();
            $table->integer('jumlah_tabung');
            $table->date('tanggal_penyaluran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penyalurans');
    }
};
