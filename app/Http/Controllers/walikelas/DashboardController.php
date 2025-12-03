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
        $user = Auth::user(); // sudah pasti wali_kelas karena lewat middleware

        $kelasList = Kelas::with(['tingkatan', 'jurusan'])
            ->where('wali_kelas_id', $user->id)
            ->orderBy('nama_kelas')
            ->get();

        $kelasData = [];
        $totalSiswa = 0;
        $totalPoinPelanggaran = 0;
        $totalPrestasi = 0;

        foreach ($kelasList as $kelas) {
            $siswas = User::where('role', 'siswa')
                ->whereHas('biodataSiswa', function ($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id)
                      ->where('status', 'Aktif');
                })
                ->with(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran', 'prestasi'])
                ->orderBy('name')
                ->get();

            $jumlahSiswa = $siswas->count();
            $poinPelanggaran = 0;
            $jumlahPrestasi = 0;

            foreach ($siswas as $siswa) {
                $poinPelanggaran += $siswa->pelanggaranSiswa->sum(function ($item) {
                    return $item->pelanggaran->poin ?? 0;
                });

                $jumlahPrestasi += $siswa->prestasi->count();
            }

            $totalSiswa += $jumlahSiswa;
            $totalPoinPelanggaran += $poinPelanggaran;
            $totalPrestasi += $jumlahPrestasi;

            $kelasData[] = [
                'kelas'            => $kelas,
                'siswas'           => $siswas,
                'jumlah_siswa'     => $jumlahSiswa,
                'poin_pelanggaran' => $poinPelanggaran,
                'jumlah_prestasi'  => $jumlahPrestasi,
            ];
        }

        return view('wali.dashboard', [
            'user'                 => $user,
            'kelasData'            => $kelasData,
            'totalKelas'           => $kelasList->count(),
            'totalSiswa'           => $totalSiswa,
            'totalPoinPelanggaran' => $totalPoinPelanggaran,
            'totalPrestasi'        => $totalPrestasi,
        ]);
    }
}
