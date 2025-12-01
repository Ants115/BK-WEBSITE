<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    // Baris 'protected $table' dihapus agar Laravel otomatis mencari tabel 'jurusans'
    protected $fillable = ['nama_jurusan', 'singkatan'];
}
