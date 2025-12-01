<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// Import Model
use App\Models\Konsultasi;
// use App\Models\Notifikasi; // Kita gunakan notifikasi bawaan Laravel sekarang

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Ambil User yang sedang login beserta Biodatanya
        $user = Auth::user()->load('biodataSiswa');

        // 2. CEK STATUS ALUMNI
        // Jika statusnya 'Lulus', langsung tampilkan dashboard khusus alumni
        if ($user->biodataSiswa && $user->biodataSiswa->status === 'Lulus') {
            return view('alumni.dashboard', compact('user'));
        }

        // ==========================================
        // LOGIKA BARU: KONSULTASI BK
        // ==========================================
        
        // Cek jadwal konsultasi mendatang
        $konsultasiMendatang = Konsultasi::where('siswa_id', $user->id)
                                ->where('status', 'Disetujui')
                                ->where('jadwal_disetujui', '>=', now())
                                ->orderBy('jadwal_disetujui', 'asc')
                                ->first();

        // Hitung Total Pengajuan (Statistik)
        $totalPengajuan = Konsultasi::where('siswa_id', $user->id)->count();

        // ==========================================
        // LOGIKA LAMA: PELANGGARAN & POIN
        // ==========================================

        // Load relasi pelanggaran untuk menghitung poin
        $user->load(['pelanggaranSiswa.pelanggaran', 'pelanggaranSiswa.pelapor']);

        // Hitung Total Poin
        $totalPoin = $user->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });

        // ==========================================
        // RETURN VIEW
        // ==========================================
        
        return view('dashboard', compact(
            'user',
            'totalPoin',
            'konsultasiMendatang',
            'totalPengajuan'
        ));
    }
}