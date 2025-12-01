<?php

namespace Database\Seeders;

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
        // Daftar akun guru BK (termasuk admin)
        $guruBkList = [
            [
                'user' => [
                    'name'     => 'Admin BK',
                    'email'    => 'admin@sekolah.com',
                    'password' => 'ant123',
                    'role'     => 'guru_bk',
                ],
                'biodata' => [
                    'nip'          => '00001',
                    'nama_lengkap' => 'Admin BK',
                    'jabatan'      => 'Administrator Sistem',
                ],
            ],
            [
                'user' => [
                    'name'     => 'Bu prapti BK',              // silakan ganti sesuai kebutuhan
                    'email'    => 'prapti.bk@sekolah.com',     // pastikan unik
                    'password' => 'prapti123',               // ganti kalau mau
                    'role'     => 'guru_bk',
                ],
                'biodata' => [
                    'nip'          => '00002',
                    'nama_lengkap' => 'Bu prapti BK',
                    'jabatan'      => 'Guru BK',
                ],
            ],
            [
                'user' => [
                    'name'     => 'bu eka BK',            // silakan ganti
                    'email'    => 'eka.bk@sekolah.com',
                    'password' => 'eka123',
                    'role'     => 'guru_bk',
                ],
                'biodata' => [
                    'nip'          => '00003',
                    'nama_lengkap' => 'bu eka',
                    'jabatan'      => 'Guru BK',
                ],
            ],
        ];

        foreach ($guruBkList as $item) {
            // 1. Buat / update user
            $user = User::updateOrCreate(
                ['email' => $item['user']['email']], // kunci unik
                [
                    'name'     => $item['user']['name'],
                    'role'     => $item['user']['role'],
                    'password' => Hash::make($item['user']['password']),
                ]
            );

            // 2. Buat / update biodata staf
            BiodataStaf::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip'          => $item['biodata']['nip'],
                    'nama_lengkap' => $item['biodata']['nama_lengkap'],
                    'jabatan'      => $item['biodata']['jabatan'],
                ]
            );
        }
    }
}
