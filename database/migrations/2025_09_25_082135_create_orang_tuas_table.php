<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ortu_user_id')->constrained('users');
            $table->foreignId('siswa_user_id')->constrained('users');
            $table->string('hubungan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orang_tuas');
    }
};