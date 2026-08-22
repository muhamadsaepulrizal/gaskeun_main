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
        Schema::table('transaksi_penyalurans', function (Blueprint $table) {
            $table->dropForeign(['penduduk_id']);
            $table->foreign('penduduk_id')->references('id')->on('konsumens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_penyalurans', function (Blueprint $table) {
            $table->dropForeign(['penduduk_id']);
            $table->foreign('penduduk_id')->references('id')->on('penduduks')->onDelete('cascade');
        });
    }
};
