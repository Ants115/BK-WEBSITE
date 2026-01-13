<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Tingkatan;
use App\Models\Jurusan;
use Illuminate\Support\Str; // Tambahkan ini agar bisa buat Slug

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua data master
        $semuaTingkatan = Tingkatan::all();
        $semuaJurusan = Jurusan::all();

        // 2. Jumlah kelas default (bisa diubah jadi 8 sesuai kebutuhanmu)
        $jumlahKelasDefault = 8; 

        // 3. Loop pembuatan kelas
        foreach ($semuaTingkatan as $tingkatan) {
            foreach ($semuaJurusan as $jurusan) {
                
                $jumlah = $jumlahKelasDefault;

                for ($i = 1; $i <= $jumlah; $i++) {
                    // Membuat nama kelas, contoh: "X TKR 1"
                    $namaKelasLengkap = $tingkatan->nama_tingkatan . ' ' . $jurusan->singkatan . ' ' . $i;
                    
                    Kelas::create([
                        'tingkatan_id' => $tingkatan->id,
                        'jurusan_id'   => $jurusan->id,
                        // Perbaikan: Nama unik jadi "x-tkr-1" (lebih aman & rapi)
                        'nama_unik'    => Str::slug($namaKelasLengkap), 
                        'nama_kelas'   => $namaKelasLengkap,
                        'tahun_ajaran' => '2025/2026',
                    ]);
                }
            }
        }
    }
}