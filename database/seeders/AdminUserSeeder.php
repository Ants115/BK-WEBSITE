<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BiodataStaf;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User untuk Login dengan role dan password yang benar
        $adminUser = User::create([
            'name' => 'Admin BK',
            'email' => 'admin@sekolah.com',
            'role' => 'guru_bk', // <-- Diperbaiki dari 'admin' menjadi 'guru_bk'
            'password' => Hash::make('ant123'), // <-- Diperbaiki dari 'password' menjadi 'ant123'
        ]);

        // 2. Buat Biodata untuk Staf/Admin
        BiodataStaf::create([
            'user_id' => $adminUser->id,
            'nip' => '00001',
            'nama_lengkap' => 'Admin BK',
            'jabatan' => 'Administrator Sistem',
        ]);
    }
}   