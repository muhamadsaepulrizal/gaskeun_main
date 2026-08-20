<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('status_aktif')->default(true)->after('password'); // BR-13
            $table->boolean('force_password_change')->default(false)->after('status_aktif'); // Force Password Change
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status_aktif', 'force_password_change']);
        });
    }
};
