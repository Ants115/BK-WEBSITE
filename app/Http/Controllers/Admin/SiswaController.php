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
// use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar semua siswa.
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

        // Validasi bahwa pesan harus diisi
        $validated = $request->validate([
            'pesan' => 'required|string|min:20',
        ]);

        $data = [
            'siswa' => $siswa,
            'pesan' => $validated['pesan'], // Gunakan pesan dari form
            'tanggalCetak' => now()->isoFormat('D MMMM YYYY'),
        ];

        // Ganti 'return view' menjadi 'Pdf::loadView' jika DOMPDF sudah siap
        // $pdf = Pdf::loadView('admin.siswa.template-surat-panggilan', $data);
        // return $pdf->stream('surat-panggilan-'. $siswa->name .'.pdf');
        
        return view('admin.siswa.template-surat-panggilan', $data);
    }
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = User::where('role', 'siswa')->with('biodataSiswa.kelas');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('biodataSiswa', function ($q) use ($search) {
                      $q->where('nis', 'like', '%' . $search . '%');
                  });
        }
        $siswas = $query->latest()->paginate(10);
        return view('admin.siswa.index', compact('siswas', 'search'));
    }

    /**
     * Menampilkan form untuk menambah siswa baru.
     */
    public function create(): View
    {
        // Jika Anda perlu dropdown kelas di form, aktifkan baris di bawah
        // $kelas = \App\Models\Kelas::all();
        // return view('admin.siswa.create', compact('kelas'));
        return view('admin.siswa.create');
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

        $siswa->load(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran', 'pelanggaranSiswa.pelapor']);
        $masterPelanggaran = Pelanggaran::orderBy('nama_pelanggaran')->get();

        $totalPoin = $siswa->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });
        
        $jenisSurat = null;
        if ($totalPoin >= 100) $jenisSurat = 'Surat Peringatan 3';
        elseif ($totalPoin >= 50) $jenisSurat = 'Surat Peringatan 2';
        elseif ($totalPoin >= 25) $jenisSurat = 'Surat Peringatan 1';

        return view('admin.siswa.show', compact('siswa', 'masterPelanggaran', 'totalPoin', 'jenisSurat'));
    }

    /**
     * Menampilkan form untuk mengedit siswa.
     */
    public function edit(User $siswa): View
    {
        // Jika Anda perlu dropdown kelas di form, aktifkan baris di bawah
        // $kelas = \App\Models\Kelas::all();
        // return view('admin.siswa.edit', compact('siswa', 'kelas'));
        return view('admin.siswa.edit', compact('siswa'));
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
     * Membuat dan menampilkan file PDF Surat Peringatan.
     */
    public function cetakSuratPeringatan(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $siswa->load(['biodataSiswa.kelas', 'pelanggaranSiswa.pelanggaran']);

        $totalPoin = $siswa->pelanggaranSiswa->sum(function ($item) {
            return $item->pelanggaran->poin ?? 0;
        });

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
        
        // return view('admin.siswa.template-surat', $data);
        $pdf = Pdf::loadView('admin.siswa.template-surat', $data);
        return $pdf->stream('surat-peringatan-'. $siswa->name .'.pdf');
    }
}