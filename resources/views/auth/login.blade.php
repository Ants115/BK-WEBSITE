<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SMK Antartika 1 Sidoarjo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex">
        
        {{-- BAGIAN KIRI: FORM LOGIN --}}
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                {{-- Logo & Judul --}}
                <div class="mb-10">
                    <a href="/" class="inline-flex items-center gap-2 text-indigo-600 font-bold text-xl hover:opacity-80 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Depan
                    </a>
                    <h2 class="mt-8 text-3xl font-extrabold text-gray-900">
                        Selamat Datang! 👋
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Silakan masuk untuk mengakses layanan BK.
                    </p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Sekolah</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="nama@siswa.smk">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Ingat Saya
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Lupa password?
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Login --}}
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform hover:-translate-y-0.5">
                            Masuk Akun
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500">
                        Daftar Siswa Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: GAMBAR DEKORATIF --}}
        <div class="hidden lg:block relative w-0 flex-1">
            <div class="absolute inset-0 h-full w-full bg-gradient-to-br from-indigo-500 to-blue-600">
                {{-- Pattern Overlay --}}
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
                
                {{-- Konten Dekoratif --}}
                <div class="flex flex-col items-center justify-center h-full px-12 text-center text-white">
                    <div class="bg-white/10 backdrop-blur-lg p-4 rounded-2xl mb-6 shadow-2xl border border-white/20">
                         {{-- Ilustrasi Sederhana (SVG) --}}
                         <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4">Sistem Informasi BK</h2>
                    <p class="text-indigo-100 max-w-md text-lg leading-relaxed">
                        "Pendidikan bukan persiapan untuk hidup; pendidikan adalah kehidupan itu sendiri."
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>