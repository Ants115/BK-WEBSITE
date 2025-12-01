<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            
            
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            
            $table->string('nama_prestasi'); 
            
            $table->string('jenis'); 
            
            $table->string('tingkat'); 
            
            $table->string('penyelenggara'); 
            
            $table->year('tahun'); 
            
            $table->string('bukti_foto')->nullable();
            
            $table->text('keterangan')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_siswas');
    }
};