<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

// Import Model
use App\Models\User;
use App\Models\BiodataSiswa; // <--- PENTING: Untuk filter status Aktif
use App\Models\PelanggaranSiswa;
use App\Models\Pelanggaran;
use App\Models\Konsultasi; 

class DashboardController extends Controller
{
    
   public function index(): View   
{
    // 1. Total Siswa Aktif (Status 'Aktif' di tabel biodata_siswas)
    $totalSiswaAktif = \App\Models\BiodataSiswa::where('status', 'Aktif')->count();
    
    // 2. Total Catatan Pelanggaran (Menghitung baris di tabel pivot)
    $totalPelanggaran = \DB::table('catatan_pelanggaran')->count();

    // 3. Data Siswa Poin Tertinggi (Top 5)
    // Menggunakan selectSub untuk menghitung total poin dari tabel pivot catatan_pelanggaran
    $siswaPoinTertinggi = \App\Models\User::where('role', 'siswa')
        ->select('users.id', 'users.name')
        ->selectSub(function ($query) {
            $query->from('catatan_pelanggaran')
                ->whereColumn('siswa_id', 'users.id')
                ->selectRaw('SUM(poin_saat_itu)');
        }, 'total_poin')
        ->orderByDesc('total_poin')
        ->whereNotNull('id')
        ->limit(5)
        ->get();

    // 4. Data Pelanggaran Teratas (Top 5 Paling Sering Terjadi)
    // Menghitung jumlah kemunculan id pelanggaran di tabel pivot
    $pelanggaranTeratas = \App\Models\Pelanggaran::select('pelanggarans.id', 'pelanggarans.nama_pelanggaran')
        ->selectSub(function ($query) {
            $query->from('catatan_pelanggaran')
                ->whereColumn('pelanggaran_id', 'pelanggarans.id')
                ->selectRaw('count(*)');
        }, 'jumlah_kasus')
        ->orderByDesc('jumlah_kasus')
        ->limit(5)
        ->get();

    // 5. Statistik Konsultasi
    $permintaanBaru = \App\Models\Konsultasi::where('status', 'Menunggu Persetujuan')->count();

    $jadwalHariIni = \App\Models\Konsultasi::where('status', 'Disetujui')
        ->whereDate('jadwal_disetujui', \Carbon\Carbon::today())
        ->count();

    $jadwalTerdekat = \App\Models\Konsultasi::where('status', 'Disetujui')
        ->where('jadwal_disetujui', '>=', now())
        ->with('siswa')
        ->orderBy('jadwal_disetujui', 'asc')
        ->limit(5)
        ->get();

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