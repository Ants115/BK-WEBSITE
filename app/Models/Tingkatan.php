<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tingkatan extends Model
{
    use HasFactory;

    // Dengan menghapus baris "protected $table", Laravel akan secara otomatis
    // mencari nama tabel plural, yaitu "tingkatans", yang cocok dengan migrasi Anda.

    protected $fillable = ['nama_tingkatan'];
}