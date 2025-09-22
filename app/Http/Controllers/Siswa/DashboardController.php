<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi; // <-- 1. Diperbaiki: Menambahkan import untuk Model Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // <-- 2. Diperbaiki: Menambahkan import untuk class View

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk siswa.
     */
    public function index(): View
    {
        $user = Auth::user();
        $user->load(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran', 'pelanggaranSiswa.pelapor']);
    
        $totalPoin = $user->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });
    
        // Mengambil notifikasi yang belum dibaca
        $notifikasiList = Notifikasi::where('user_id', $user->id)
                                      ->where('dibaca', false)
                                      ->latest()
                                      ->get();
    
        return view('dashboard', [
            'user' => $user,
            'totalPoin' => $totalPoin,
            'notifikasiList' => $notifikasiList, // Kirim notifikasi ke view
        ]);
    }
}