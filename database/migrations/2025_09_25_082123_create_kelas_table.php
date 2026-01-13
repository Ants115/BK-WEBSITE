<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tingkatan_id')->constrained('tingkatans');
            $table->foreignId('jurusan_id')->constrained('jurusans');
            $table->string('nama_unik')->nullable(); // Jadikan nullable jaga-jaga
            $table->string('nama_kelas');
            $table->string('tahun_ajaran')->default(date('Y')); // Kasih default tahun sekarang
            $table->foreignId('wali_kelas_id')->nullable()->constrained('users'); // PERBAIKAN DISINI
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};