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
        Schema::table('keluhans', function (Blueprint $table) {
            $table->string('kode_tiket')->unique()->nullable()->after('id');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->nullOnDelete()->after('no_hp_pelapor');
            $table->foreignId('pangkalan_id')->nullable()->constrained('users')->nullOnDelete()->after('kecamatan_id');
            $table->string('jenis_aduan')->nullable()->after('pangkalan_id');
            $table->text('alasan_penolakan')->nullable()->after('status_keluhan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keluhans', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['pangkalan_id']);
            $table->dropColumn(['kode_tiket', 'kecamatan_id', 'pangkalan_id', 'jenis_aduan', 'alasan_penolakan']);
        });
    }
};
