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
    // METHOD UNTUK MANAJEMEN MASTER DATA PELANGGARAN
    //======================================================================

    public function index()
    {
        $pelanggaranList = Pelanggaran::latest()->paginate(10);
        return view('admin.pelanggaran.index', compact('pelanggaranList'));
    }

    public function create()
    {
        return view('admin.pelanggaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Diperbaiki: 'pelanggaran' menjadi 'pelanggarans'
        $validated = $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggarans,nama_pelanggaran',
            'poin' => 'required|integer|min:1',
        ]);

        Pelanggaran::create($validated);

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        return view('admin.pelanggaran.edit', compact('pelanggaran'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran): RedirectResponse
    {
        // Diperbaiki: 'pelanggaran' menjadi 'pelanggarans'
        $validated = $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggarans,nama_pelanggaran,' . $pelanggaran->id,
            'poin' => 'required|integer|min:1',
        ]);

        $pelanggaran->update($validated);

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran): RedirectResponse
    {
        $pelanggaran->delete();

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }


    //======================================================================
    // METHOD UNTUK MANAJEMEN CATATAN PELANGGARAN SISWA
    //======================================================================

    public function storeSiswaPelanggaran(Request $request): RedirectResponse
    {
        // Diperbaiki: 'pelanggaran' menjadi 'pelanggarans'
        $validated = $request->validate([
            'siswa_user_id' => 'required|exists:users,id',
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
        
        $validated['pelapor_user_id'] = Auth::id();
        
        PelanggaranSiswa::create($validated);

        $pelanggaran = Pelanggaran::find($validated['pelanggaran_id']);

        Notifikasi::create([
            'user_id' => $validated['siswa_user_id'],
            'judul' => 'Pelanggaran Baru Dicatat',
            'pesan' => 'Sebuah catatan pelanggaran baru telah ditambahkan: "' . $pelanggaran->nama_pelanggaran . '" dengan ' . $pelanggaran->poin . ' poin.',
        ]);

        return redirect()->route('admin.siswa.show', $request->siswa_user_id)
                         ->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }

    public function destroySiswaPelanggaran(PelanggaranSiswa $pelanggaranSiswa): RedirectResponse
    {
        $siswaId = $pelanggaranSiswa->siswa_user_id;
        
        $pelanggaranSiswa->delete();

        return redirect()->route('admin.siswa.show', $siswaId)
                         ->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}
