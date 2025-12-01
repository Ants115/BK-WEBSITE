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
use App\Http\Controllers\KonsultasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Awal (Welcome)
Route::get('/', function () {
    return view('welcome');
});

// ======================================================================
// RUTE SISWA & KONSULTASI (BUTUH LOGIN)
// ======================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Siswa
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // Konsultasi Siswa
    Route::get('/konsultasi/buat', [KonsultasiController::class, 'create'])->name('konsultasi.create');
    Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
    Route::get('/konsultasi/riwayat', [KonsultasiController::class, 'riwayat'])->name('konsultasi.riwayat');
    Route::get('/konsultasi/{konsultasi}/cetak', [KonsultasiController::class, 'cetakTiket'])->name('konsultasi.cetak');
    Route::get('/konsultasi/notifikasi/{id}', [KonsultasiController::class, 'bacaNotifikasi'])->name('konsultasi.baca_notifikasi');
    Route::delete('/konsultasi/{konsultasi}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');

    // Rute untuk notifikasi umum (bisa dipakai oleh semua user login)
    Route::patch('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])
        ->name('notifikasi.tandaiDibaca');
});

// ======================================================================
// GRUP UNTUK SEMUA RUTE ADMIN
// ======================================================================
Route::middleware(['auth', 'verified'])
    ->prefix('admin')->name('admin.')->group(function () {

        // Konsultasi (Admin)
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::patch('/konsultasi/{konsultasi}/selesaikan', [KonsultasiController::class, 'selesaikan'])->name('konsultasi.selesaikan');
        Route::patch('/konsultasi/{konsultasi}/setujui', [KonsultasiController::class, 'setujui'])->name('konsultasi.setujui');
        Route::patch('/konsultasi/{konsultasi}/tolak', [KonsultasiController::class, 'tolak'])->name('konsultasi.tolak');
        Route::patch('/konsultasi/{konsultasi}/jadwalkan-ulang', [KonsultasiController::class, 'jadwalkanUlang'])->name('konsultasi.jadwalkanUlang');

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rute KHUSUS penyesuaian kelas siswa (harus di atas resource siswa)
        Route::get('siswa/penyesuaian', [SiswaController::class, 'showPenyesuaianForm'])->name('siswa.penyesuaian');
        Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.update-kelas');

        // Rute Resource untuk semua CRUD Admin
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

        // Rute KHUSUS untuk catatan pelanggaran siswa
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
