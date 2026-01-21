<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('biodata_siswa', function (Blueprint $table) {
        // Cek dulu biar gak error kalau sudah ada
        if (!Schema::hasColumn('biodata_siswa', 'poin_pelanggaran')) {
            $table->integer('poin_pelanggaran')->default(0)->after('user_id'); 
        }
    });
}

public function down()
{
    Schema::table('biodata_siswa', function (Blueprint $table) {
        $table->dropColumn('poin_pelanggaran');
    });
}
};
