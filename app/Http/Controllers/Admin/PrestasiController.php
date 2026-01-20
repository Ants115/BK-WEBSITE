<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Notifikasi; // Tambahan untuk notifikasi
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Tambahan untuk Auth

class PrestasiController extends Controller
{
    /**
     * Daftar semua prestasi (Menu Sidebar - dengan filter lengkap).
     */
    public function index(Request $request): View
    {
        $query = Prestasi::with(['siswa.biodataSiswa.kelas'])
            ->orderBy('tanggal', 'desc');

        $search   = $request->query('search');
        $tingkat  = $request->query('tingkat');
        $kategori = $request->query('kategori');
        $kelasId  = $request->query('kelas_id');
        $tahun    = $request->query('tahun');

        // Filter search: judul prestasi / nama siswa
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($tingkat)  $query->where('tingkat', $tingkat);
        if ($kategori) $query->where('kategori', $kategori);
        if ($kelasId) {
            $query->whereHas('siswa.biodataSiswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }
        if ($tahun) $query->whereYear('tanggal', $tahun);

        $prestasis = $query->paginate(15)->withQueryString();

        // Data untuk dropdown filter
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
     * Halaman rekap prestasi (Menu Sidebar - Statistik).
     */
    public function rekap(Request $request): View
    {
        $tahun = $request->query('tahun');
        $base = Prestasi::query();

        if ($tahun) $base->whereYear('tanggal', $tahun);

        $totalPrestasi = (clone $base)->count();
        $totalSiswa = (clone $base)->distinct('siswa_id')->count('siswa_id');

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

        $rekapPerTingkat = (clone $base)->selectRaw('tingkat, COUNT(*) as total_prestasi')->groupBy('tingkat')->orderBy('tingkat')->get();
        $rekapPerKategori = (clone $base)->selectRaw('kategori, COUNT(*) as total_prestasi')->groupBy('kategori')->orderBy('kategori')->get();

        $tahunOptions = Prestasi::selectRaw('YEAR(tanggal) as tahun')
            ->whereNotNull('tanggal')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        return view('admin.prestasi.rekap', compact(
            'tahun', 'tahunOptions', 'totalPrestasi', 'totalSiswa', 
            'rekapPerKelas', 'rekapPerTingkat', 'rekapPerKategori'
        ));
    }

    // ================================================================
    // CRUD STANDAR (Biasanya dipakai di menu Prestasi Siswa sidebar)
    // ================================================================

    public function create(): View
{
    // Gunakan whereHas untuk filter berdasarkan tabel relasi biodata
    $siswas = User::where('role', 'siswa')
                  ->whereHas('biodataSiswa', function($query) {
                      $query->where('status', 'aktif'); // Sesuaikan nama kolom statusmu
                  })
                  ->with('biodataSiswa.kelas')
                  ->orderBy('name')
                  ->get();

    return view('admin.prestasi.create', [
        'siswas' => $siswas,
        'tingkatOptions' => Prestasi::TINGKAT_OPTIONS,
        'kategoriOptions' => Prestasi::KATEGORI_OPTIONS,
    ]);
}
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id'   => 'required|exists:users,id',
            'judul'      => 'required|string|max:255',
            'tingkat'    => 'required|string',
            'kategori'   => 'required|string',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Prestasi::create($validated);
        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi): View
    {
        $siswas = User::where('role', 'siswa')->with('biodataSiswa.kelas')->orderBy('name')->get();
        return view('admin.prestasi.edit', [
            'prestasi' => $prestasi,
            'siswas' => $siswas,
            'tingkatOptions' => Prestasi::TINGKAT_OPTIONS,
            'kategoriOptions' => Prestasi::KATEGORI_OPTIONS,
        ]);
    }

    public function update(Request $request, Prestasi $prestasi): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id'   => 'required|exists:users,id',
            'judul'      => 'required|string|max:255',
            'tingkat'    => 'required|string',
            'kategori'   => 'required|string',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $prestasi->update($validated);
        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    // ================================================================
    // FITUR KHUSUS POPUP MODAL (Detail Siswa)
    // ================================================================

    /**
     * Menangani input dari Modal Popup di halaman detail siswa.
     */
    public function storeSiswaPrestasi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id'      => 'required|exists:users,id',
            'judul'         => 'required|string|max:255',
            'tingkat'       => 'required|string',
            'kategori'      => 'required|string',
            'penyelenggara' => 'nullable|string|max:255',
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        // 1. Simpan Data
        Prestasi::create($validated);

        // 2. Kirim Notifikasi ke Siswa
        Notifikasi::create([
            'user_id' => $validated['siswa_id'],
            'judul'   => 'Selamat! Prestasi Tercatat',
            'pesan'   => 'Sekolah bangga padamu! Prestasi "' . $validated['judul'] . '" telah dicatat oleh Guru BK.',
        ]);

        // 3. Redirect kembali ke halaman profil siswa
        return redirect()->route('admin.siswa.show', $validated['siswa_id'])
                         ->with('success', 'Prestasi berhasil dicatat! Luar biasa.');
    }

    /**
     * Hapus prestasi (Support untuk tombol delete di Detail Siswa maupun Index).
     * Saya ubah parameternya jadi $id supaya fleksibel.
     */
    public function destroy($id): RedirectResponse
    {
        $prestasi = Prestasi::findOrFail($id);
        $siswaId = $prestasi->siswa_id;
        
        $prestasi->delete();

        // Cek URL sebelumnya. Jika dari halaman detail siswa, kembalikan ke sana.
        // Jika tidak, kembalikan ke index.
        if (str_contains(url()->previous(), '/siswa/')) {
            return redirect()->route('admin.siswa.show', $siswaId)
                             ->with('success', 'Data prestasi berhasil dihapus.');
        }

        return redirect()->route('admin.prestasi.index')
                         ->with('success', 'Data prestasi berhasil dihapus.');
    }
}