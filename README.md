# BK-WEBSITE  
Sistem Informasi Bimbingan Konseling Sekolah

Project ini adalah aplikasi web bimbingan konseling (BK) untuk mengelola data siswa, pelanggaran, prestasi, konseling, serta komunikasi antara guru BK dan siswa. Aplikasi dibuat sebagai bagian dari **Uji Kompetensi Keahlian (UKK)**.

---

## ✨ Fitur Utama

- **Manajemen Data Master**
  - Tingkatan (kelas X, XI, XII, dsb.)
  - Jurusan
  - Kelas

- **Manajemen Data Pengguna**
  - Data siswa
  - Data staf / guru BK
  - (Opsional) Data orang tua / wali

- **Manajemen Konseling**
  - Pengajuan permohonan konseling oleh siswa
  - Penjadwalan konseling oleh guru BK
  - Pencatatan hasil / catatan konseling
  - Riwayat konsultasi per siswa

- **Manajemen Pelanggaran & Surat Peringatan**
  - Pencatatan pelanggaran siswa
  - Relasi pelanggaran dengan siswa dan kelas
  - Pembuatan surat peringatan
  - Rekap pelanggaran per siswa

- **Manajemen Prestasi**
  - Input data prestasi siswa
  - Riwayat prestasi per siswa

- **Pengumuman & Notifikasi**
  - Pengumuman terkait kegiatan BK / sekolah
  - Notifikasi internal aplikasi (misal: permohonan konseling baru, jadwal konseling, dsb.)

- **Arsip & Kenaikan Kelas**
  - Arsip alumni
  - Proses kenaikan kelas siswa per tahun ajaran

- **Dashboard**
  - Dashboard guru BK (ringkasan jumlah siswa, pelanggaran, konsultasi, dsb.)
  - Dashboard siswa (informasi pribadi, riwayat konseling, pelanggaran, pengumuman, dsb.)

- **Hak Akses / Role**
  - Autentikasi (login / logout)
  - Role pengguna, misalnya:
    - `guru_bk` / admin
    - `siswa`
  - Menu dan fitur ditampilkan sesuai role

---

## 🧰 Teknologi yang Digunakan

- **Backend**: Laravel (lihat versi pada `composer.json`)
- **Bahasa Pemrograman**: PHP
- **Database**: MySQL / MariaDB
- **Frontend**:
  - Blade Template Engine
  - Tailwind CSS
  - Alpine.js (interaksi sederhana di frontend)
- **Tools Pendukung**:
  - Composer
  - Node.js & NPM (untuk build asset jika diperlukan)

---

## ⚙️ Kebutuhan Sistem

- PHP ≥ 8.1
- Composer
- MySQL / MariaDB
- Node.js & NPM (opsional, untuk `npm run dev` / `npm run build`)
- Git

---

## 🔧 Instalasi Project Secara Lokal

1. **Clone repository**

   ```bash
   git clone https://github.com/Ants115/BK-WEBSITE.git
   cd BK-WEBSITE

git switch master
# atau
git checkout master
composer install
cp .env.example .env (jika menggunakan mac/linux)
copy .env.example .env (jika menggunakan windows)
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
