<?php

namespace App\Http\Controllers; // Perhatikan namespace ini Polos

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Jika Role ADMIN
        // Arahkan ke route khusus admin yang memakai controller Admin (No. 1)
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 2. Jika Role WALI KELAS
        // Arahkan ke route khusus wali yang memakai controller Wali (No. 2)
        if ($user->role == 'walikelas') {
            return redirect()->route('wali.dashboard');
        }

        // 3. Jika SISWA (Default)
        // Langsung tampilkan view dashboard siswa (karena siswa biasanya tidak butuh controller ribet)
        // Atau buat SiswaDashboardController jika nanti butuh statistik
        return view('dashboard'); 
    }
}