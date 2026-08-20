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
        Schema::table('profil_agens', function (Blueprint $table) {
            $table->string('pso')->nullable()->after('kontak');
            $table->string('jumlah_mitra')->nullable()->after('pso');
            $table->integer('id_spbe')->nullable()->after('jumlah_mitra');
            $table->decimal('latitude', 10, 8)->nullable()->after('id_spbe');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        Schema::table('pangkalan_profiles', function (Blueprint $table) {
            $table->foreignId('desa_kelurahan_id')->nullable()->constrained('desas')->nullOnDelete()->after('kecamatan_id');
            $table->string('penyaluran', 100)->nullable()->after('kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_agens', function (Blueprint $table) {
            $table->dropColumn(['pso', 'jumlah_mitra', 'id_spbe', 'latitude', 'longitude']);
        });

        Schema::table('pangkalan_profiles', function (Blueprint $table) {
            $table->dropForeign(['desa_kelurahan_id']);
            $table->dropColumn(['desa_kelurahan_id', 'penyaluran']);
        });
    }
};
