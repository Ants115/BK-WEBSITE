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
        Schema::table('jurusan', function (Blueprint $table) {
            // Menambahkan kolom 'singkatan' setelah 'nama_jurusan'
            // Anda bisa sesuaikan panjangnya jika perlu
            $table->string('singkatan', 15)->after('nama_jurusan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->dropColumn('singkatan');
        });
    }
};
