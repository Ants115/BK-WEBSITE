<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Notifikasi; // <-- 1. Tambahkan import untuk Notifikasi
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    //======================================================================
    // METHOD UNTUK MANAJEMEN MASTER DATA PELANGGARAN
    //======================================================================

    /**
     * Menampilkan daftar semua jenis pelanggaran.
     */
    public function index()
    {
        $pelanggaranList = Pelanggaran::latest()->paginate(10);
        return view('admin.pelanggaran.index', compact('pelanggaranList'));
    }

    /**
     * Menampilkan form untuk membuat jenis pelanggaran baru.
     */
    public function create()
    {
        return view('admin.pelanggaran.create');
    }

    /**
     * Menyimpan jenis pelanggaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggaran,nama_pelanggaran',
            'poin' => 'required|integer|min:1',
        ]);

        Pelanggaran::create($validated);

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jenis pelanggaran.
     */
    public function edit(Pelanggaran $pelanggaran)
    {
        return view('admin.pelanggaran.edit', compact('pelanggaran'));
    }

    /**
     * Memperbarui data jenis pelanggaran di database.
     */
    public function update(Request $request, Pelanggaran $pelanggaran): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggaran,nama_pelanggaran,' . $pelanggaran->id,
            'poin' => 'required|integer|min:1',
        ]);

        $pelanggaran->update($validated);

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    /**
     * Menghapus data jenis pelanggaran dari database.
     */
    public function destroy(Pelanggaran $pelanggaran): RedirectResponse
    {
        $pelanggaran->delete();

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }


    //======================================================================
    // METHOD UNTUK MANAJEMEN CATATAN PELANGGARAN SISWA
    //======================================================================

    /**
     * Menyimpan CATATAN PELANGGARAN SISWA baru.
     */
    public function storeSiswaPelanggaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_user_id' => 'required|exists:users,id',
            'pelanggaran_id' => 'required|exists:pelanggaran,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
        
        $validated['pelapor_user_id'] = Auth::id();
        
        PelanggaranSiswa::create($validated);

        // --- 👇 LOGIKA NOTIFIKASI OTOMATIS DITAMBAHKAN DI SINI 👇 ---

        // Ambil detail pelanggaran untuk isi notifikasi
        $pelanggaran = Pelanggaran::find($validated['pelanggaran_id']);

        // Buat notifikasi baru untuk siswa
        Notifikasi::create([
            'user_id' => $validated['siswa_user_id'],
            'judul' => 'Pelanggaran Baru Dicatat',
            'pesan' => 'Sebuah catatan pelanggaran baru telah ditambahkan: "' . $pelanggaran->nama_pelanggaran . '" dengan ' . $pelanggaran->poin . ' poin.',
        ]);

        // --- AKHIR DARI LOGIKA NOTIFIKASI ---

        return redirect()->route('admin.siswa.show', $request->siswa_user_id)
                         ->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }

    /**
     * Menghapus CATATAN PELANGGARAN SISWA.
     */
    public function destroySiswaPelanggaran(PelanggaranSiswa $pelanggaranSiswa): RedirectResponse
    {
        $siswaId = $pelanggaranSiswa->siswa_user_id;
        
        $pelanggaranSiswa->delete();

        return redirect()->route('admin.siswa.show', $siswaId)
                         ->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}