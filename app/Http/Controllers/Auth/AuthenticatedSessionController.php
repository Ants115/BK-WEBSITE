<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    // --- LOGIKA TAMBAHAN DIMULAI DI SINI ---
    $user = $request->user();

    if ($user->role === 'guru_bk') {
        // Jika rolenya adalah guru_bk, arahkan ke dashboard admin
        return redirect()->route('admin.dashboard');
    }
    
    // Jika tidak, arahkan ke halaman default (dashboard siswa)
    return redirect()->intended(RouteServiceProvider::HOME);
    // --- LOGIKA TAMBAHAN SELESAI ---
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
