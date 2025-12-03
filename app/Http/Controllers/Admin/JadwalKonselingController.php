<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKonseling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JadwalKonselingController extends Controller
{
    /**
     * Daftar jadwal konsultasi.
     */
    public function index(Request $request): View
    {
        $guruId = $request->query('guru_id');

        $gurus = User::where('role', 'guru_bk')->orderBy('name')->get();

        $query = JadwalKonseling::with('guru')->orderBy('tanggal')->orderBy('jam_mulai');

        if ($guruId) {
            $query->where('guru_id', $guruId);
        }

        $jadwals = $query->paginate(20);

        return view('admin.jadwal-konseling.index', compact('jadwals', 'gurus', 'guruId'));
    }

    /**
     * Form tambah jadwal.
     */
    public function create(): View
    {
        $gurus = User::where('role', 'guru_bk')->orderBy('name')->get();

        return view('admin.jadwal-konseling.create', compact('gurus'));
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guru_id'    => ['required', 'exists:users,id'],
            'tanggal'    => ['nullable', 'date'],
            'hari'       => ['nullable', 'string', 'max:50'],
            'jam_mulai'  => ['required'],
            'jam_selesai'=> ['nullable'],
            'lokasi'     => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Kalau tanggal diisi, hari boleh dikosongkan. Atau sebaliknya. Bebas.
        JadwalKonseling::create($validated);

        return redirect()
            ->route('admin.jadwal-konseling.index')
            ->with('success', 'Jadwal konsultasi berhasil ditambahkan.');
    }

    /**
     * Form edit jadwal.
     */
    public function edit(JadwalKonseling $jadwalKonseling): View
    {
        $gurus = User::where('role', 'guru_bk')->orderBy('name')->get();

        return view('admin.jadwal-konseling.edit', [
            'jadwal' => $jadwalKonseling,
            'gurus'  => $gurus,
        ]);
    }

    /**
     * Update jadwal.
     */
    public function update(Request $request, JadwalKonseling $jadwalKonseling): RedirectResponse
    {
        $validated = $request->validate([
            'guru_id'    => ['required', 'exists:users,id'],
            'tanggal'    => ['nullable', 'date'],
            'hari'       => ['nullable', 'string', 'max:50'],
            'jam_mulai'  => ['required'],
            'jam_selesai'=> ['nullable'],
            'lokasi'     => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $jadwalKonseling->update($validated);

        return redirect()
            ->route('admin.jadwal-konseling.index')
            ->with('success', 'Jadwal konsultasi berhasil diperbarui.');
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(JadwalKonseling $jadwalKonseling): RedirectResponse
    {
        $jadwalKonseling->delete();

        return redirect()
            ->route('admin.jadwal-konseling.index')
            ->with('success', 'Jadwal konsultasi berhasil dihapus.');
    }
}
