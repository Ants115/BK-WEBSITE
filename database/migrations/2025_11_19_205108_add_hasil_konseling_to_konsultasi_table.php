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
        if (!Schema::hasColumn('konsultasi', 'hasil_konseling')) {
            
            // Jika BELUM ADA, baru tambahkan
            Schema::table('konsultasi', function (Blueprint $table) {
                $table->text('hasil_konseling')->nullable()->after('pesan_guru');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dropColumn('hasil_konseling');
        });
    }   
};
