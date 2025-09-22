<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function tandaiDibaca(Notifikasi $notifikasi): RedirectResponse
    {
        // PENTING: Pengecekan keamanan agar user tidak bisa membaca notifikasi milik orang lain.
        if (Auth::id() !== $notifikasi->user_id) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        // Update status 'dibaca' menjadi true (1)
        $notifikasi->update(['dibaca' => true]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}