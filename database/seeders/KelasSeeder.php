<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Tingkatan;
use App\Models\Jurusan;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua data master yang diperlukan
        $semuaTingkatan = Tingkatan::all();
        $semuaJurusan = Jurusan::all();

        // 2. Definisikan jumlah kelas untuk setiap jurusan (default & pengecualian)
        // Atur jumlah kelas default untuk semua jurusan menjadi 9
        $jumlahKelasDefault = 9;

        // 3. Gunakan loop untuk membuat semua kombinasi kelas secara dinamis
        foreach ($semuaTingkatan as $tingkatan) {
            foreach ($semuaJurusan as $jurusan) {
                // Semua jurusan sekarang akan memiliki 9 kelas paralel
                $jumlah = $jumlahKelasDefault;

                for ($i = 1; $i <= $jumlah; $i++) {
                    Kelas::create([
                        'tingkatan_id' => $tingkatan->id,
                        'jurusan_id' => $jurusan->id,
                        'nama_unik' => (string)$i,
                        'nama_kelas' => $tingkatan->nama_tingkatan . ' ' . $jurusan->singkatan . ' ' . $i,
                        'tahun_ajaran' => '2025/2026',
                    ]);
                }
            }
        }
    }
}