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
        Schema::create('kks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_kk')->unique();
            $table->text('alamat_lengkap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kks');
    }
};
