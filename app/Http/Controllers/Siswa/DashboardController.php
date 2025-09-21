<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller; // <-- KITA KEMBALI GUNAKAN 'USE'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller // <-- DAN 'EXTENDS' SEDERHANA
{
    public function index()
    {
        $user = Auth::user();

        // Load relasi yang dibutuhkan dengan lebih efisien
        $user->load([
            'biodataSiswa', // Untuk NIS
            'pelanggaranSiswa.pelanggaran' // Untuk riwayat pelanggaran
        ]);
        $totalPoin = $user->pelanggaranSiswa->sum(function($item) {
            return $item->pelanggaran ? $item->pelanggaran->poin : 0;
        });

        return view('dashboard', [
            'user' => $user,
            'totalPoin' => $totalPoin
        ]);
    }
}