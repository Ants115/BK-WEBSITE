    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('jadwal_konseling', function (Blueprint $table) {
                $table->id();
                $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
                $table->string('hari')->nullable(); // contoh: "Senin", "Selasa", dsb (opsional)
                $table->date('tanggal')->nullable(); // bisa dipakai kalau jadwalnya harian spesifik
                $table->time('jam_mulai');
                $table->time('jam_selesai')->nullable();
                $table->string('lokasi')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('jadwal_konseling');
        }
    };
