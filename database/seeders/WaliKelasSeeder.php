<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas; // Pastikan model Kelas di-import jika ada relasi
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WaliKelasSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Akun User Budi Santoso
        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@sekolah.id', // Sesuaikan email login
            'password' => Hash::make('password123'), // Password default
            'role' => 'wali_kelas', // Sesuaikan dengan kolom role di tabel users kamu
            // 'nip' => '198001012023011001', // Tambahkan jika ada kolom NIP
        ]);

        // Jika menggunakan Spatie Permission, gunakan baris ini:
        // $budi->assignRole('wali_kelas');

        // 2. Hubungkan Budi ke Kelas (Opsional, jika tabel kelas sudah ada)
        // Contoh: Menjadikan Budi wali kelas X RPL 1
        $kelas = Kelas::where('nama_kelas', 'X RPL 1')->first();
        if ($kelas) {
            $kelas->update([
                'wali_kelas_id' => $budi->id // Sesuaikan nama kolom foreign key di tabel kelas
            ]);
        }
    }
}