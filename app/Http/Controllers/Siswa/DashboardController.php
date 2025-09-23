<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Baris ini mendefinisikan variabel $user
        $user = Auth::user()->load('biodataSiswa');

        // Pengecekan status siswa
        if ($user->biodataSiswa && $user->biodataSiswa->status === 'Lulus') {
            // Jika statusnya 'Lulus', tampilkan view khusus alumni
            return view('alumni.dashboard', compact('user'));
        }

        // Jika statusnya 'Aktif' atau lainnya, lanjutkan logika dashboard siswa biasa
        // Baris 21 (yang error) adalah baris di bawah ini
        $user->load(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran', 'pelanggaranSiswa.pelapor']);

        $totalPoin = $user->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });

        // Ambil notifikasi yang belum dibaca
        $notifikasiList = Notifikasi::where('user_id', $user->id)
                                    ->where('dibaca', false)
                                    ->latest()
                                    ->get();
        
        // Setelah notifikasi diambil, langsung update statusnya menjadi 'sudah dibaca'
        $notifikasiList->each->update(['dibaca' => true]);

        return view('dashboard', [
            'user' => $user,
            'totalPoin' => $totalPoin,
            'notifikasiList' => $notifikasiList,
        ]);
    }
}