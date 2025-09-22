<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranSiswaController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'siswa_user_id' => 'required|exists:users,id',
            'pelanggaran_id' => 'required|exists:pelanggaran,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // 2. Simpan data catatan pelanggaran
        PelanggaranSiswa::create([
            'siswa_user_id' => $request->siswa_user_id,
            'pelanggaran_id' => $request->pelanggaran_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'pelapor_user_id' => Auth::id(), // Mengambil ID admin yang sedang login
        ]);

        // 3. Buat notifikasi untuk siswa
        $pelanggaran = Pelanggaran::find($request->pelanggaran_id);
        Notifikasi::create([
            'user_id' => $request->siswa_user_id,
            'judul' => 'Catatan Pelanggaran Baru',
            'pesan' => 'Anda mendapatkan ' . $pelanggaran->poin . ' poin untuk pelanggaran: ' . $pelanggaran->nama_pelanggaran,
        ]);

        // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }
}