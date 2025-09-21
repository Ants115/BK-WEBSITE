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
        // Hapus data lama untuk menghindari duplikat
        Kelas::query()->delete();

        // AMBIL ID UNTUK TINGKATAN
        $tingkatanX = Tingkatan::where('nama_tingkatan', 'X')->firstOrFail();
        $tingkatanXI = Tingkatan::where('nama_tingkatan', 'XI')->firstOrFail();
        $tingkatanXII = Tingkatan::where('nama_tingkatan', 'XII')->firstOrFail();

        // AMBIL ID UNTUK JURUSAN
        $jurusanRPL = Jurusan::where('singkatan', 'RPL')->firstOrFail();
        $jurusanTKR = Jurusan::where('singkatan', 'TKRO')->firstOrFail(); // Menggunakan singkatan TKRO

        // BUAT DATA KELAS
        Kelas::create(['tingkatan_id' => $tingkatanX->id, 'jurusan_id' => $jurusanRPL->id, 'nama_unik' => '1', 'nama_kelas' => 'X RPL 1', 'tahun_ajaran' => '2025/2026']);
        Kelas::create(['tingkatan_id' => $tingkatanX->id, 'jurusan_id' => $jurusanRPL->id, 'nama_unik' => '2', 'nama_kelas' => 'X RPL 2', 'tahun_ajaran' => '2025/2026']);
        Kelas::create(['tingkatan_id' => $tingkatanX->id, 'jurusan_id' => $jurusanTKR->id, 'nama_unik' => '1', 'nama_kelas' => 'X TKRO 1', 'tahun_ajaran' => '2025/2026']);

        Kelas::create(['tingkatan_id' => $tingkatanXI->id, 'jurusan_id' => $jurusanRPL->id, 'nama_unik' => '1', 'nama_kelas' => 'XI RPL 1', 'tahun_ajaran' => '2025/2026']);
        Kelas::create(['tingkatan_id' => $tingkatanXI->id, 'jurusan_id' => $jurusanTKR->id, 'nama_unik' => '1', 'nama_kelas' => 'XI TKRO 1', 'tahun_ajaran' => '2025/2026']);

        // TAMBAHKAN BARIS INI UNTUK MEMBUAT KELAS XII
        Kelas::create(['tingkatan_id' => $tingkatanXII->id, 'jurusan_id' => $jurusanRPL->id, 'nama_unik' => '1', 'nama_kelas' => 'XII RPL 1', 'tahun_ajaran' => '2025/2026']);
        Kelas::create(['tingkatan_id' => $tingkatanXII->id, 'jurusan_id' => $jurusanTKR->id, 'nama_unik' => '1', 'nama_kelas' => 'XII TKRO 1', 'tahun_ajaran' => '2025/2026']);
    }
    }
