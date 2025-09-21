<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::updateOrCreate(['nama_jurusan' => 'Rekayasa Perangkat Lunak'], ['singkatan' => 'RPL']);
        Jurusan::updateOrCreate(['nama_jurusan' => 'Teknik Elektronika Industri'], ['singkatan' => 'TEI']);
        Jurusan::updateOrCreate(['nama_jurusan' => 'Teknik Kendaraan Ringan Otomotif'], ['singkatan' => 'TKRO']);
        Jurusan::updateOrCreate(['nama_jurusan' => 'Teknik Instalasi Tenaga Listrik'], ['singkatan' => 'TITL']);
        Jurusan::updateOrCreate(['nama_jurusan' => 'Teknik Pemesinan'], ['singkatan' => 'TPM']);
    }
}
