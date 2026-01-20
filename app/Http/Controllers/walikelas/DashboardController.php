<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // 1. Ambil Kelas dimana user ini adalah walinya
        $kelasList = Kelas::with(['tingkatan', 'jurusan'])
            ->where('wali_kelas_id', $user->id)
            ->orderBy('nama_kelas')
            ->get();

        // 2. Siapkan variabel untuk statistik
        $kelasData = [];
        $totalSiswa = 0;
        $totalPoinPelanggaran = 0;
        $totalPrestasi = 0;

        // 3. Loop logic untuk menghitung siswa & poin (Kode panjang tadi)
        foreach ($kelasList as $kelas) {
            $siswas = User::where('role', 'siswa')
                ->whereHas('biodataSiswa', function ($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id)->where('status', 'Aktif');
                })
                ->with(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran', 'prestasi'])
                ->orderBy('name')
                ->get();

            $jumlahSiswa = $siswas->count();
            $poinPelanggaran = 0;
            $jumlahPrestasi = 0;

            foreach ($siswas as $siswa) {
                $poinPelanggaran += $siswa->pelanggaranSiswa->sum(fn($item) => $item->pelanggaran->poin ?? 0);
                $jumlahPrestasi += $siswa->prestasi->count();
            }

            $totalSiswa += $jumlahSiswa;
            $totalPoinPelanggaran += $poinPelanggaran;
            $totalPrestasi += $jumlahPrestasi;

            $kelasData[] = [
                'kelas' => $kelas,
                'siswas' => $siswas,
                'jumlah_siswa' => $jumlahSiswa,
                'poin_pelanggaran' => $poinPelanggaran,
                'jumlah_prestasi' => $jumlahPrestasi,
            ];
        }

        // 4. Tampilkan View Khusus Wali (folder resources/views/wali/dashboard.blade.php)
        return view('wali.dashboard', [
            'user' => $user,
            'kelasData' => $kelasData,
            'totalKelas' => $kelasList->count(),
            'totalSiswa' => $totalSiswa,
            'totalPoinPelanggaran' => $totalPoinPelanggaran,
            'totalPrestasi' => $totalPrestasi,
        ]);
    }
}