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
        // Buat User untuk Login
        $adminUser = User::create([
            'name' => 'Admin BK',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'), // Passwordnya 'password'
            'role' => 'admin',
        ]);

        // Buat Biodata untuk Staf/Admin
        BiodataStaf::create([
            'user_id' => $adminUser->id,
            'nip' => '00001',
            'nama_lengkap' => 'Admin BK',
            'jabatan' => 'Administrator Sistem',
        ]);
    }
}