<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Konsultasi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Role ADMIN / GURU BK
        if ($user->role == 'admin' || $user->role == 'guru_bk') {
            return redirect()->route('admin.dashboard');
        }

        // 2. Role WALI KELAS
        if ($user->role == 'walikelas') {
            return redirect()->route('wali.dashboard');
        }

        // 3. Role SISWA
        // Ambil SATU data terbaru saja pakai first()
        try {
            $konsultasiMendatang = Konsultasi::where('siswa_id', $user->id)
                ->whereIn('status', ['menunggu', 'disetujui', 'dijadwalkan'])
                ->orderBy('created_at', 'desc')
                ->first(); // <--- KUNCI PERBAIKANNYA (Ganti get() jadi first())
        } catch (\Exception $e) {
            $konsultasiMendatang = null;
        }

        return view('dashboard', compact('user', 'konsultasiMendatang')); 
    }
}