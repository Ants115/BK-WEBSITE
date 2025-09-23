<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodataSiswa;
use Illuminate\Http\Request;

class ArsipAlumniController extends Controller
{
    /**
     * Menampilkan daftar tahun kelulusan (angkatan).
     */
    public function index()
    {
        // Ambil semua tahun lulus yang unik dari database
        $tahunLulusList = BiodataSiswa::where('status', 'Lulus')
                                      ->select('tahun_lulus')
                                      ->distinct()
                                      ->orderBy('tahun_lulus', 'desc')
                                      ->pluck('tahun_lulus');

        return view('admin.arsip.index', compact('tahunLulusList'));
    }

    /**
     * Menampilkan daftar siswa yang lulus pada tahun tertentu.
     */
    public function show($tahun_lulus)
{
    // Ganti underscore dengan slash untuk query database
    $tahunAjaran = str_replace('_', '/', $tahun_lulus);

    // Ambil semua data alumni dari tahun ajaran tersebut
    $alumniList = BiodataSiswa::where('status', 'Lulus')
                              ->where('tahun_lulus', $tahunAjaran)
                              ->with('user', 'kelas') // Load relasi yang dibutuhkan
                              ->get();

    // KELOMPOKKAN HASILNYA BERDASARKAN NAMA KELAS
    $alumniGroupedByKelas = $alumniList->groupBy('kelas.nama_kelas');

    return view('admin.arsip.show', compact('alumniGroupedByKelas', 'tahunAjaran'));
}
}