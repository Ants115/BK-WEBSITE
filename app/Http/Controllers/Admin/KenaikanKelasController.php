<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\BiodataSiswa;

class KenaikanKelasController extends Controller
{
    /**
     * Menampilkan halaman proses kenaikan kelas secara dinamis.
     * Hanya kelas yang memiliki siswa aktif yang akan ditampilkan di halaman.
     */
    public function index()
    {
        // Query ini sekarang akan berjalan dengan benar
        $idKelasYangAdaSiswa = BiodataSiswa::whereNotNull('kelas_id')
                                          ->where('status', 'Aktif')
                                          ->distinct()
                                          ->pluck('kelas_id');
        
        // (Asumsi status siswa aktif adalah 'Aktif', Anda juga bisa menggunakan ini:)
        // ->where('status', 'Aktif')

        // 2. Ambil detail kelas berdasarkan ID yang aktif tersebut
        $kelas = Kelas::with('tingkatan')
                      ->whereIn('id', $idKelasYangAdaSiswa)
                      ->orderBy('tingkatan_id')
                      ->get();
        
        $kelasGrouped = $kelas->groupBy('tingkatan.nama_tingkatan');

        $daftarKelasTujuan = Kelas::orderBy('nama_kelas')->get();

        return view('admin.kenaikan-kelas.index', compact('kelasGrouped', 'daftarKelasTujuan'));
    }

    /**
     * Memproses data dari form kenaikan kelas.
     * Tidak ada perubahan di sini, logika Anda sudah benar.
     */
    public function proses(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'promosi' => 'required|array',
        ]);

        $pemetaan = $request->input('promosi');
        $tahunAjaranLulus = date('Y') . '/' . (date('Y') + 1); // Contoh: 2025/2026

        // 2. Loop melalui setiap pemetaan dari form
        foreach ($pemetaan as $kelasAsalId => $tujuan) {
            
            // Lewati jika admin memilih "-- Jangan Ubah --"
            if (empty($tujuan)) {
                continue;
            }

            // 3. Logika untuk meluluskan siswa
            if ($tujuan === 'lulus') {
                BiodataSiswa::where('kelas_id', $kelasAsalId)
                            ->update([
                                'status' => 'Lulus',
                                'tahun_lulus' => $tahunAjaranLulus,
                            ]);
            } 
            // 4. Logika untuk menaikkan kelas siswa
            else {
                $kelasTujuanId = $tujuan;
                BiodataSiswa::where('kelas_id', $kelasAsalId)
                            ->update(['kelas_id' => $kelasTujuanId]);
            }
        }

        // 5. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('admin.kenaikan-kelas.index')
                         ->with('success', 'Proses kenaikan kelas dan kelulusan berhasil dijalankan.');
    }
}
