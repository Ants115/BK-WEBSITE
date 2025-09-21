<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\Jurusan;
use App\Models\BiodataSiswa;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use App\Models\Tingkatan;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // 1. Ambil data yang dibutuhkan untuk dropdown
        $jurusanList = Jurusan::all();
        $tingkatanList = Tingkatan::orderBy('nama_tingkatan')->get();
        $nomorKelasList = Kelas::distinct()->orderBy('nama_unik')->pluck('nama_unik');
    
        // 2. Kirim semua data tersebut ke view
        return view('auth.register', [
            'tingkatanList' => $tingkatanList,
            'jurusanList' => $jurusanList,
            'nomorKelasList' => $nomorKelasList,
        ]);
    }
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // 1. Tambahkan 'tingkatan_id' ke dalam validasi
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'nis' => ['required', 'string', 'max:255', 'unique:'.BiodataSiswa::class],
        'tingkatan_id' => ['required', 'integer', 'exists:tingkatan,id'], // Validasi Tingkatan
        'jurusan_id' => ['required', 'integer', 'exists:jurusan,id'],
        'nama_unik' => ['required', 'string'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // 2. Cari kelas_id yang cocok berdasarkan SEMUA input dari user
    $kelas = Kelas::where('tingkatan_id', $request->tingkatan_id) // <-- Gunakan tingkatan dari form
                  ->where('jurusan_id', $request->jurusan_id)
                  ->where('nama_unik', $request->nama_unik)
                  ->first();

    // 3. Jika kombinasi kelas tidak ditemukan, kembalikan error
    if (!$kelas) {
        return back()->withErrors(['tingkatan_id' => 'Kombinasi Tingkatan, Jurusan, dan Kelas tidak valid.'])->withInput();
    }

    // 4. Lanjutkan proses pembuatan user seperti biasa
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'siswa',
    ]);

    BiodataSiswa::create([
        'user_id' => $user->id,
        'nis' => $request->nis,
        'nama_lengkap' => $request->name,
        'kelas_id' => $kelas->id, // Gunakan ID yang sudah ditemukan
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}

}