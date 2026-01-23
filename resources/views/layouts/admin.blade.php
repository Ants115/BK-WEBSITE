<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin BK')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font & Icon --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

    {{-- Tailwind via Vite (sesuaikan dengan project-mu) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #F5F6F8;
        }

        /* Scrollbar halus untuk sidebar */
        .bk-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.7) transparent;
        }
        .bk-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .bk-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .bk-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(148, 163, 184, 0.7);
            border-radius: 9999px;
        }

        /* Transition sederhana */
        .bk-transition {
            transition: all 180ms ease-out;
        }

        /* State aktif sidebar */
        .bk-nav-item-active {
            background-color: #E0F2FE;
            color: #0369A1;
        }
        .bk-nav-indicator {
            width: 3px;
            border-radius: 9999px;
        }
    </style>

    @stack('styles')
</head>
<body class="text-slate-800">
<div class="min-h-screen flex flex-col">

    {{-- HEADER --}}
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                {{-- Kiri: logo + breadcrumb --}}
                <div class="flex items-center gap-3">
                    {{-- Tombol buka sidebar di mobile --}}
                    <button id="sidebarToggle"
                            class="lg:hidden inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bk-transition hover:bg-slate-50">
                        <i class="ri-menu-2-line text-lg"></i>
                    </button>

                    <div class="flex items-center gap-2">
                        {{-- Ganti dengan asset logo asli --}}
                        <div class="w-9 h-9 rounded-xl bg-sky-600 flex items-center justify-center text-white font-semibold">
                            BK
                        </div>
                        <div class="hidden sm:flex flex-col leading-tight">
                            <span class="text-sm font-semibold">Sistem Informasi BK</span>
                            <span class="text-xs text-slate-500">SMK / SMA — Admin Panel</span>
                        </div>
                    </div>

                    {{-- Breadcrumb / judul halaman --}}
                    <div class="hidden md:block ml-6">
                        <p class="text-xs uppercase tracking-wide text-slate-400 mb-0.5">
                            @yield('breadcrumb_label', 'Dashboard')
                        </p>
                        <p class="text-sm font-medium text-slate-700">
                            @yield('breadcrumb', 'Dashboard / Admin Dashboard')
                        </p>
                    </div>
                </div>

                {{-- Kanan: notifikasi + user --}}
                <div class="flex items-center gap-4">
                    <button class="relative inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 hover:bg-slate-50 bk-transition">
                        <i class="ri-notification-3-line text-lg text-slate-600"></i>
                        {{-- contoh badge notif --}}
                        {{-- <span class="absolute -top-0.5 -right-0.5 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-rose-500 text-[10px] text-white">3</span> --}}
                    </button>

                    <div class="relative">
                        <button
                            class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-2.5 py-1.5 bk-transition hover:bg-slate-50">
                            <div class="w-8 h-8 rounded-full bg-violet-500 flex items-center justify-center text-white text-sm font-semibold">
                                A
                            </div>
                            <div class="hidden sm:flex flex-col items-start">
                                <span class="text-xs font-medium text-slate-700 leading-tight">Admin BK</span>
                                <span class="text-[11px] text-slate-400 leading-tight">admin@bksekolah.sch.id</span>
                            </div>
                            <i class="ri-arrow-down-s-line text-slate-500 text-lg"></i>
                        </button>

                        {{-- dropdown user (opsional, isi sendiri) --}}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 min-h-0">
        {{-- SIDEBAR --}}
        <aside id="adminSidebar"
               class="fixed inset-y-0 left-0 z-40 w-72 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto bk-transition flex flex-col">
            <div class="flex-1 overflow-y-auto bk-scroll">
                <div class="px-4 pt-4 pb-2 lg:hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Menu Admin BK</span>
                        <button id="sidebarClose" class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-slate-100">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </div>
                </div>

                <nav class="px-3 pb-6 pt-3 space-y-6 text-sm">
                    {{-- NAVIGASI UTAMA --}}
                    <div>
                        <p class="px-2 mb-1 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">
                            Navigasi Utama
                        </p>
                        <x-admin-nav-item icon="ri-dashboard-line"
                            :active="request()->routeIs('admin.dashboard')"
                            :href="route('admin.dashboard')">
                            Admin Dashboard
                        </x-admin-nav-item>

                        <x-admin-nav-item icon="ri-chat-3-line"
                            :active="request()->routeIs('admin.konsultasi.*')"
                            :href="route('admin.konsultasi.index')">
                            Konsultasi Siswa
                        </x-admin-nav-item>

                        <x-admin-nav-item icon="ri-file-list-3-line"
                            :active="request()->routeIs('admin.laporan-konsultasi.*')"
                            :href="route('admin.laporan-konsultasi.index')">
                            Laporan Konsultasi
                        </x-admin-nav-item>
                    </div>

                    {{-- SISWA & KELAS --}}
                    <div>
                        <p class="px-2 mb-1 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">
                            Siswa &amp; Kelas
                        </p>
                        <x-admin-nav-item icon="ri-team-line"
                            :active="request()->routeIs('admin.siswa.*')"
                            :href="route('admin.siswa.index')">
                            Daftar Siswa
                        </x-admin-nav-item>

                        <x-admin-nav-item icon="ri-skip-up-line"
                            :active="request()->routeIs('admin.kenaikan-kelas.*')"
                            :href="route('admin.kenaikan-kelas.index')">
                            Kenaikan Kelas
                        </x-admin-nav-item>
                    </div>

                    {{-- PELANGGARAN & PRESTASI --}}
                    <div>
                        <p class="px-2 mb-1 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">
                            Pelanggaran &amp; Prestasi
                        </p>
                        <x-admin-nav-item icon="ri-alert-line"
                            :active="request()->routeIs('admin.pelanggaran.*')"
                            :href="route('admin.pelanggaran.index')">
                            Manajemen Pelanggaran
                        </x-admin-nav-item>

                        <x-admin-nav-item icon="ri-medal-line"
                            :active="request()->routeIs('admin.prestasi.*')"
                            :href="route('admin.prestasi.index')">
                            Prestasi Siswa
                        </x-admin-nav-item>
                    </div>

                    {{-- ARSIP & GURU BK --}}
                    <div>
                        <p class="px-2 mb-1 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">
                            Arsip &amp; Guru BK
                        </p>
                        <x-admin-nav-item icon="ri-archive-line"
                            :active="request()->routeIs('admin.arsip-alumni.*')"
                            :href="route('admin.arsip-alumni.index')">
                            Arsip Alumni
                        </x-admin-nav-item>

                        <x-admin-nav-item icon="ri-user-star-line"
                            :active="request()->routeIs('admin.guru-bk.*')"
                            :href="route('admin.guru-bk.index')">
                            Guru BK
                        </x-admin-nav-item>

                        <x-admin-nav-item icon="ri-user-3-line"
                            :active="request()->routeIs('admin.staf-guru.*')"
                            :href="route('admin.staf-guru.index')">
                            Staf Guru
                        </x-admin-nav-item>
                      
                    </div>
                </nav>
            </div>
        </aside>

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 min-w-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                {{-- Heading halaman --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-slate-900">
                        @yield('page_title', 'Admin Dashboard')
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        @yield('page_subtitle', 'Ringkasan kondisi terkini Bimbingan Konseling.')
                    </p>
                </div>

                @yield('content')
            </div>
        </main>
    </div>
</div>

{{-- Komponen nav item, simpan di resources/views/components/admin-nav-item.blade.php --}}
{{-- Kode ada di bawah jawaban ini --}}

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('adminSidebar');
        const toggle = document.getElementById('sidebarToggle');
        const close = document.getElementById('sidebarClose');

        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });
        }
        if (close) {
            close.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>