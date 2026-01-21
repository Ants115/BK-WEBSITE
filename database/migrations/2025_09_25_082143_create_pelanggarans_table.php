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
        // Cek agar tidak error "Table already exists"
        if (!Schema::hasTable('pelanggarans')) {
            Schema::create('pelanggarans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pelanggaran'); 
                $table->integer('poin'); 
                $table->string('kategori'); // Ringan, Sedang, Berat
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};