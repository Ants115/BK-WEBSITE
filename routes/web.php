<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;

// Dashboard siswa & fitur siswa
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PrestasiController as SiswaPrestasiController;

// Admin BK / Guru BK
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\TingkatanController;
use App\Http\Controllers\Admin\PelanggaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KenaikanKelasController;
use App\Http\Controllers\Admin\ArsipAlumniController;
use App\Http\Controllers\Admin\GuruBkController;
use App\Http\Controllers\Admin\StafGuruController;
use App\Http\Controllers\Admin\JadwalKonselingController;
use App\Http\Controllers\Admin\PrestasiController;

// Konsultasi BK (dipakai siswa & admin)
use App\Http\Controllers\KonsultasiController;

// Wali Kelas
use App\Http\Controllers\WaliKelas\DashboardController as WaliDashboardController;

/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| RUTE UNTUK USER LOGIN (UMUM: SISWA)
|--------------------------------------------------------------------------
|
| Semua user login lewat sini, tapi kontennya memang utama untuk siswa.
| Role lain (guru_bk, wali_kelas, admin) tetap bisa login, hanya saja
| dashboard utama mereka dialihkan di tempat lain.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard siswa
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('dashboard');

    // Prestasi siswa (versi siswa)
    Route::get('/prestasi-saya', [SiswaPrestasiController::class, 'index'])
        ->name('siswa.prestasi.index');

    // Konsultasi – sisi siswa
    Route::get('/konsultasi/buat', [KonsultasiController::class, 'create'])
        ->name('konsultasi.create');

    Route::post('/konsultasi', [KonsultasiController::class, 'store'])
        ->name('konsultasi.store');

    Route::get('/konsultasi/riwayat', [KonsultasiController::class, 'riwayat'])
        ->name('konsultasi.riwayat');

    Route::get('/konsultasi/{konsultasi}/cetak', [KonsultasiController::class, 'cetakTiket'])
        ->name('konsultasi.cetak');

    Route::get('/konsultasi/notifikasi/{id}', [KonsultasiController::class, 'bacaNotifikasi'])
        ->name('konsultasi.baca_notifikasi');

    Route::delete('/konsultasi/{konsultasi}', [KonsultasiController::class, 'destroy'])
        ->name('konsultasi.destroy');

    // Notifikasi umum (dipakai semua user login)
    Route::patch('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])
        ->name('notifikasi.tandaiDibaca');
});

/*
|--------------------------------------------------------------------------
| RUTE BACKOFFICE BK – ADMIN & GURU BK
|--------------------------------------------------------------------------
|
| Di sini kita batasi dengan middleware role.
| Misal:
|   - admin  = super admin BK
|   - guru_bk = guru BK biasa
|
| Silakan sesuaikan: kalau mau hanya admin saja, pakai 'role:admin'.
*/

Route::middleware(['auth', 'verified', 'role:admin,guru_bk'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin BK
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
         * Konsultasi – sisi admin/guru BK
         */
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])
            ->name('konsultasi.index');

        Route::get('/konsultasi/laporan', [KonsultasiController::class, 'laporan'])
            ->name('konsultasi.laporan');

        Route::patch('/konsultasi/{konsultasi}/selesaikan', [KonsultasiController::class, 'selesaikan'])
            ->name('konsultasi.selesaikan');

        Route::patch('/konsultasi/{konsultasi}/setujui', [KonsultasiController::class, 'setujui'])
            ->name('konsultasi.setujui');

        Route::patch('/konsultasi/{konsultasi}/tolak', [KonsultasiController::class, 'tolak'])
            ->name('konsultasi.tolak');

        Route::patch('/konsultasi/{konsultasi}/jadwalkan-ulang', [KonsultasiController::class, 'jadwalkanUlang'])
            ->name('konsultasi.jadwalkanUlang');

        /*
         * Siswa & Kelas
         */
        Route::get('siswa/penyesuaian', [SiswaController::class, 'showPenyesuaianForm'])
            ->name('siswa.penyesuaian');

        Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])
            ->name('siswa.update-kelas');

        Route::resource('siswa', SiswaController::class);
        Route::resource('pelanggaran', PelanggaranController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('tingkatan', TingkatanController::class);

        /*
         * Guru BK – role guru_bk
         */
        Route::resource('guru-bk', GuruBkController::class)
            ->parameters(['guru-bk' => 'guruBk']);

        /*
         * STAF GURU – role staf_guru
         */
        Route::resource('staf-guru', StafGuruController::class)
            ->parameters(['staf-guru' => 'stafGuru']);

        /*
         * Jadwal Konseling
         */
        Route::resource('jadwal-konseling', JadwalKonselingController::class)
            ->except(['show']);

        /*
         * Prestasi – sisi admin
         */
        Route::get('/prestasi/rekap', [PrestasiController::class, 'rekap'])
            ->name('prestasi.rekap');

        Route::resource('prestasi', PrestasiController::class)->except(['show']);

        /*
         * Kenaikan Kelas
         */
        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])
            ->name('kenaikan-kelas.index');

        Route::post('/kenaikan-kelas', [KenaikanKelasController::class, 'proses'])
            ->name('kenaikan-kelas.proses');

        /*
         * Arsip Alumni
         */
        Route::get('/arsip-alumni', [ArsipAlumniController::class, 'index'])
            ->name('arsip.index');

        Route::get('/arsip-alumni/{tahun_lulus}', [ArsipAlumniController::class, 'show'])
            ->name('arsip.show');

        /*
         * Pelanggaran siswa (detail per siswa)
         */
        Route::post('pelanggaran-siswa', [PelanggaranController::class, 'storeSiswaPelanggaran'])
            ->name('pelanggaran-siswa.store');

        Route::delete('pelanggaran-siswa/{pelanggaranSiswa}', [PelanggaranController::class, 'destroySiswaPelanggaran'])
            ->name('pelanggaran-siswa.destroy');

        /*
         * Surat peringatan & panggilan
         */
        Route::get('siswa/{siswa}/cetak-surat-peringatan', [SiswaController::class, 'cetakSuratPeringatan'])
            ->name('siswa.cetakSuratPeringatan');

        Route::get('siswa/{siswa}/surat-panggilan/create', [SiswaController::class, 'createSuratPanggilan'])
            ->name('siswa.createSuratPanggilan');

        Route::post('siswa/{siswa}/surat-panggilan', [SiswaController::class, 'cetakSuratPanggilan'])
            ->name('siswa.cetakSuratPanggilan');

        /*
         * Profil Admin
         */
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });

/*
|--------------------------------------------------------------------------
| RUTE KHUSUS WALI KELAS
|--------------------------------------------------------------------------
|
| Hanya role wali_kelas yang boleh masuk ke prefix /wali-kelas.
| Tidak ada link ke sini di menu admin, supaya benar-benar terpisah.
*/

Route::middleware(['auth', 'verified', 'role:wali_kelas'])
    ->prefix('wali-kelas')
    ->name('wali.')
    ->group(function () {
        Route::get('/dashboard', [WaliDashboardController::class, 'index'])
            ->name('dashboard');

        // nanti kalau ada modul tambahan untuk wali kelas, letakkan di sini
    });

require __DIR__.'/auth.php';
