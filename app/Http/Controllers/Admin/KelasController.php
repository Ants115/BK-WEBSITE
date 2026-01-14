<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Tingkatan;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    /**
     * Daftar kelas.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Kelas::with(['tingkatan', 'jurusan', 'waliKelas']);

        if (!empty($search)) {
            $query->where('nama_kelas', 'like', "%{$search}%");
        }

        // PERBAIKAN: Ubah nama variabel jadi $kelas (bukan $kelasList)
        // Agar sesuai dengan View index.blade.php
        $kelas = $query
            ->orderBy('nama_kelas', 'asc')
            ->paginate(12)
            ->withQueryString();

        return view('admin.kelas.index', [
            'kelas'  => $kelas, // <--- Ini yang bikin error tadi
            'search' => $search,
        ]);
    }

    /**
     * Form tambah kelas.
     */
    public function create(): View
    {
        $tingkatans = Tingkatan::orderBy('id')->get();
        $jurusans   = Jurusan::orderBy('id')->get();

        $waliCandidates = User::whereIn('role', ['guru_bk', 'wali_kelas', 'staf_guru']) // Tambahkan staf_guru jika perlu
            ->orderBy('name')
            ->get();

        return view('admin.kelas.create', compact('tingkatans', 'jurusans', 'waliCandidates'));
    }

    /**
     * Simpan data kelas baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_kelas'    => ['required', 'string', 'max:100'],
            'tingkatan_id'  => ['required', 'integer'],
            'jurusan_id'    => ['required', 'integer'],
            'wali_kelas_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $data['nama_unik'] = Str::slug($data['nama_kelas']);
        $data['tahun_ajaran'] = '2025/2026';

        Kelas::create($data);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Redirect show -> edit.
     */
    public function show($id): RedirectResponse
    {
        return redirect()->route('admin.kelas.edit', $id);
    }

    /**
     * Form edit kelas.
     */
    public function edit(Kelas $kela) // Pastikan parameter di route list sesuai
    {
        // Jika route parameter kamu {kela}, maka variabel ini $kela
        // Kita ubah jadi $kelas biar enak dipakai di view
        $kelas = $kela;

        $tingkatans = Tingkatan::orderBy('id')->get();
        $jurusans   = Jurusan::orderBy('id')->get();

        $waliCandidates = User::whereIn('role', ['guru_bk', 'wali_kelas', 'staf_guru'])
            ->orderBy('name')
            ->get();

        return view('admin.kelas.edit', compact('kelas', 'tingkatans', 'jurusans', 'waliCandidates'));
    }

    /**
     * Update data kelas.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $kelas = Kelas::findOrFail($id);

        $data = $request->validate([
            'nama_kelas'    => ['required', 'string', 'max:100'],
            'tingkatan_id'  => ['required', 'integer'],
            'jurusan_id'    => ['required', 'integer'],
            'wali_kelas_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $data['nama_unik'] = Str::slug($data['nama_kelas']);

        $kelas->update($data);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Hapus satu kelas.
     */
    public function destroy($id): RedirectResponse
    {
        $kelas = Kelas::withCount('biodataSiswa')->findOrFail($id);

        if ($kelas->biodata_siswa_count > 0) {
            return redirect()
                ->route('admin.kelas.index')
                ->with('error', 'Gagal hapus! Kelas ini masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }

    /**
     * Hapus banyak kelas sekaligus (Bulk Delete).
     */
    public function destroyMultiple(Request $request): RedirectResponse
    {
        $ids = $request->input('ids'); 

        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Tidak ada kelas yang dipilih.');
        }

        $kelasAdaSiswa = Kelas::whereIn('id', $ids)
            ->whereHas('biodataSiswa') 
            ->count();

        if ($kelasAdaSiswa > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Beberapa kelas yang dipilih masih memiliki siswa.');
        }

        Kelas::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' kelas berhasil dihapus.');
    }
}