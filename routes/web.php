<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Admin\PelanggaranController;
use App\Http\Controllers\Admin\SiswaController;  // Perbaiki import ini
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\TingkatanController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Kelompokkan semua rute admin agar rapi
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Pastikan view ini ada
    })->name('dashboard');

    // Rute untuk menampilkan semua siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');

    // Rute untuk menampilkan detail seorang siswa
    Route::get('/siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');

    // Rute resource untuk data master
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::resource('kelas', KelasController::class);
});

require __DIR__.'/auth.php';
