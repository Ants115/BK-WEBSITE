<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

// Konsultasi BK (dipakai siswa & admin)
use App\Http\Controllers\KonsultasiController;

// Wali Kelas
use App\Http\Controllers\WaliKelas\DashboardController as WaliDashboardController;
use App\Http\Controllers\DashboardController; // Controller "Satpam" (No 3)
use App\Http\Controllers\Admin\DashboardController as AdminDashboard; // Controller Admin (No 1)
use App\Http\Controllers\WaliKelas\DashboardController as WaliDashboard; // Controller Wali (No 2)

// 1. ROUTE UTAMA (Pintu Masuk setelah Login)
// Ini yang dipanggil saat user membuka domain.com/dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


// 2. GROUP ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // URL ini beda: /admin/dashboard
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])
        ->name('admin.dashboard');
});


// 3. GROUP WALI KELAS
Route::middleware(['auth', 'role:walikelas'])->group(function () {
    // URL ini beda: /wali-kelas/dashboard
    Route::get('/wali-kelas/dashboard', [WaliDashboard::class, 'index'])
        ->name('wali.dashboard');
});
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
| ROUTE GLOBAL: semua user login (semua role)
|--------------------------------------------------------------------------
*/

Route::post('/get-nomor-kelas', [RegisteredUserController::class, 'getNomorKelas'])->name('get.nomor.kelas');

// Group khusus Wali Kelas
Route::middleware(['auth', 'role:walikelas'])->group(function () {
    
    // Route ini yang akan memanggil Controller Wali Kelas kamu
    Route::get('/wali-kelas/dashboard', [WaliDashboardController::class, 'index'])
        ->name('wali.dashboard');

});

