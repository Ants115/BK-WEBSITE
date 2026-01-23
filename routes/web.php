<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController; // Controller "Satpam"

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
use App\Http\Controllers\Admin\LaporanKonsultasiController;

// Konsultasi BK
use App\Http\Controllers\KonsultasiController;

// Wali Kelas
use App\Http\Controllers\WaliKelas\DashboardController as WaliDashboardController;

/*
|--------------------------------------------------------------------------
| HALAMAN DEPAN (GUEST)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| ROUTE GLOBAL (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. DASHBOARD UTAMA (Pintu Masuk)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. UTILITAS GLOBAL
    Route::post('/get-nomor-kelas', [RegisteredUserController::class, 'getNomorKelas'])->name('get.nomor.kelas');
    
    Route::patch('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])
        ->name('notifikasi.tandaiDibaca');

    // 3. FITUR CHAT / KONSULTASI ONLINE (Admin <-> Siswa)
    // PENTING: Route 'widget' HARUS DI ATAS route '{id}'
    Route::get('/chat/widget', [ChatController::class, 'widget'])->name('chat.widget'); 
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
});


/*
|--------------------------------------------------------------------------
| GROUP ADMIN & GURU BK (Role: admin, guru_bk)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin,guru_bk'])
    ->prefix('admin') // URL jadi: /admin/...
    ->name('admin.')  // Nama route jadi: admin....
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Profil
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // --- MANAJEMEN KELAS ---
        Route::delete('/kelas/bulk-delete', [KelasController::class, 'destroyMultiple'])->name('kelas.destroyMultiple');
        Route::resource('kelas', KelasController::class);

        // --- MANAJEMEN MASTER DATA ---
        Route::resource('jurusan', JurusanController::class);
        Route::resource('tingkatan', TingkatanController::class);
        Route::resource('guru-bk', GuruBkController::class)->parameters(['guru-bk' => 'guruBk']);
        Route::resource('staf-guru', StafGuruController::class)->parameters(['staf-guru' => 'stafGuru']);

        // --- MANAJEMEN SISWA ---
        // Route Spesifik (Harus di atas resource siswa)
        Route::get('siswa/{siswa}/cetak-surat-peringatan', [SiswaController::class, 'cetakSuratPeringatan'])->name('siswa.cetakSuratPeringatan');
        Route::get('siswa/{siswa}/cetak-panggilan', [SiswaController::class, 'cetakPanggilanOrtu'])->name('siswa.cetakPanggilan');
        Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.updateKelas');
        
        // Surat Panggilan
        Route::get('siswa/{siswa}/surat-panggilan/create', [SiswaController::class, 'createSuratPanggilan'])->name('siswa.createSuratPanggilan');
        Route::post('siswa/{siswa}/surat-panggilan', [SiswaController::class, 'cetakSuratPanggilan'])->name('siswa.cetakSuratPanggilan');

        // Resource Siswa
        Route::resource('siswa', SiswaController::class);

        // --- MANAJEMEN PELANGGARAN ---
        Route::resource('pelanggaran', PelanggaranController::class);
        Route::post('/siswa/pelanggaran', [PelanggaranController::class, 'storeSiswaPelanggaran'])->name('pelanggaran.storeSiswa');
        Route::delete('/siswa/pelanggaran/{id}', [PelanggaranController::class, 'destroySiswaPelanggaran'])->name('pelanggaran.destroySiswa');

        // --- MANAJEMEN PRESTASI ---
        Route::get('/prestasi/rekap', [PrestasiController::class, 'rekap'])->name('prestasi.rekap');
        Route::get('/prestasi-list', [PrestasiController::class, 'index'])->name('prestasi.index');
        Route::post('/siswa/prestasi', [PrestasiController::class, 'storeSiswaPrestasi'])->name('prestasi.storeSiswa');
        Route::delete('/siswa/prestasi/{id}', [PrestasiController::class, 'destroy'])->name('prestasi.destroySiswa');
        Route::resource('prestasi', PrestasiController::class)->except(['show', 'index']);

        // --- KONSULTASI (Admin Side) ---
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::get('/konsultasi/laporan', [KonsultasiController::class, 'laporan'])->name('konsultasi.laporan');
        Route::patch('/konsultasi/{konsultasi}/selesaikan', [KonsultasiController::class, 'selesaikan'])->name('konsultasi.selesaikan');
        Route::patch('/konsultasi/{konsultasi}/setujui', [KonsultasiController::class, 'setujui'])->name('konsultasi.setujui');
        Route::patch('/konsultasi/{konsultasi}/tolak', [KonsultasiController::class, 'tolak'])->name('konsultasi.tolak');
        Route::patch('/konsultasi/{konsultasi}/jadwalkan-ulang', [KonsultasiController::class, 'jadwalkanUlang'])->name('konsultasi.jadwalkanUlang');
        
        Route::get('/laporan-konsultasi', [LaporanKonsultasiController::class, 'index'])->name('laporan-konsultasi.index');
        Route::resource('jadwal-konseling', JadwalKonselingController::class)->except(['show']);

        // --- FITUR LAINNYA ---
        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
        Route::post('/kenaikan-kelas', [KenaikanKelasController::class, 'proses'])->name('kenaikan-kelas.proses');
        
        Route::get('/arsip-alumni', [ArsipAlumniController::class, 'index'])->name('arsip.index');
        Route::get('/arsip-alumni/{tahun_lulus}', [ArsipAlumniController::class, 'show'])->name('arsip.show');
    });


/*
|--------------------------------------------------------------------------
| GROUP WALI KELAS (Role: walikelas)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:walikelas'])->group(function () {
    Route::get('/wali-kelas/dashboard', [WaliDashboardController::class, 'index'])->name('wali.dashboard');
});


/*
|--------------------------------------------------------------------------
| GROUP SISWA (Role: siswa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:siswa'])->group(function () {

    // Prestasi Saya
    Route::get('/prestasi-saya', [SiswaPrestasiController::class, 'index'])->name('siswa.prestasi.index');

    // Konsultasi Siswa
    Route::get('/konsultasi/buat', [KonsultasiController::class, 'create'])->name('konsultasi.create');
    Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
    Route::get('/konsultasi/riwayat', [KonsultasiController::class, 'riwayat'])->name('konsultasi.riwayat');
    Route::get('/konsultasi/{konsultasi}/cetak', [KonsultasiController::class, 'cetakTiket'])->name('konsultasi.cetak');
    Route::get('/konsultasi/notifikasi/{id}', [KonsultasiController::class, 'bacaNotifikasi'])->name('konsultasi.baca_notifikasi');
    Route::delete('/konsultasi/{konsultasi}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');
});

require __DIR__.'/auth.php';