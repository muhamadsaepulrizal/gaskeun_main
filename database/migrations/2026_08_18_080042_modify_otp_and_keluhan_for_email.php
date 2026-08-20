<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->string('email')->nullable()->after('no_wa');
            $table->string('no_wa')->nullable()->change();
        });

        Schema::table('keluhans', function (Blueprint $table) {
            $table->string('email_pelapor')->nullable()->after('no_hp_pelapor');
            $table->string('no_hp_pelapor')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->string('no_wa')->nullable(false)->change();
        });

        Schema::table('keluhans', function (Blueprint $table) {
            $table->dropColumn('email_pelapor');
            $table->string('no_hp_pelapor')->nullable(false)->change();
        });
    }
};
