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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tingkatan_id')->constrained('tingkatan')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->string('nama_unik'); // Contoh: 1, 2, 3 (untuk X RPL 1, X RPL 2)
            $table->string('nama_kelas')->unique(); // Contoh: "X Rekayasa Perangkat Lunak 1"

            // Menambahkan unique constraint untuk kombinasi tingkatan, jurusan, dan nama unik
            $table->unique(['tingkatan_id', 'jurusan_id', 'nama_unik']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};