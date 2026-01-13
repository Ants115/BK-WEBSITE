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
use Illuminate\Support\Str; // Tambahkan ini untuk Slug

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

        $kelasList = $query
            ->orderBy('nama_kelas', 'asc')
            ->paginate(12) // Saya ubah jadi 12 biar pas grid 3x4 atau 4x3
            ->withQueryString();

        return view('admin.kelas.index', [
            'kelasList' => $kelasList,
            'search'    => $search,
        ]);
    }

    /**
     * Form tambah kelas.
     */
    public function create(): View
    {
        $tingkatans = Tingkatan::orderBy('id')->get();
        $jurusans   = Jurusan::orderBy('id')->get();

        // Konsisten pakai nama $waliCandidates
        $waliCandidates = User::whereIn('role', ['guru_bk', 'wali_kelas'])
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

        // Otomatis buat slug (nama_unik)
        $data['nama_unik'] = Str::slug($data['nama_kelas']);
        // Default tahun ajaran (bisa diubah nanti jika mau input manual)
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
    public function edit(Kelas $kela) // Laravel binding (param harus sama dengan route list)
    {
        // Fix param binding (kadang laravel baca {kela} bukan {kelas})
        $kelas = $kela;

        $tingkatans = Tingkatan::orderBy('id')->get();
        $jurusans   = Jurusan::orderBy('id')->get();

        $waliCandidates = User::whereIn('role', ['guru_bk', 'wali_kelas'])
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

        // Update slug jika nama berubah
        $data['nama_unik'] = Str::slug($data['nama_kelas']);

        $kelas->update($data);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas.
     */
    public function destroy($id): RedirectResponse
    {
        // Cek relasi ke siswa (biodataSiswa atau biodataSiswas sesuai model kamu)
        // Pastikan nama relasi di Model Kelas.php adalah 'biodataSiswa'
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
}