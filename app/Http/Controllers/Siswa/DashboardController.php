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
    $user = Auth::user();

    // Pastikan data terbaru di-load
    $user->load(['biodataSiswa.kelas.waliKelas']);

    // 1. POIN: Hitung langsung ke tabel pivot (catatan_pelanggaran)
    $totalPoin = \DB::table('catatan_pelanggaran')
        ->where('siswa_id', $user->id)
        ->sum('poin_saat_itu');

    // 2. PRESTASI: Hitung langsung ke tabel prestasi
    $totalPrestasi = \App\Models\Prestasi::where('siswa_id', $user->id)->count();

    // 3. KONSULTASI: Hitung langsung ke tabel konsultasi
    $totalPengajuan = \App\Models\Konsultasi::where('siswa_id', $user->id)->count();

    // 4. Jadwal Mendatang
    $konsultasiMendatang = \App\Models\Konsultasi::where('siswa_id', $user->id)
        ->where('status', 'Disetujui')
        ->where('jadwal_disetujui', '>=', now())
        ->orderBy('jadwal_disetujui', 'asc')
        ->first();

    return view('dashboard', compact(
        'user',
        'totalPoin',
        'konsultasiMendatang',
        'totalPengajuan',
        'totalPrestasi',
    ));
}
}
