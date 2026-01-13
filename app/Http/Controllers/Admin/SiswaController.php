<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    /**
     * Tampilan Cerdas: Selektor Kelas atau Daftar Siswa
     */
    public function index(Request $request): View
    {
        $kelasId = $request->query('kelas_id');
        $search  = $request->query('search');

        // MODE 1: Jika Kelas Dipilih (Tampilkan Daftar Siswa)
        if ($kelasId) {
            $currentKelas = Kelas::with(['jurusan', 'tingkatan', 'waliKelas'])->findOrFail($kelasId);
            
            // Ambil siswa di kelas ini
            $query = User::where('role', 'siswa')
                ->whereHas('biodataSiswa', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId)
                      ->where('status', 'Aktif'); // Asumsi ada kolom status
                });

            // Fitur Pencarian di dalam kelas
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhereHas('biodataSiswa', function ($subq) use ($search) {
                          $subq->where('nis', 'like', '%' . $search . '%');
                      });
                });
            }

            $siswas = $query->with('biodataSiswa')->orderBy('name')->paginate(25)->withQueryString();

            // Ambil semua data kelas untuk Dropdown Mutasi (Pindah Kelas)
            $allKelas = Kelas::orderBy('nama_kelas')->get();

            return view('admin.siswa.index', compact('siswas', 'currentKelas', 'search', 'allKelas'));
        }

        // MODE 2: Jika Kelas BELUM Dipilih (Tampilkan Grid Jurusan & Kelas)
        // Kita kelompokkan kelas berdasarkan Jurusan agar rapi
        $jurusans = Jurusan::with(['kelas' => function($q) {
            $q->orderBy('nama_kelas');
        }])->get();

        return view('admin.siswa.index', compact('jurusans'));
    }

    /**
     * Proses Mutasi / Pindah Kelas (Langsung dari Daftar Siswa)
     */
    public function updateKelas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'  => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $kelasBaru = Kelas::find($validated['kelas_id']);

        if ($user->biodataSiswa) {
            $user->biodataSiswa->update([
                'kelas_id' => $validated['kelas_id']
            ]);

            // Redirect kembali ke halaman kelas asal (atau kelas tujuan jika mau)
            // Di sini saya redirect ke kelas TUJUAN supaya admin bisa cek apakah siswa sudah masuk
            return redirect()
                ->route('admin.siswa.index', ['kelas_id' => $validated['kelas_id']])
                ->with('success', "Berhasil! Siswa {$user->name} dipindahkan ke {$kelasBaru->nama_kelas}.");
        }

        return redirect()->back()->with('error', 'Gagal update: Biodata siswa tidak ditemukan.');
    }

    // ... (Method create, store, show, edit, destroy, cetakSurat TETAP SAMA seperti sebelumnya) ...
    // ... Copy paste method create, store, edit, update, destroy, show dari file lamamu ke sini ...
    
    // Pastikan method create() dan lain-lain tetap ada di bawah sini ya.
    
    public function create(): View
    {
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

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
                'password' => Hash::make($request->nis), 
                'role' => 'siswa',
            ]);

            $user->biodataSiswa()->create([
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'status' => 'Aktif', // Default aktif
            ]);
        });

        // Redirect ke kelas siswa tersebut agar admin langsung lihat hasilnya
        return redirect()->route('admin.siswa.index', ['kelas_id' => $request->kelas_id])
            ->with('success', 'Siswa berhasil ditambahkan.');
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
            'prestasi', 
        ]);

        // PERBAIKAN: Ambil data master pelanggaran (Ini yang bikin error tadi)
        // Pastikan kamu sudah use App\Models\Pelanggaran; di paling atas file
        $masterPelanggaran = Pelanggaran::orderBy('nama_pelanggaran')->get();

        // Hitung total poin pelanggaran
        $totalPoin = $siswa->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });
        
        // Logika Status Surat Peringatan
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

        // Kirim semua variabel ke View, TERMASUK $masterPelanggaran
        return view('admin.siswa.show', compact(
            'siswa',
            'masterPelanggaran', // <--- Wajib ada
            'totalPoin',
            'jenisSurat',
            'prestasi',
            'totalPrestasi'
        ));
    }

    public function edit(User $siswa): View
    {
        $kelas = \App\Models\Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, User $siswa): RedirectResponse
    {
        // ... (Isi sama dengan kodingan kamu sebelumnya) ...
        $siswa->update(['name' => $request->name, 'email' => $request->email]);
        if($siswa->biodataSiswa) {
            $siswa->biodataSiswa->update(['nis' => $request->nis]);
        }
        
        // Kembali ke kelas siswa
        return redirect()->route('admin.siswa.index', ['kelas_id' => $siswa->biodataSiswa->kelas_id ?? null])
            ->with('success', 'Data diperbarui.');
    }

    public function destroy(User $siswa): RedirectResponse
    {
        $kelasId = $siswa->biodataSiswa->kelas_id ?? null;
        $siswa->delete();
        return redirect()->route('admin.siswa.index', ['kelas_id' => $kelasId])->with('success', 'Siswa dihapus.');
    }
}