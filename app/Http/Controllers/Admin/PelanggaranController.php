<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelanggaranController extends Controller
{
    /**
     * Menampilkan daftar semua data master pelanggaran.
     */
    public function index()
    {
        $pelanggaranList = Pelanggaran::all();
        return view('admin.pelanggaran.index', ['pelanggaranList' => $pelanggaranList]);
    }

    /**
     * Menampilkan form untuk membuat data pelanggaran baru.
     */
    public function create()
    {
        return view('admin.pelanggaran.create');
    }

    /**
     * Menyimpan data pelanggaran baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255|unique:pelanggaran,nama_pelanggaran',
            'poin' => 'required|integer|min:1',
        ]);

        Pelanggaran::create($request->all());

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Data pelanggaran baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pelanggaran.
     */
    public function edit(Pelanggaran $pelanggaran)
    {
        // Laravel secara otomatis akan mencari data Pelanggaran berdasarkan ID dari URL
        return view('admin.pelanggaran.edit', ['pelanggaran' => $pelanggaran]);
    }

    /**
     * Memperbarui data pelanggaran di database.
     */
    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $request->validate([
            'nama_pelanggaran' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pelanggaran')->ignore($pelanggaran->id),
            ],
            'poin' => 'required|integer|min:1',
        ]);

        $pelanggaran->update($request->all());

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    /**
     * Menghapus data pelanggaran dari database.
     */
    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();

        return redirect()->route('admin.pelanggaran.index')
                         ->with('success', 'Data pelanggaran berhasil dihapus.');
    }
}
