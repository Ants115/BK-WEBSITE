<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Kelas;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar semua siswa.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $query = User::where('role', 'siswa')->whereHas('biodataSiswa', function ($q) {
            $q->where('status', 'Aktif');
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('biodataSiswa', function ($subq) use ($search) {
                      $subq->where('nis', 'like', '%' . $search . '%');
                  });
            });
        }
        $siswas = $query->with('biodataSiswa.kelas')->latest()->paginate(15);
        return view('admin.siswa.index', compact('siswas', 'search'));
    }

    /**
     * Menampilkan form untuk menambah siswa baru.
     */
    public function create(): View
    {
        $kelas = \App\Models\Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    /**
     * Menyimpan data siswa baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', 'unique:biodata_siswa,nis'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->nis), // Password default dari NIS
                'role' => 'siswa',
            ]);

            $user->biodataSiswa()->create([
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail seorang siswa.
     */
    public function show(User $siswa): View
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        // Load relasi yang dibutuhkan
        $siswa->load([
            'biodataSiswa.kelas',
            'pelanggaranSiswa.pelanggaran',
            'pelanggaranSiswa.pelapor',
            'prestasi', // tambahan: relasi prestasi
        ]);

        $masterPelanggaran = Pelanggaran::orderBy('nama_pelanggaran')->get();

        // Hitung total poin pelanggaran
        $totalPoin = $siswa->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });
        
        $jenisSurat = null;
        if ($totalPoin >= 100) {
            $jenisSurat = 'Surat Peringatan 3';
        } elseif ($totalPoin >= 50) {
            $jenisSurat = 'Surat Peringatan 2';
        } elseif ($totalPoin >= 25) {
            $jenisSurat = 'Surat Peringatan 1';
        }

        // Susun data prestasi (urutan terbaru di atas)
        $prestasi = $siswa->prestasi()
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPrestasi = $prestasi->count();

        return view('admin.siswa.show', compact(
            'siswa',
            'masterPelanggaran',
            'totalPoin',
            'jenisSurat',
            'prestasi',
            'totalPrestasi'
        ));
    }

    /**
     * Menampilkan form untuk mengedit siswa.
     */
    public function edit(User $siswa): View
    {
        $kelas = \App\Models\Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }
 
    /**
     * Memperbarui data siswa di database.
     */
    public function update(Request $request, User $siswa): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $siswa->id],
            'nis' => ['required', 'string', 'max:20', 'unique:biodata_siswa,nis,' . optional($siswa->biodataSiswa)->id],
            // 'kelas_id' => ['required', 'exists:kelas,id'],
        ]);
        
        DB::transaction(function () use ($request, $siswa) {
            $siswa->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        
            if ($siswa->biodataSiswa) {
                $siswa->biodataSiswa->update([
                    'nis' => $request->nis,
                    // 'kelas_id' => $request->kelas_id,
                ]);
            }
        });
        
        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa.
     */
    public function destroy(User $siswa): RedirectResponse
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
    
    /**
     * Menampilkan form untuk membuat surat panggilan.
     */
    public function createSuratPanggilan(User $siswa): View
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        return view('admin.siswa.create-surat-panggilan', compact('siswa'));
    }

    /**
     * Memproses dan membuat PDF surat panggilan dengan pesan kustom.
     */
    public function cetakSuratPanggilan(Request $request, User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }
        $validated = $request->validate(['pesan' => 'required|string|min:20']);
        $data = [
            'siswa' => $siswa,
            'pesan' => $validated['pesan'],
            'tanggalCetak' => now()->isoFormat('D MMMM YYYY'),
        ];
        $pdf = Pdf::loadView('admin.siswa.template-surat-panggilan', $data);
        return $pdf->stream('surat-panggilan-'. $siswa->name .'.pdf');
    }

    /**
     * Membuat dan menampilkan file PDF Surat Peringatan.
     */
    public function cetakSuratPeringatan(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }
        $siswa->load(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran']);
        $totalPoin = $siswa->pelanggaranSiswa->sum(fn ($item) => $item->pelanggaran->poin ?? 0);

        $jenisSurat = null;
        if ($totalPoin >= 100) $jenisSurat = 'Surat Peringatan 3';
        elseif ($totalPoin >= 50) $jenisSurat = 'Surat Peringatan 2';
        elseif ($totalPoin >= 25) $jenisSurat = 'Surat Peringatan 1';
        else {
            return redirect()->back()->with('error', 'Total poin siswa tidak cukup untuk menerbitkan surat peringatan.');
        }

        $data = [
            'siswa' => $siswa,
            'totalPoin' => $totalPoin,
            'jenisSurat' => $jenisSurat,
            'tanggalCetak' => now()->isoFormat('D MMMM Y'),
        ];
        $pdf = Pdf::loadView('admin.siswa.template-surat', $data);
        return $pdf->stream('surat-peringatan-'. $siswa->name .'.pdf');
    }

    // ======================================================================
    // == METHOD BARU UNTUK FITUR PENYESUAIAN KELAS ==
    // ======================================================================

    /**
     * Menampilkan halaman form untuk penyesuaian kelas siswa.
     */
    public function showPenyesuaianForm(Request $request): View
    {
        $query = $request->input('search');
        $siswas = collect(); 

        if ($query) {
            $siswas = User::where('role', 'siswa')
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhereHas('biodataSiswa', function($subQ) use ($query) {
                          $subQ->where('nis', 'LIKE', "%{$query}%");
                      });
                })
                ->with(['biodataSiswa.kelas.tingkatan'])
                ->get();
        }

        // PERBAIKAN: Hanya gunakan satu tanda dolar ($)
        $kelasPerTingkat = Kelas::orderBy('nama_kelas')
                                ->get()
                                ->groupBy('tingkatan_id');
        
        return view('admin.siswa.penyesuaian', [
            'siswas' => $siswas,
            'kelasPerTingkat' => $kelasPerTingkat,
            'query' => $query
        ]);
    }

    /**
     * Memproses pemindahan satu siswa ke kelas baru.
     */
    public function updateKelas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->biodataSiswa) {
            $user->biodataSiswa->update([
                'kelas_id' => $validated['kelas_id']
            ]);

            return redirect()->route('admin.siswa.penyesuaian', ['search' => $user->name])
                             ->with('success', "Kelas siswa '{$user->name}' berhasil diperbarui.");
        }

        return redirect()->back()->with('error', 'Gagal memperbarui kelas: data biodata siswa tidak ditemukan.');
    }
}

