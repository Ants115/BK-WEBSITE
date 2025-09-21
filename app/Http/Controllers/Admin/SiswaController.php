<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PelanggaranSiswa;
use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        // Ambil semua user. Untuk saat ini, kita anggap semua user adalah siswa.
        // Kita memuat relasi biodataSiswa dan juga relasi kelas di dalamnya.
        $siswas = User::where('role', 'siswa')->with('biodataSiswa.kelas')->get();

        // Kirim data ke view
        return view('admin.siswa.index', ['siswas' => $siswas]);
    }

    public function show($id)
    {
        // Cari siswa berdasarkan ID beserta relasi biodata, kelas, dan pelanggarannya.
        $siswa = User::with(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran'])
                     ->findOrFail($id);

        // Ambil semua jenis pelanggaran untuk form input
        $masterPelanggaran = Pelanggaran::all();

        // Hitung total poin pelanggaran siswa
        $totalPoin = $siswa->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });

        return view('admin.siswa.show', [
            'siswa' => $siswa,
            'masterPelanggaran' => $masterPelanggaran,
            'totalPoin' => $totalPoin,
        ]);
    }

    public function cetakSurat($id, $jenis)
{
    $siswa = User::with(['biodataSiswa.kelas.jurusan', 'biodataSiswa.kelas.tingkatan'])->findOrFail($id);
    $tanggal = now()->translatedFormat('d F Y'); // Format tanggal Indonesia

    // Buat view baru yang bersih khusus untuk dicetak
    return view('admin.surat.template', [
        'siswa' => $siswa,
        'jenis' => $jenis,
        'tanggal' => $tanggal,
    ]);
}

    /**
     * Menyimpan catatan pelanggaran baru untuk seorang siswa.
     */
    public function storePelanggaran(Request $request, User $siswa)
    {
        $request->validate([
            'pelanggaran_id' => 'required|exists:pelanggaran,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        PelanggaranSiswa::create([
            'siswa_user_id' => $siswa->id,
            'pelanggaran_id' => $request->pelanggaran_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'pelapor_user_id' => auth()->id(), // Ambil ID admin yg sedang login
        ]);

        return back()->with('success', 'Catatan pelanggaran berhasil ditambahkan.');
    }
}