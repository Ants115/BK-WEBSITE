<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

// Import Model
use App\Models\User;
use App\Models\PelanggaranSiswa;
use App\Models\Pelanggaran;
use App\Models\Konsultasi; 

class DashboardController extends Controller
{
    public function index(): View
    {
        // ==========================================
        // BAGIAN 1: STATISTIK PELANGGARAN
        // ==========================================

        // 1. Data Statistik Dasar
        // PERBAIKAN: Mengganti nama variabel agar sesuai dengan View ($totalSiswa)
        $totalSiswaAktif = User::where('role', 'siswa')->count();
        $totalPelanggaran = PelanggaranSiswa::count();

        // 2. Data Siswa Poin Tertinggi (Top 5)
        $siswaPoinTertinggi = User::where('role', 'siswa')
            ->join('pelanggaran_siswas', 'users.id', '=', 'pelanggaran_siswas.siswa_user_id')
            ->join('pelanggarans', 'pelanggaran_siswas.pelanggaran_id', '=', 'pelanggarans.id')
            ->select('users.name', DB::raw('SUM(pelanggarans.poin) as total_poin'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        // 3. Data Pelanggaran Teratas (Top 5)
        $pelanggaranTeratas = Pelanggaran::withCount('pelanggaranSiswa as jumlah_kasus')
            ->orderByDesc('jumlah_kasus')
            ->limit(5)
            ->get();

        // ==========================================
        // BAGIAN 2: STATISTIK KONSULTASI
        // ==========================================

        // 4. Hitung Permintaan Baru
        $permintaanBaru = Konsultasi::where('status', 'Menunggu Persetujuan')->count();

        // 5. Hitung Jadwal Hari Ini
        $jadwalHariIni = Konsultasi::where('status', 'Disetujui')
            ->whereDate('jadwal_disetujui', Carbon::today())
            ->count();

        // 6. Ambil 5 Jadwal Terdekat
        $jadwalTerdekat = Konsultasi::where('status', 'Disetujui')
            ->where('jadwal_disetujui', '>=', now())
            ->with('siswa')
            ->orderBy('jadwal_disetujui', 'asc')
            ->limit(5)
            ->get();

        // ==========================================
        // BAGIAN 3: PENGIRIMAN DATA KE VIEW
        // ==========================================
        
        return view('admin.dashboard', compact(
           'totalSiswaAktif',
            'totalPelanggaran',
            'siswaPoinTertinggi',
            'pelanggaranTeratas',
            'permintaanBaru',
            'jadwalHariIni',
            'jadwalTerdekat'
        ));
    }
}