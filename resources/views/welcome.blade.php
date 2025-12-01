<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Bimbingan Konseling</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 antialiased">
<div class="min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                {{-- Logo BK – pastikan file ada di: public/images/logo-bk.png --}}
                <img src="{{ asset('images/logo.png') }}" alt="Logo BK" class="h-9 w-auto">
                <div class="flex flex-col">
                    <span class="font-semibold text-sm md:text-base text-slate-800">
                        Sistem Informasi Bimbingan Konseling
                    </span>
                    <span class="text-[10px] md:text-xs text-slate-500 tracking-wide">
                        SMK ANTARTIKA 1 SDA
                    </span>
                </div>
            </a>

            <div class="flex items-center gap-3 text-xs md:text-sm">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 border border-slate-300 rounded-full hover:bg-slate-50 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 shadow-sm transition">
                    Daftar Siswa
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1">

        {{-- HERO --}}
        <section class="relative overflow-hidden">
            {{-- background gradient + blobs --}}
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-slate-50 to-sky-50"></div>
            <div class="pointer-events-none absolute -left-20 -top-24 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-16 top-1/3 h-80 w-80 rounded-full bg-sky-300/40 blur-3xl"></div>
            <div class="pointer-events-none absolute left-1/2 bottom-[-6rem] h-72 w-72 -translate-x-1/2 rounded-full bg-purple-300/30 blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 border border-indigo-100 text-[11px] font-medium text-indigo-700 mb-4">
                        Sistem BK Digital • Versi UKK RPL
                    </p>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 leading-tight">
                        Catatan BK lebih rapi, aman, dan
                        <span class="text-indigo-600">siap diakses kapan saja.</span>
                    </h1>
                    <p class="text-slate-600 mb-6 text-sm md:text-base max-w-xl">
                        Di era digital, buku catatan BK mudah hilang, rusak, atau salah tulis.
                        Sistem ini membantu Guru BK dan Wali Kelas menyimpan data konsultasi,
                        pelanggaran, dan perkembangan siswa secara terpusat, terstruktur, dan mudah dicari.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 text-sm">
                        <a href="{{ route('login') }}"
                           class="px-5 py-3 bg-indigo-600 text-white rounded-full shadow-md hover:bg-indigo-700 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-indigo-700 hover:underline">
                            Daftar akun siswa baru
                        </a>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-100 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Fitur utama sistem
                    </h2>
                    <ul class="space-y-2 text-sm text-slate-600 list-disc list-inside">
                        <li>Pengajuan jadwal konsultasi siswa secara online.</li>
                        <li>Manajemen poin pelanggaran dan riwayat catatan siswa.</li>
                        <li>Dashboard statistik untuk Guru BK dan wali kelas.</li>
                        <li>Proses kenaikan kelas dan arsip alumni otomatis.</li>
                    </ul>
                    <p class="text-xs text-slate-400 pt-2">
                        Dibangun dengan Laravel &amp; Tailwind untuk mendukung UKK RPL
                        dan pengelolaan BK di sekolah.
                    </p>
                </div>
            </div>
        </section>

        {{-- TENTANG SISTEM --}}
        <section class="bg-white">
            <div class="max-w-5xl mx-auto px-6 py-12 space-y-4">
                <h2 class="inline-flex items-center gap-2 text-2xl font-semibold text-slate-900">
                    <span class="h-6 w-1 rounded-full bg-indigo-500"></span>
                    Tentang Sistem Informasi BK
                </h2>
                <p class="text-sm md:text-base text-slate-700 leading-relaxed">
                    Sistem Informasi Bimbingan Konseling ini dirancang sebagai pengganti pencatatan manual
                    di buku atau kertas. Melalui aplikasi ini, Guru BK dan Wali Kelas dapat mencatat
                    konsultasi, pelanggaran, serta perkembangan siswa dari awal masuk hingga lulus secara
                    terstruktur dan terdokumentasi dengan baik.
                </p>
                <p class="text-sm md:text-base text-slate-700 leading-relaxed">
                    Tujuannya bukan hanya untuk memenuhi tugas UKK, tetapi benar-benar menjadi alat bantu
                    sekolah dalam memantau kondisi siswa, mempermudah pembuatan laporan, dan memastikan
                    setiap keputusan pembinaan didukung oleh data yang lengkap dan akurat.
                </p>
            </div>
        </section>

        {{-- MENGAPA BK DIGITAL PENTING --}}
        <section class="bg-slate-900">
            <div class="max-w-5xl mx-auto px-6 py-12">
                <h2 class="inline-flex items-center gap-2 text-2xl font-semibold text-slate-50 mb-4">
                    <span class="h-6 w-1 rounded-full bg-indigo-400"></span>
                    Mengapa BK digital lebih baik daripada catatan manual?
                </h2>
                <div class="grid md:grid-cols-2 gap-8 text-sm md:text-base text-slate-100">
                    <ul class="space-y-3 list-disc list-inside">
                        <li>
                            <span class="font-semibold">Catatan tidak mudah hilang.</span><br>
                            Buku pelanggaran bisa tercecer, rusak, atau hilang.
                            Di sistem digital, data disimpan di database dan dapat di-backup.
                        </li>
                        <li>
                            <span class="font-semibold">Mengurangi kesalahan pencatatan.</span><br>
                            Form terstruktur membantu mengurangi salah tulis, duplikasi, atau data yang terlewat.
                        </li>
                        <li>
                            <span class="font-semibold">Riwayat siswa selalu lengkap.</span><br>
                            Guru BK atau wali kelas baru tetap bisa melihat histori siswa dari tahun-tahun sebelumnya.
                        </li>
                    </ul>
                    <ul class="space-y-3 list-disc list-inside">
                        <li>
                            <span class="font-semibold">Pencarian data jauh lebih cepat.</span><br>
                            Data siswa dapat ditemukan hanya dengan nama atau NIS, tanpa membolak-balik buku.
                        </li>
                        <li>
                            <span class="font-semibold">Mudah membuat rekap dan laporan.</span><br>
                            Data yang tersimpan dapat direkap per kelas, jenis pelanggaran, atau periode secara otomatis.
                        </li>
                        <li>
                            <span class="font-semibold">Mendukung pengambilan keputusan.</span><br>
                            Data yang rapi membuat pembinaan dan kebijakan disiplin lebih objektif dan terukur.
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- FITUR UTAMA --}}
        <section class="bg-slate-950">
            <div class="max-w-6xl mx-auto px-6 py-12">
                <h2 class="inline-flex items-center gap-2 text-2xl font-semibold text-slate-50 mb-6">
                    <span class="h-6 w-1 rounded-full bg-indigo-400"></span>
                    Fitur utama yang tersedia
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
                    <div class="bg-gradient-to-br from-indigo-500/10 via-slate-800 to-slate-900 border border-slate-800 rounded-xl p-4 text-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <h3 class="font-semibold text-slate-50 mb-2">Konsultasi Siswa</h3>
                        <p>Pengajuan jadwal konsultasi, pemilihan guru BK, dan riwayat pertemuan tersimpan rapi.</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500/10 via-slate-800 to-slate-900 border border-slate-800 rounded-xl p-4 text-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <h3 class="font-semibold text-slate-50 mb-2">Pelanggaran &amp; Poin</h3>
                        <p>Pencatatan jenis pelanggaran, poin, dan total akumulasi setiap siswa.</p>
                    </div>
                    <div class="bg-gradient-to-br from-sky-500/10 via-slate-800 to-slate-900 border border-slate-800 rounded-xl p-4 text-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <h3 class="font-semibold text-slate-50 mb-2">Kenaikan Kelas</h3>
                        <p>Proses pindah kelas dan kelulusan siswa dengan pemetaan otomatis per tingkatan.</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500/10 via-slate-800 to-slate-900 border border-slate-800 rounded-xl p-4 text-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <h3 class="font-semibold text-slate-50 mb-2">Arsip Alumni</h3>
                        <p>Data siswa yang sudah lulus dipindahkan ke arsip alumni tanpa menghapus riwayatnya.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-800 bg-slate-950">
        <div class="max-w-7xl mx-auto px-6 py-4 text-[11px] text-slate-500 text-center">
            &copy; {{ date('Y') }} Sistem Informasi Bimbingan Konseling.
            Dibuat oleh Andhi Lukman. {{-- ganti dengan namamu jika perlu --}}
        </div>
    </footer>
</div>
</body>
</html>
