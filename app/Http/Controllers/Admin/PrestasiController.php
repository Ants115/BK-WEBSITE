<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    /**
     * Daftar semua prestasi (dengan filter lengkap).
     */
    public function index(Request $request): View
    {
        $query = Prestasi::with(['siswa.biodataSiswa.kelas'])
            ->orderBy('tanggal', 'desc');

        $search    = $request->query('search');
        $tingkat   = $request->query('tingkat');
        $kategori  = $request->query('kategori');
        $kelasId   = $request->query('kelas_id');
        $tahun     = $request->query('tahun');

        // Filter search: judul prestasi / nama siswa
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter tingkat
        if ($tingkat) {
            $query->where('tingkat', $tingkat);
        }

        // Filter kategori
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Filter kelas (via biodata_siswa.kelas_id)
        if ($kelasId) {
            $query->whereHas('siswa.biodataSiswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        // Filter tahun (berdasarkan kolom tanggal)
        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $prestasis = $query->paginate(15)->withQueryString();

        // Data untuk dropdown
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $tahunOptions = Prestasi::selectRaw('YEAR(tanggal) as tahun')
            ->whereNotNull('tanggal')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        return view('admin.prestasi.index', [
            'prestasis'       => $prestasis,
            'search'          => $search,
            'tingkat'         => $tingkat,
            'kategori'        => $kategori,
            'kelasId'         => $kelasId,
            'tahun'           => $tahun,
            'tingkatOptions'  => Prestasi::TINGKAT_OPTIONS,
            'kategoriOptions' => Prestasi::KATEGORI_OPTIONS,
            'kelasList'       => $kelasList,
            'tahunOptions'    => $tahunOptions,
        ]);
    }

    /**
     * Halaman rekap prestasi (ringkasan per kelas / tingkat / kategori).
     */
    public function rekap(Request $request): View
    {
        $tahun = $request->query('tahun');

        // Query dasar
        $base = Prestasi::query();

        if ($tahun) {
            $base->whereYear('tanggal', $tahun);
        }

        // Ringkasan umum
        $totalPrestasi = (clone $base)->count();

        $totalSiswa = (clone $base)
            ->distinct('siswa_id')
            ->count('siswa_id');

        // Rekap per kelas
        $rekapPerKelas = (clone $base)
            ->join('users', 'prestasi.siswa_id', '=', 'users.id')
            ->join('biodata_siswa', 'biodata_siswa.user_id', '=', 'users.id')
            ->join('kelas', 'kelas.id', '=', 'biodata_siswa.kelas_id')
            ->selectRaw('
                kelas.id as kelas_id,
                kelas.nama_kelas,
                COUNT(prestasi.id) as total_prestasi,
                COUNT(DISTINCT prestasi.siswa_id) as total_siswa
            ')
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->get();

        // Rekap per tingkat
        $rekapPerTingkat = (clone $base)
            ->selectRaw('tingkat, COUNT(*) as total_prestasi')
            ->groupBy('tingkat')
            ->orderBy('tingkat')
            ->get();

        // Rekap per kategori
        $rekapPerKategori = (clone $base)
            ->selectRaw('kategori, COUNT(*) as total_prestasi')
            ->groupBy('kategori')
            ->orderBy('kategori')
            ->get();

        // Pilihan tahun di dropdown
        $tahunOptions = Prestasi::selectRaw('YEAR(tanggal) as tahun')
            ->whereNotNull('tanggal')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        return view('admin.prestasi.rekap', [
            'tahun'            => $tahun,
            'tahunOptions'     => $tahunOptions,
            'totalPrestasi'    => $totalPrestasi,
            'totalSiswa'       => $totalSiswa,
            'rekapPerKelas'    => $rekapPerKelas,
            'rekapPerTingkat'  => $rekapPerTingkat,
            'rekapPerKategori' => $rekapPerKategori,
        ]);
    }

    /**
     * Form tambah prestasi.
     */
    public function create(): View
    {
        $siswas = User::where('role', 'siswa')
            ->with('biodataSiswa.kelas')
            ->orderBy('name')
            ->get();

        return view('admin.prestasi.create', [
            'siswas'          => $siswas,
            'tingkatOptions'  => Prestasi::TINGKAT_OPTIONS,
            'kategoriOptions' => Prestasi::KATEGORI_OPTIONS,
        ]);
    }

    /**
     * Simpan prestasi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id'   => 'required|exists:users,id',
            'judul'      => 'required|string|max:255',
            'tingkat'    => 'required|string|max:100',
            'kategori'   => 'required|string|max:100',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Prestasi::create($validated);

        return redirect()
            ->route('admin.prestasi.index')
            ->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    /**
     * Form edit prestasi.
     */
    public function edit(Prestasi $prestasi): View
    {
        $siswas = User::where('role', 'siswa')
            ->with('biodataSiswa.kelas')
            ->orderBy('name')
            ->get();

        return view('admin.prestasi.edit', [
            'prestasi'        => $prestasi,
            'siswas'          => $siswas,
            'tingkatOptions'  => Prestasi::TINGKAT_OPTIONS,
            'kategoriOptions' => Prestasi::KATEGORI_OPTIONS,
        ]);
    }

    /**
     * Update data prestasi.
     */
    public function update(Request $request, Prestasi $prestasi): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id'   => 'required|exists:users,id',
            'judul'      => 'required|string|max:255',
            'tingkat'    => 'required|string|max:100',
            'kategori'   => 'required|string|max:100',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $prestasi->update($validated);

        return redirect()
            ->route('admin.prestasi.index')
            ->with('success', 'Data prestasi berhasil diperbarui.');
    }

    /**
     * Hapus prestasi.
     */
    public function destroy(Prestasi $prestasi): RedirectResponse
    {
        $prestasi->delete();

        return redirect()
            ->route('admin.prestasi.index')
            ->with('success', 'Data prestasi berhasil dihapus.');
    }
}
