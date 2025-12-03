<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Preload relasi yang dibutuhkan di dashboard
        $user->load([
            'biodataSiswa.kelas.waliKelas',          // data siswa + kelas + wali kelas
            'pelanggaranSiswa.pelanggaran',         // untuk hitung poin
            'pelanggaranSiswa.pelapor',             // nama guru pencatat
            'prestasi',                             // untuk menghitung total prestasi
        ]);

        // Jika statusnya 'Lulus', tampilkan dashboard alumni
        if ($user->biodataSiswa && $user->biodataSiswa->status === 'Lulus') {
            return view('alumni.dashboard', compact('user'));
        }

        // ==========================================
        // KONSULTASI BK
        // ==========================================

        // Cek jadwal konsultasi mendatang (disetujui & tanggal >= sekarang)
        $konsultasiMendatang = Konsultasi::where('siswa_id', $user->id)
            ->where('status', 'Disetujui')
            ->where('jadwal_disetujui', '>=', now())
            ->orderBy('jadwal_disetujui', 'asc')
            ->first();

        // Hitung total pengajuan konsultasi
        $totalPengajuan = Konsultasi::where('siswa_id', $user->id)->count();

        // ==========================================
        // PELANGGARAN & POIN
        // ==========================================

        $totalPoin = $user->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });

        // ==========================================
        // PRESTASI
        // ==========================================

        $totalPrestasi = $user->prestasi->count();

        // ==========================================
        // RETURN VIEW
        // ==========================================

        return view('dashboard', compact(
            'user',
            'totalPoin',
            'konsultasiMendatang',
            'totalPengajuan',
            'totalPrestasi',
        ));
    }
}
