<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    //======================================================================
    // 1. MANAJEMEN MASTER DATA PELANGGARAN (CRUD JENIS PELANGGARAN)
    //======================================================================

    /**
     * Menampilkan daftar master pelanggaran dengan fitur Pencarian.
     */
    public function index(Request $request)
    {
        // Fitur Baru: Ambil search dari URL
        $search = $request->query('search');

        // Query dengan filter pencarian + sorting poin
        $pelanggarans = Pelanggaran::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_pelanggaran', 'like', "%{$search}%");
            })
            ->orderBy('poin', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Mengirim variabel $pelanggarans dan $search ke view
        // Perhatikan: View kamu mungkin bernama 'admin.pelanggaran.index'
        return view('admin.pelanggaran.index', compact('pelanggarans', 'search'));
    }

    /**
     * Simpan Master Pelanggaran Baru (Dengan Kategori).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggarans,nama_pelanggaran',
            'poin'             => 'required|integer|min:1',
            'kategori'         => 'required|in:Ringan,Sedang,Berat', // Fitur Baru
        ]);

        Pelanggaran::create($request->all());

        return redirect()->back()->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    /**
     * Update Master Pelanggaran (Dengan Kategori).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $pelanggaran = Pelanggaran::findOrFail($id);

        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggarans,nama_pelanggaran,' . $id,
            'poin'             => 'required|integer|min:1',
            'kategori'         => 'required|in:Ringan,Sedang,Berat', // Fitur Baru
        ]);

        $pelanggaran->update($request->all());

        return redirect()->back()->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    /**
     * Hapus Master Pelanggaran (Dengan Pengecekan Relasi).
     */
    public function destroy($id): RedirectResponse
    {
        // Cek dulu apakah pelanggaran ini sudah pernah dipakai siswa
        $pelanggaran = Pelanggaran::withCount('pelanggaranSiswa')->findOrFail($id);

        if ($pelanggaran->pelanggaran_siswa_count > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Jenis pelanggaran ini sudah tercatat pada data siswa.');
        }

        $pelanggaran->delete();

        return redirect()->back()->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }


    //======================================================================
    // 2. MANAJEMEN PENCATATAN PELANGGARAN KE SISWA (TRANSAKSI)
    //======================================================================

    /**
     * Mencatat pelanggaran yang dilakukan siswa.
     */
    public function storeSiswaPelanggaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_user_id'  => 'required|exists:users,id',
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'tanggal'        => 'required|date',
            'keterangan'     => 'nullable|string',
        ]);
        
        // Otomatis isi pelapor dengan user yang login (Guru BK/Admin)
        $validated['pelapor_user_id'] = Auth::id();
        
        // Simpan ke tabel pelanggaran_siswas
        PelanggaranSiswa::create($validated);

        // Ambil data pelanggaran untuk pesan notifikasi
        $pelanggaran = Pelanggaran::find($validated['pelanggaran_id']);

        // Buat Notifikasi untuk Siswa
        Notifikasi::create([
            'user_id' => $validated['siswa_user_id'],
            'judul'   => 'Pelanggaran Baru Dicatat',
            'pesan'   => 'Pelanggaran tercatat: "' . $pelanggaran->nama_pelanggaran . '" (' . $pelanggaran->poin . ' poin).',
        ]);

        // Redirect kembali ke halaman detail siswa
        return redirect()->route('admin.siswa.show', $request->siswa_user_id)
                         ->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }

    /**
     * Menghapus catatan pelanggaran siswa (misal salah input).
     */
    public function destroySiswaPelanggaran($id): RedirectResponse
    {
        // Kita pakai ID langsung agar lebih aman
        $pelanggaranSiswa = PelanggaranSiswa::findOrFail($id);
        
        $siswaId = $pelanggaranSiswa->siswa_user_id;
        
        $pelanggaranSiswa->delete();

        return redirect()->route('admin.siswa.show', $siswaId)
                         ->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}