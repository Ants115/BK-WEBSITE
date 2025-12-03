<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom wali_kelas_id kalau belum ada
        if (!Schema::hasColumn('kelas', 'wali_kelas_id')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->foreignId('wali_kelas_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kelas', 'wali_kelas_id')) {
            Schema::table('kelas', function (Blueprint $table) {
                // Hapus FK + kolom
                $table->dropConstrainedForeignId('wali_kelas_id');
            });
        }
    }
};
