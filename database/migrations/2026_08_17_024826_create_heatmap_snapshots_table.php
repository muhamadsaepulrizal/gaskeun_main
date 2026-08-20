<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heatmap_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->date('tanggal_snapshot')->comment('BR-08: Setiap hari sisipkan baris baru, jangan overwrite');
            $table->decimal('skor_heatmap', 5, 2)->default(0)->comment('Skor 0-100 hasil normalisasi 7 parameter (BR-10)');
            $table->enum('level_risiko', ['Aman', 'Waspada', 'Rawan', 'Kritis'])->default('Aman');
            $table->json('parameter_detail')->nullable()->comment('Detail 7 parameter kalkulasi dalam JSON');
            $table->integer('rekomendasi_kuota')->default(0)->comment('FR-18: Rekomendasi kuota = konsumen_sasaran x rata_konsumsi');
            $table->integer('jumlah_konsumen_sasaran')->default(0);
            $table->decimal('rata_konsumsi_harian', 8, 2)->default(0);
            $table->integer('kepadatan_penduduk')->default(0)->comment('BR-22: Kepadatan per km2');
            $table->timestamps();
            
            // BR-08: Unik per kecamatan per tanggal (satu snapshot per hari)
            $table->unique(['kecamatan_id', 'tanggal_snapshot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heatmap_snapshots');
    }
};
