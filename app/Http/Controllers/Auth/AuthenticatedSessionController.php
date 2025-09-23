<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani permintaan autentikasi yang masuk.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Logika redirect cerdas berdasarkan role pengguna
        $user = $request->user();

        if ($user->role === 'guru_bk') {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Untuk role lainnya (misalnya siswa)
        return redirect()->intended(route('dashboard')); // <-- Titik koma yang hilang sudah ditambahkan di sini.
    }

    /**
     * Menghancurkan sesi yang terautentikasi (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}