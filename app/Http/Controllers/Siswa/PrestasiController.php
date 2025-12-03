<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    /**
     * Tampilkan daftar prestasi milik siswa yang sedang login.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Mengambil prestasi lewat relasi user->prestasi
        $prestasi = $user->prestasi()
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('siswa.prestasi.index', compact('prestasi', 'user'));
    }
}
