<?php

namespace Database\Seeders;

use App\Models\Pelanggaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pelanggaran::firstOrCreate(['nama_pelanggaran' => 'Terlambat masuk sekolah'], ['poin' => 5]);
        Pelanggaran::firstOrCreate(['nama_pelanggaran' => 'Tidak mengerjakan tugas'], ['poin' => 10]);
        Pelanggaran::firstOrCreate(['nama_pelanggaran' => 'Membolos'], ['poin' => 25]);
        Pelanggaran::firstOrCreate(['nama_pelanggaran' => 'Seragam tidak sesuai aturan'], ['poin' => 10]);
        Pelanggaran::firstOrCreate(['nama_pelanggaran' => 'Merokok di area sekolah'], ['poin' => 50]);
    }
}