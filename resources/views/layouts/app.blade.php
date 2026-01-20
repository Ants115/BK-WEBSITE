<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F5F6F8; }
        .bk-scroll::-webkit-scrollbar { width: 6px; }
        .bk-scroll::-webkit-scrollbar-thumb { background-color: rgba(148, 163, 184, 0.5); border-radius: 99px; }
    </style>
</head>
<body class="text-slate-800">
    <div class="min-h-screen flex flex-col md:flex-row">

        {{-- 1. INCLUDE NAVIGATION SEBAGAI SIDEBAR --}}
        {{-- Logika menu (Siswa/Wali/Guru) tetap aman di dalam file ini --}}
        @include('layouts.navigation')

        {{-- 2. KONTEN UTAMA --}}
        <main class="flex-1 bg-[#F5F6F8] min-h-screen flex flex-col min-w-0 overflow-hidden">
            
            {{-- Header Mobile (Hanya muncul di layar kecil) --}}
            <div class="md:hidden bg-white border-b border-slate-200 p-4 flex items-center justify-between sticky top-0 z-20">
                <div class="font-bold text-lg text-slate-800">BK Digital</div>
                <button id="mobileMenuBtn" class="p-2 rounded-md bg-slate-100 text-slate-600">
                    <i class="ri-menu-line"></i>
                </button>
            </div>

            {{-- Slot Konten --}}
            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    {{-- Header Halaman (Support $header dari dashboard) --}}
                    @isset($header)
                        <header class="mb-8">
                            <h1 class="text-2xl font-bold text-slate-800">
                                {{ $header }}
                            </h1>
                        </header>
                    @endisset

                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    {{-- 3. KOMPONEN TAMBAHAN (Yang sempat hilang) --}}
    <x-confirm-delete-modal />

    {{-- Script Toggle Mobile --}}
    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('nav.sidebar-nav'); // Class ini ada di navigation.blade.php
        
        if(btn && sidebar) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
</body>
</html>