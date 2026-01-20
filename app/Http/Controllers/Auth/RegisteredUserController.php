<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BiodataSiswa;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Tingkatan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $tingkatanList = Tingkatan::orderBy('nama_tingkatan')->get();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        $nomorKelasList = Kelas::distinct()->orderBy('nama_unik')->pluck('nama_unik');
    
        return view('auth.register', compact('tingkatanList', 'jurusanList', 'nomorKelasList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:biodata_siswa,nis'],
            'tingkatan_id' => ['required', 'integer', 'exists:tingkatans,id'],
            'jurusan_id' => ['required', 'integer', 'exists:jurusans,id'],
            'nama_unik' => ['required', 'string'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $kelas = Kelas::where('tingkatan_id', $request->tingkatan_id)
                      ->where('jurusan_id', $request->jurusan_id)
                      ->where('nama_unik', $request->nama_unik)
                      ->first();

        if (!$kelas) {
            return back()->withErrors(['nama_unik' => 'Kombinasi Tingkatan, Jurusan, dan Nomor Kelas tidak valid.'])->withInput();
        }
        
        $user = null;
        DB::transaction(function () use ($request, $kelas, &$user) {
            // ---- PERBAIKAN DI SINI ----
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa', // <-- BARIS INI YANG SEBELUMNYA HILANG
            ]);
    
            $user->biodataSiswa()->create([
                'nis' => $request->nis,
                'nama_lengkap' => $request->name,
                'kelas_id' => $kelas->id,
            ]);

            event(new Registered($user));
        });

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // ... function store dan create yang lama ...

    /**
     * Mengambil daftar nomor kelas berdasarkan tingkatan dan jurusan
     */
    public function getNomorKelas(Request $request)
    {
        // Cari kelas yang cocok dengan Tingkatan & Jurusan yang dipilih user
        $nomorKelas = Kelas::where('tingkatan_id', $request->tingkatan_id)
                            ->where('jurusan_id', $request->jurusan_id)
                            ->orderBy('nama_unik') // Urutkan 1, 2, 3...
                            ->pluck('nama_unik');  // Ambil cuma kolom angkanya

        return response()->json($nomorKelas);
    }
}

