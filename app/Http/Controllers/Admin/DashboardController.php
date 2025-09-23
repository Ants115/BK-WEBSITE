<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PelanggaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- DATA STATISTIK DASAR ---
        $totalSiswaAktif = User::where('role', 'siswa')
                               ->whereHas('biodataSiswa', fn($q) => $q->where('status', 'Aktif'))
                               ->count();
        $totalPelanggaran = PelanggaranSiswa::count();

        // --- DATA SISWA POIN TERTINGGI (TOP 5) ---
        $siswaPoinTertinggi = PelanggaranSiswa::join('users', 'pelanggaran_siswa.siswa_user_id', '=', 'users.id')
            ->join('pelanggaran', 'pelanggaran_siswa.pelanggaran_id', '=', 'pelanggaran.id')
            ->select('users.name', DB::raw('SUM(pelanggaran.poin) as total_poin'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        // --- DATA PELANGGARAN TERATAS (TOP 5) ---
        $pelanggaranTeratas = PelanggaranSiswa::join('pelanggaran', 'pelanggaran_siswa.pelanggaran_id', '=', 'pelanggaran.id')
            ->select('pelanggaran.nama_pelanggaran', DB::raw('COUNT(pelanggaran_siswa.id) as jumlah_kasus'))
            ->groupBy('pelanggaran.id', 'pelanggaran.nama_pelanggaran')
            ->orderByDesc('jumlah_kasus')
            ->limit(5)
            ->get();
        
        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalSiswaAktif',
            'totalPelanggaran',
            'siswaPoinTertinggi',
            'pelanggaranTeratas'
        ));
    }
}