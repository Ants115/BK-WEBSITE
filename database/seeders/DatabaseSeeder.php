<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BiodataSiswa;
use App\Models\BiodataStaf;
use App\Models\Kelas;
use App\Models\Tingkatan;
use App\Models\Jurusan;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
        ]);
        
        // Matikan pengecekan foreign key agar bisa truncate (bersihkan) tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Bersihkan semua tabel terkait untuk memulai dari awal
        User::truncate();
        BiodataSiswa::truncate();
        BiodataStaf::truncate();
        Kelas::truncate();
        Tingkatan::truncate();
        Jurusan::truncate();
        Pelanggaran::truncate();
        
        // 3. Panggil semua seeder yang dibutuhkan secara berurutan
        // Pastikan file-file seeder ini ADA di folder database/seeders
        $this->call([
            TingkatanSeeder::class,
            JurusanSeeder::class,
            KelasSeeder::class,
            PelanggaranSeeder::class,
            AdminUserSeeder::class, 
        ]);

        // 4. Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        User::create([
            'name' => 'Bu Rani',
            'email' => 'rani.bk@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'guru_bk',
        ]);
        
        User::create([
            'name' => 'Pak Dimas',
            'email' => 'dimas.bk@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'guru_bk',
        ]);
        
    }


}