<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BiodataSiswa; // Pastikan model ini di-use
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArsipAlumniController extends Controller
{
    /**
     * Menampilkan daftar tahun angkatan (Index).
     */
    public function index(): View
    {
        // Ambil tahun lulus unik dari biodata siswa
        // Asumsi: Siswa lulus ditandai dengan status 'Lulus' atau 'Alumni'
        $angkatan = BiodataSiswa::select('tahun_lulus')
            ->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->get();

        return view('admin.arsip.index', compact('angkatan'));
    }

    /**
     * Menampilkan detail alumni per tahun (Group by Kelas).
     */
    public function show($tahun_lulus): View
    {
        // KEMBALIKAN FORMAT URL: Ubah '-' kembali menjadi '/'
        // Contoh: dari "2026-2027" menjadi "2026/2027" agar ketemu di database
        $tahun_asli = str_replace('-', '/', $tahun_lulus);

        // Ambil siswa yang lulus pada tahun tersebut
        $alumni = User::whereHas('biodataSiswa', function ($q) use ($tahun_asli) {
            $q->where('tahun_lulus', $tahun_asli);
        })
        ->with(['biodataSiswa.kelas.jurusan']) 
        ->get();

        // Kelompokkan data berdasarkan Nama Kelas
        $alumniPerKelas = $alumni->groupBy(function ($item) {
            return $item->biodataSiswa->kelas->nama_kelas ?? 'Tanpa Kelas';
        });

        // Hitung total statistik
        $totalSiswa = $alumni->count();
        $totalKelas = $alumniPerKelas->count();

        // Kirim variabel $tahun_lulus (yang asli) ke View
        return view('admin.arsip.show', [
            'alumniPerKelas' => $alumniPerKelas,
            'tahun_lulus'    => $tahun_asli, // Kirim tahun yang formatnya benar (pakai /)
            'totalSiswa'     => $totalSiswa,
            'totalKelas'     => $totalKelas
        ]);
    }
}