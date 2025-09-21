<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- PENTING: Import model User
use App\Models\BiodataSiswa; // <-- PENTING: Import model BiodataSiswa

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil semua seeder untuk data master terlebih dahulu
        $this->call([
            TingkatanSeeder::class,
            JurusanSeeder::class,
            AdminUserSeeder::class,
            PelanggaranSeeder::class,
            KelasSeeder::class, // Pastikan KelasSeeder juga dipanggil
        ]);

        // 2. Buat satu user siswa untuk testing
        $userSiswa = User::factory()->create([
            'name' => 'Siswa Contoh',
            'email' => 'siswa@example.com',
            'role' => 'siswa',
        ]);

        // 3. Buat biodata untuk user siswa tersebut
        // Pastikan ada kelas dengan ID=1 di database dari KelasSeeder
        BiodataSiswa::create([
            'user_id' => $userSiswa->id,
            'nis' => '12345678',
            'nama_lengkap' => $userSiswa->name,
            'kelas_id' => 1, 
        ]);
    }
}