<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BiodataSiswa;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Tingkatan;
use App\Rules\Recaptcha; // Pastikan ini ter-import
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
    /**
     * Menampilkan form registrasi.
     */
    public function create(): View
    {
        $tingkatanList = Tingkatan::orderBy('nama_tingkatan')->get();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        
        // Kita ambil semua kemungkinan nomor kelas untuk inisialisasi awal
        $nomorKelasList = Kelas::distinct()->orderBy('nama_unik')->pluck('nama_unik');
    
        return view('auth.register', compact('tingkatanList', 'jurusanList', 'nomorKelasList'));
    }

    /**
     * Menangani proses penyimpanan data registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:biodata_siswa,nis'],
            'tingkatan_id' => ['required', 'integer', 'exists:tingkatans,id'],
            'jurusan_id' => ['required', 'integer', 'exists:jurusans,id'],
            'nama_unik' => ['required', 'string'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Validasi Captcha
            'g-recaptcha-response' => ['required', new Recaptcha],
        ], [
            // 2. Pesan Error Kustom (Agar user tahu kesalahannya)
            'g-recaptcha-response.required' => 'Mohon centang kotak "Saya bukan robot" untuk melanjutkan.',
            'nis.unique' => 'NIS ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        // 3. Cari ID Kelas berdasarkan kombinasi input
        $kelas = Kelas::where('tingkatan_id', $request->tingkatan_id)
                      ->where('jurusan_id', $request->jurusan_id)
                      ->where('nama_unik', $request->nama_unik)
                      ->first();

        // Jika kelas tidak ditemukan dalam database
        if (!$kelas) {
            return back()
                ->withErrors(['nama_unik' => 'Kombinasi Tingkatan, Jurusan, dan Nomor Kelas tidak valid (Kelas tidak ditemukan).'])
                ->withInput();
        }
        
        // 4. Proses Penyimpanan dengan Transaksi Database
        // Menggunakan try-catch otomatis via DB::transaction
        $user = DB::transaction(function () use ($request, $kelas) {
            
            // A. Buat Akun User (Login)
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa', // Wajib di-set siswa
            ]);
    
            // B. Buat Biodata Siswa yang terhubung
            $newUser->biodataSiswa()->create([
                'nis' => $request->nis,
                'nama_lengkap' => $request->name,
                'kelas_id' => $kelas->id,
            ]);

            event(new Registered($newUser));

            return $newUser;
        });

        // 5. Login otomatis setelah daftar
        Auth::login($user);

        // 6. Redirect ke Dashboard
        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * API Endpoint untuk mengambil nomor kelas (AJAX).
     */
    public function getNomorKelas(Request $request)
    {
        $nomorKelas = Kelas::where('tingkatan_id', $request->tingkatan_id)
                            ->where('jurusan_id', $request->jurusan_id)
                            ->orderBy('nama_unik')
                            ->pluck('nama_unik');

        return response()->json($nomorKelas);
    }
}