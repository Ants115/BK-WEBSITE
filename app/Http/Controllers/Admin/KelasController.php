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

class KelasController extends Controller
{
    /**
     * Daftar kelas.
     * Menampilkan nama kelas, tingkatan, jurusan, wali kelas, dan jumlah siswa.
     */
    public function index(Request $request)
    {
        // Ambil keyword pencarian dari query string
        $search = $request->query('search');

        // Query dasar: load relasi yang dipakai di view
        $query = Kelas::with([
            'tingkatan',
            'jurusan',
            'waliKelas',      // aman walaupun null
        ]);

        // Kalau ada keyword, filter berdasarkan nama_kelas
        if (!empty($search)) {
            $query->where('nama_kelas', 'like', "%{$search}%");
        }

        // Paginate + keep query string (supaya ?search= tetap ada saat pindah halaman)
        $kelasList = $query
            ->orderBy('nama_kelas', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Kirim ke view dengan nama variabel yang DIPAKAI di Blade
        return view('admin.kelas.index', [
            'kelasList' => $kelasList,
            'search'    => $search,
        ]);
    }

    /**
     * Form tambah kelas.
     * Admin bisa langsung memilih wali kelas (opsional).
     */
    public function create(): View
    {
        $tingkatans = Tingkatan::orderBy('id')->get();
        $jurusans   = Jurusan::orderBy('id')->get();

        // Kandidat wali kelas: user dengan role guru_bk atau wali_kelas
        $waliOptions = User::whereIn('role', ['guru_bk', 'wali_kelas'])
            ->orderBy('name')
            ->get();

        return view('admin.kelas.create', compact('tingkatans', 'jurusans', 'waliOptions'));
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

        Kelas::create($data);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Redirect show -> edit agar tidak perlu view terpisah.
     */
    public function show($id): RedirectResponse
    {
        return redirect()->route('admin.kelas.edit', $id);
    }

    /**
     * Form edit kelas (termasuk ganti wali kelas).
     */
    public function edit(Kelas $kela)
    {
        // Laravel kasih param {kela}, jadi kita normalkan jadi $kelas
        $kelas = $kela;

        // Ambil semua tingkatan & jurusan untuk dropdown
        $tingkatans = Tingkatan::orderBy('id')->get();
        $jurusans   = Jurusan::orderBy('id')->get();

        // Kandidat wali kelas: guru BK + wali_kelas
        $waliCandidates = User::whereIn('role', ['guru_bk', 'wali_kelas'])
            ->orderBy('name')
            ->get();

        // Kirim ke view dengan nama variabel yang DIPAKAI di Blade
        return view('admin.kelas.edit', [
            'kelas'          => $kelas,
            'tingkatans'     => $tingkatans,
            'jurusans'       => $jurusans,
            'waliCandidates' => $waliCandidates,
        ]);
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

        $kelas->update($data);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas (opsional: dicek dulu apakah masih punya siswa).
     */
    public function destroy($id): RedirectResponse
    {
        $kelas = Kelas::withCount('biodataSiswas')->findOrFail($id);

        if ($kelas->biodata_siswas_count > 0) {
            return redirect()
                ->route('admin.kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
