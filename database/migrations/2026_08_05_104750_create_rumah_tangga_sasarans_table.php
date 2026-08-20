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
        Schema::create('rumah_tangga_sasarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kk_id')->constrained()->cascadeOnDelete();
            $table->string('kriteria_bantuan');
            $table->enum('status_penerima', ['Layak', 'Tidak Layak', 'Menerima'])->default('Layak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rumah_tangga_sasarans');
    }
};