Route::middleware(['auth', 'verified'])->group(function () {
    // /dashboard = router umum (nanti cek role di controller)
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('dashboard');

    // Notifikasi umum
    Route::patch('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])
        ->name('notifikasi.tandaiDibaca');
});

/*
|--------------------------------------------------------------------------
| RUTE KHUSUS SISWA (role: siswa)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:siswa'])->group(function () {

    // Rute khusus untuk memproses Mutasi / Pindah Kelas
    Route::post('/siswa/update-kelas', [App\Http\Controllers\Admin\SiswaController::class, 'updateKelas'])->name('admin.siswa.updateKelas');

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
});

/*
|--------------------------------------------------------------------------
| RUTE BACKOFFICE BK – ADMIN & GURU BK
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:admin,guru_bk'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ==========================================
        // 1. DASHBOARD & PROFIL
        // ==========================================
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // ==========================================
        // 2. MANAJEMEN MASTER DATA (CRUD)
        // ==========================================
        
        // --- PERBAIKAN URUTAN KELAS (PENTING) ---
        // Route Bulk Delete WAJIB DI ATAS Resource Kelas agar tidak kena 404
        Route::delete('/kelas/bulk-delete', [\App\Http\Controllers\Admin\KelasController::class, 'destroyMultiple'])
            ->name('kelas.destroyMultiple');
            
        Route::resource('kelas', KelasController::class);
        // ----------------------------------------

        Route::resource('jurusan', JurusanController::class);
        Route::resource('tingkatan', TingkatanController::class);
        Route::resource('guru-bk', GuruBkController::class)->parameters(['guru-bk' => 'guruBk']);
        Route::resource('staf-guru', StafGuruController::class)->parameters(['staf-guru' => 'stafGuru']);


        // ==========================================
        // 3. MANAJEMEN SISWA & MUTASI
        // ==========================================
        Route::resource('siswa', SiswaController::class);

        // Mutasi / Pindah Kelas
        Route::post('/siswa/update-kelas', [SiswaController::class, 'updateKelas'])
            ->name('siswa.updateKelas');

        // Cetak Surat
        Route::get('siswa/{siswa}/cetak-surat-peringatan', [SiswaController::class, 'cetakSuratPeringatan'])
            ->name('siswa.cetakSuratPeringatan');

        Route::get('siswa/{siswa}/surat-panggilan/create', [SiswaController::class, 'createSuratPanggilan'])
            ->name('siswa.createSuratPanggilan');

        Route::post('siswa/{siswa}/surat-panggilan', [SiswaController::class, 'cetakSuratPanggilan'])
            ->name('siswa.cetakSuratPanggilan');


        // ==========================================
        // 4. MANAJEMEN PELANGGARAN
        // ==========================================
        // Master Data Pelanggaran
        Route::resource('pelanggaran', PelanggaranController::class);

        // Transaksi Catat Pelanggaran Siswa (Tombol Merah)
        Route::post('/siswa/pelanggaran', [PelanggaranController::class, 'storeSiswaPelanggaran'])
            ->name('pelanggaran.storeSiswa');

        // Transaksi Hapus Riwayat Pelanggaran
        Route::delete('/siswa/pelanggaran/{id}', [PelanggaranController::class, 'destroySiswaPelanggaran'])
            ->name('pelanggaran.destroySiswa');


        // ==========================================
        // 5. MANAJEMEN PRESTASI
        // ==========================================
        // Master Data Prestasi & Rekap
        Route::get('/prestasi/rekap', [PrestasiController::class, 'rekap'])->name('prestasi.rekap');
        // Route untuk Menu Sidebar "Prestasi Siswa" (Daftar & Filter)
        Route::get('/prestasi-list', [PrestasiController::class, 'index'])->name('prestasi.index');
        // Resource standar (CRUD)
        Route::resource('prestasi', PrestasiController::class)->except(['show', 'index']); 

        // Transaksi Catat Prestasi Siswa (Tombol Biru)
        Route::post('/siswa/prestasi', [PrestasiController::class, 'storeSiswaPrestasi'])
            ->name('prestasi.storeSiswa');
            
        // Transaksi Hapus Riwayat Prestasi
        Route::delete('/siswa/prestasi/{id}', [PrestasiController::class, 'destroy'])
            ->name('prestasi.destroySiswa');


        // ==========================================
        // 6. KONSULTASI (Admin Side)
        // ==========================================
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::get('/konsultasi/laporan', [KonsultasiController::class, 'laporan'])->name('konsultasi.laporan');
        
        Route::patch('/konsultasi/{konsultasi}/selesaikan', [KonsultasiController::class, 'selesaikan'])->name('konsultasi.selesaikan');
        Route::patch('/konsultasi/{konsultasi}/setujui', [KonsultasiController::class, 'setujui'])->name('konsultasi.setujui');
        Route::patch('/konsultasi/{konsultasi}/tolak', [KonsultasiController::class, 'tolak'])->name('konsultasi.tolak');
        Route::patch('/konsultasi/{konsultasi}/jadwalkan-ulang', [KonsultasiController::class, 'jadwalkanUlang'])->name('konsultasi.jadwalkanUlang');

        // Laporan Konsultasi Khusus
        Route::get('/laporan-konsultasi', [LaporanKonsultasiController::class, 'index'])->name('laporan-konsultasi.index');
        // Jadwal Konseling Master
        Route::resource('jadwal-konseling', JadwalKonselingController::class)->except(['show']);


        // ==========================================
        // 7. FITUR LAINNYA (Kenaikan & Alumni)
        // ==========================================
        // Kenaikan Kelas (Massal)
        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
        Route::post('/kenaikan-kelas', [KenaikanKelasController::class, 'proses'])->name('kenaikan-kelas.proses');
        
        // Arsip Alumni
        Route::get('/arsip-alumni', [ArsipAlumniController::class, 'index'])->name('arsip.index');
        Route::get('/arsip-alumni/{tahun_lulus}', [ArsipAlumniController::class, 'show'])->name('arsip.show');

    });

/*
|--------------------------------------------------------------------------
| RUTE KHUSUS WALI KELAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:wali_kelas'])
    ->prefix('wali-kelas')
    ->name('wali.')
    ->group(function () {
        Route::get('/dashboard', [WaliDashboardController::class, 'index'])
            ->name('dashboard');

        // route wali kelas lain taruh di sini
    });

require __DIR__.'/auth.php';