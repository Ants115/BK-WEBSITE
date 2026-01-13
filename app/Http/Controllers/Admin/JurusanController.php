<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        // nanti kamu isi logika ambil data jurusan
        return view('admin.jurusan.index');
    }
}