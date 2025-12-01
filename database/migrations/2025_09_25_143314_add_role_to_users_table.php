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
        // Cek dulu apakah kolom 'role' sudah ada atau belum
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('siswa')->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cek dulu apakah kolom 'role' ada sebelum dihapus
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                // Menghapus kolom 'role' jika migrasi di-rollback
                $table->dropColumn('role');
            });
        }
    }
};