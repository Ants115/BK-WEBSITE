<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggaran')->unique();
            $table->integer('poin');
            $table->string('kategori')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};