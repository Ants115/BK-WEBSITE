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
        // Pengecekan aman seperti sebelumnya
        if (!Schema::hasTable('jurusans')) {
            Schema::create('jurusans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_jurusan');
                $table->string('singkatan'); // <--- INI YANG HILANG TADI
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};