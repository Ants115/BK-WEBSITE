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
    
    {{-- PERBAIKAN: Script ReCaptcha dipindah ke sini (Standard HTML yang benar) --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F5F6F8; }
        .bk-scroll::-webkit-scrollbar { width: 6px; }
        .bk-scroll::-webkit-scrollbar-thumb { background-color: rgba(148, 163, 184, 0.5); border-radius: 99px; }
    </style>
</head>
@if(Auth::user()->role === 'siswa') {{-- Hanya muncul di Siswa --}}
    
    {{-- 1. KOTAK CHAT (Default: Hidden) --}}
    <div id="chat-popup" class="fixed bottom-24 right-6 w-80 h-96 bg-white shadow-2xl rounded-xl overflow-hidden z-50 hidden border border-gray-200 transition-all transform origin-bottom-right scale-95 opacity-0">
        {{-- Load Widget via Iframe --}}
        <iframe src="{{ route('chat.widget') }}" class="w-full h-full border-0"></iframe>
    </div>

    {{-- 2. TOMBOL BULAT --}}
    <button onclick="toggleChat()" 
        class="fixed bottom-6 right-6 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg z-50 flex items-center justify-center transition-transform hover:scale-110">
        {{-- Ikon Chat --}}
        <i class="ri-chat-3-line text-2xl" id="chat-icon-open"></i>
        {{-- Ikon Close (Default: Hidden) --}}
        <i class="ri-close-line text-2xl hidden" id="chat-icon-close"></i>
    </button>

    {{-- 3. JAVASCRIPT TOGGLE --}}
    <script>
        function toggleChat() {
            const popup = document.getElementById('chat-popup');
            const iconOpen = document.getElementById('chat-icon-open');
            const iconClose = document.getElementById('chat-icon-close');

            if (popup.classList.contains('hidden')) {
                // Buka Chat
                popup.classList.remove('hidden');
                setTimeout(() => {
                    popup.classList.remove('scale-95', 'opacity-0');
                }, 10);
                
                // Ubah Ikon
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
            } else {
                // Tutup Chat
                popup.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    popup.classList.add('hidden');
                }, 200);

                // Ubah Ikon
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
        }
    </script>
@endif
<body class="text-slate-800">
    <div class="min-h-screen flex flex-col md:flex-row">

        {{-- 1. INCLUDE NAVIGATION SEBAGAI SIDEBAR --}}
        @include('layouts.navigation')

        {{-- 2. KONTEN UTAMA --}}
        <main class="flex-1 bg-[#F5F6F8] min-h-screen flex flex-col min-w-0 overflow-hidden">
            
            {{-- Header Mobile --}}
            <div class="md:hidden bg-white border-b border-slate-200 p-4 flex items-center justify-between sticky top-0 z-20">
                <div class="font-bold text-lg text-slate-800">BK Digital</div>
                <button id="mobileMenuBtn" class="p-2 rounded-md bg-slate-100 text-slate-600">
                    <i class="ri-menu-line"></i>
                </button>
            </div>

            {{-- Slot Konten --}}
            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    {{-- Header Halaman --}}
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

    {{-- 3. KOMPONEN TAMBAHAN --}}
    <x-confirm-delete-modal />

    {{-- Script Toggle Mobile --}}
    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('nav.sidebar-nav'); 
        
        if(btn && sidebar) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
</body>
</html>