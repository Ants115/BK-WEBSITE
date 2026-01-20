<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bimbingan Konseling - SMK Antartika 1 Sidoarjo</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    {{-- NAVBAR SEDERHANA --}}
    <nav class="absolute top-0 left-0 w-full z-20 px-6 py-6 md:px-12 flex justify-between items-center">
        {{-- Logo Sekolah --}}
        <div class="flex items-center gap-3">
            {{-- Ganti src dengan path logo sekolahmu --}}
            {{-- <img src="{{ asset('img/logo-sekolah.png') }}" class="h-10 w-auto" alt="Logo"> --}}
            
            {{-- Placeholder Logo (Jika belum ada gambar) --}}
            <div class="h-10 w-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                BK
            </div>
            
            <div class="leading-tight">
                <h1 class="font-bold text-gray-900 text-lg">SMK ANTARTIKA 1</h1>
                <p class="text-xs text-gray-500 font-medium tracking-wide">SISTEM INFORMASI BK</p>
            </div>
        </div>

        {{-- Auth Links (Pojok Kanan) --}}
        <div class="hidden md:flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                        Dashboard &rarr;
                    </a>
                @else
                    
                @endauth
            @endif
        </div>
    </nav>

    {{-- HERO SECTION (Split Screen) --}}
    <div class="relative min-h-screen flex flex-col md:flex-row items-center justify-center overflow-hidden">
        
        {{-- Background Shape Decoration --}}
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-indigo-50 rounded-full blur-3xl opacity-50 -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-3xl opacity-50 -z-10"></div>

        {{-- KIRI: Teks & CTA --}}
        <div class="w-full md:w-1/2 px-6 md:px-16 lg:px-24 pt-24 md:pt-0 flex flex-col justify-center z-10">
            
            <div class="inline-flex self-start items-center gap-2 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                Portal Layanan Siswa
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                Teman Cerita & <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">
                    Masa Depanmu.
                </span>
            </h1>

            <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-lg">
                Selamat datang di portal Bimbingan Konseling SMK Antartika 1. 
                Platform ini hadir untuk membantu pengembangan diri, konsultasi karir, 
                dan pendampingan masalah siswa secara privat dan aman.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition transform hover:-translate-y-1 text-center">
                            Akses Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-black transition transform hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Masuk Akun
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 border-2 border-indigo-100 font-bold rounded-xl hover:border-indigo-600 hover:bg-indigo-50 transition text-center">
                                Daftar Siswa Baru
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="mt-10 flex items-center gap-4 text-sm text-gray-500">
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white"></div>
                    <div class="w-8 h-8 rounded-full bg-gray-300 border-2 border-white"></div>
                    <div class="w-8 h-8 rounded-full bg-gray-400 border-2 border-white"></div>
                </div>
                <p>Bergabung dengan <span class="font-bold text-gray-800">1,200+ Siswa</span> lainnya.</p>
            </div>
        </div>

        {{-- KANAN: Ilustrasi / Gambar (Visual) --}}
        <div class="w-full md:w-1/2 h-[50vh] md:h-screen relative bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center">
            
            {{-- Pattern Dot --}}
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 32px 32px;"></div>

            {{-- Ilustrasi Utama (Gunakan SVG atau Gambar Sekolah) --}}
            <div class="relative w-4/5 max-w-md z-10">
                {{-- KOTAK INFORMASI MELAYANG --}}
                <div class="absolute -top-12 -left-8 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 animate-bounce-slow">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Status Konseling</p>
                            <p class="text-sm font-bold text-gray-800">Privasi Terjaga 🔒</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-12 -right-4 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 animate-bounce-slow" style="animation-delay: 1s;">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Respon Guru</p>
                            <p class="text-sm font-bold text-gray-800">Cepat & Ramah</p>
                        </div>
                    </div>
                </div>
                
                {{-- GAMBAR ILUSTRASI UTAMA --}}
                {{-- Kamu bisa ganti src ini dengan gambar gedung sekolah atau ilustrasi siswa --}}
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/online-education-illustration-download-in-svg-png-gif-file-formats--learning-logo-teacher-study-school-pack-illustrations-4609696.png" 
                     alt="Counseling Illustration" 
                     class="w-full h-auto drop-shadow-2xl transform hover:scale-105 transition duration-500">
            </div>
        </div>
    </div>

    {{-- SECTION LAYANAN RINGKAS (Opsional) --}}
    <div class="bg-white py-12 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition border border-gray-100">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center text-white mb-4 mx-auto md:mx-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Konseling Pribadi</h3>
                <p class="text-gray-600 text-sm">Ceritakan masalahmu secara rahasia kepada Guru BK yang siap mendengarkan.</p>
            </div>
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-blue-50 transition border border-gray-100">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white mb-4 mx-auto md:mx-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Bimbingan Karir</h3>
                <p class="text-gray-600 text-sm">Konsultasi mengenai rencana kuliah, kerja, atau wirausaha setelah lulus.</p>
            </div>
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-green-50 transition border border-gray-100">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center text-white mb-4 mx-auto md:mx-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Catatan Kedisiplinan</h3>
                <p class="text-gray-600 text-sm">Pantau poin pelanggaran dan prestasi siswa secara transparan dan real-time.</p>
            </div>
        </div>
    </div>

    {{-- FOOTER SIMPLE --}}
    <footer class="bg-gray-50 py-8 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} SMK Antartika 1 Sidoarjo. Sistem Informasi Bimbingan Konseling.
    </footer>

    {{-- CUSTOM CSS ANIMATION --}}
    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite ease-in-out;
        }
    </style>
</body>
</html>