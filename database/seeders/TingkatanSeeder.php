<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tingkatan; // <-- Pastikan Model di-import

class TingkatanSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikat
        Tingkatan::query()->delete();

        Tingkatan::create(['nama_tingkatan' => 'X']);
        Tingkatan::create(['nama_tingkatan' => 'XI']);
        Tingkatan::create(['nama_tingkatan' => 'XII']);
    }
}