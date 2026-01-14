<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    /**
     * Tampilkan daftar master pelanggaran.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pelanggarans = Pelanggaran::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_pelanggaran', 'like', "%{$search}%");
            })
            ->orderBy('poin', 'asc') // Urutkan dari poin terkecil
            ->paginate(10)
            ->withQueryString();

        return view('admin.pelanggaran.index', compact('pelanggarans', 'search'));
    }

    /**
     * Simpan data pelanggaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggarans,nama_pelanggaran',
            'poin'             => 'required|integer|min:1',
            'kategori'         => 'required|in:Ringan,Sedang,Berat',
        ]);

        Pelanggaran::create($request->all());

        return redirect()->back()->with('success', 'Data pelanggaran berhasil ditambahkan.');
    }

    /**
     * Update data pelanggaran.
     */
    public function update(Request $request, $id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);

        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggarans,nama_pelanggaran,' . $id,
            'poin'             => 'required|integer|min:1',
            'kategori'         => 'required|in:Ringan,Sedang,Berat',
        ]);

        $pelanggaran->update($request->all());

        return redirect()->back()->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    /**
     * Hapus data pelanggaran.
     */
    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::withCount('pelanggaranSiswa')->findOrFail($id);

        // Cek apakah pelanggaran ini sudah pernah dipakai di data siswa
        if ($pelanggaran->pelanggaran_siswa_count > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Jenis pelanggaran ini sudah tercatat pada data siswa.');
        }

        $pelanggaran->delete();

        return redirect()->back()->with('success', 'Data pelanggaran berhasil dihapus.');
    }
}