<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tingkatan extends Model
{
    use HasFactory;

    protected $table = 'tingkatan';
    protected $fillable = ['nama_tingkatan'];
}