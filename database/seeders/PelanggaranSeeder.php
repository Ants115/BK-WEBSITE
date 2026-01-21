<?php

namespace Database\Seeders;

use App\Models\Pelanggaran;
use Illuminate\Database\Seeder;

class PelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Terlambat (Ringan)
        Pelanggaran::create([
            'nama_pelanggaran' => 'Terlambat masuk sekolah',
            'poin' => 5,
            'kategori' => 'Ringan', // <--- Pastikan ini ada
        ]);

        // 2. Tidak mengerjakan tugas (Ringan)
        Pelanggaran::firstOrCreate(
            ['nama_pelanggaran' => 'Tidak mengerjakan tugas'], 
            ['poin' => 10, 'kategori' => 'Ringan'] 
        );

        // 3. Membolos (Sedang)
        Pelanggaran::firstOrCreate(
            ['nama_pelanggaran' => 'Membolos'], 
            ['poin' => 25, 'kategori' => 'Sedang']
        );

        // 4. Seragam (Ringan)
        Pelanggaran::firstOrCreate(
            ['nama_pelanggaran' => 'Seragam tidak sesuai aturan'], 
            ['poin' => 10, 'kategori' => 'Ringan']
        );

        // 5. Merokok (Berat)
        Pelanggaran::firstOrCreate(
            ['nama_pelanggaran' => 'Merokok di area sekolah'], 
            ['poin' => 50, 'kategori' => 'Berat']
        );
    }
}