<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanKonsultasiController extends Controller
{
    public function index()
    {
        // nanti isi logic dan view
        return view('admin.laporan-konsultasi.index');
    }
}
