<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\TingkatanController;
use App\Http\Controllers\Admin\PelanggaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Admin\KenaikanKelasController;
use App\Http\Controllers\Admin\ArsipAlumniController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Awal
Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard Siswa
Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk notifikasi (berlaku untuk semua user yang login)
Route::patch('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])
    ->middleware(['auth', 'verified'])
    ->name('notifikasi.tandaiDibaca');


// --- GRUP UNTUK SEMUA RUTE ADMIN ---
Route::middleware(['auth', 'verified'])
    ->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ======================================================================
    // == PINDAHKAN RUTE BARU KE SINI (DI ATAS RESOURCE) ==
    // ======================================================================
    Route::get('siswa/penyesuaian', [SiswaController::class, 'showPenyesuaianForm'])->name('siswa.penyesuaian');
    Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.update-kelas');
    // ======================================================================

    // Rute Resource untuk semua CRUD Admin
    // Rute ini sekarang berada di bawah rute yang lebih spesifik
    Route::resource('siswa', SiswaController::class);
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('tingkatan', TingkatanController::class);

    // Rute Kenaikan Kelas
    Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
    Route::post('/kenaikan-kelas', [KenaikanKelasController::class, 'proses'])->name('kenaikan-kelas.proses');
    
    // Rute untuk Arsip Alumni
    Route::get('/arsip-alumni', [ArsipAlumniController::class, 'index'])->name('arsip.index');
    Route::get('/arsip-alumni/{tahun_lulus}', [ArsipAlumniController::class, 'show'])->name('arsip.show');
    
    // Rute KHUSUS untuk menangani catatan pelanggaran siswa
    Route::post('pelanggaran-siswa', [PelanggaranController::class, 'storeSiswaPelanggaran'])->name('pelanggaran-siswa.store');
    Route::delete('pelanggaran-siswa/{pelanggaranSiswa}', [PelanggaranController::class, 'destroySiswaPelanggaran'])->name('pelanggaran-siswa.destroy');

    // Rute KHUSUS untuk surat
    Route::get('siswa/{siswa}/cetak-surat-peringatan', [SiswaController::class, 'cetakSuratPeringatan'])->name('siswa.cetakSuratPeringatan');
    Route::get('siswa/{siswa}/surat-panggilan/create', [SiswaController::class, 'createSuratPanggilan'])->name('siswa.createSuratPanggilan');
    Route::post('siswa/{siswa}/surat-panggilan', [SiswaController::class, 'cetakSuratPanggilan'])->name('siswa.cetakSuratPanggilan');
    
    // Rute untuk profil admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat rute-rute autentikasi bawaan Breeze
require __DIR__.'/auth.php';
