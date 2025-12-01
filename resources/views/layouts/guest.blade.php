<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem BK') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-slate-950 relative overflow-hidden">
        {{-- Background gradient + blur blobs --}}
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/30 via-slate-900 to-sky-500/20"></div>
        <div class="pointer-events-none absolute -left-24 -top-32 h-72 w-72 rounded-full bg-indigo-400/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-16 top-1/3 h-80 w-80 rounded-full bg-sky-400/40 blur-3xl"></div>
        <div class="pointer-events-none absolute left-1/2 bottom-[-6rem] h-72 w-72 -translate-x-1/2 rounded-full bg-purple-400/30 blur-3xl"></div>

        {{-- Card utama login/register --}}
        <div class="relative w-full max-w-5xl">
            <div class="bg-white/10 backdrop-blur-2xl border border-white/15 rounded-3xl shadow-2xl overflow-hidden">
                <div class="grid md:grid-cols-2 gap-0">
                    {{-- Panel kiri: info singkat sistem --}}
                    <div class="hidden md:flex flex-col justify-between p-8 border-r border-white/10 bg-gradient-to-br from-slate-900/90 via-slate-900 to-indigo-900/90 text-slate-100">
                        <div>
                            <p class="text-xs font-medium text-indigo-300 mb-2 tracking-wide uppercase">
                                Sistem Informasi Bimbingan Konseling
                            </p>
                            <h1 class="text-2xl font-semibold mb-3">
                                BK digital untuk catatan yang rapi dan aman.
                            </h1>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                Kelola konsultasi, pelanggaran, dan perkembangan siswa tanpa takut buku hilang
                                atau catatan tercecer. Semua tersimpan rapi di satu sistem terpusat.
                            </p>
                        </div>

                        <div class="mt-6 text-[11px] text-slate-400">
                            Dibangun untuk mendukung UKK RPL dan kebutuhan BK di SMK.
                        </div>
                    </div>

                    {{-- Panel kanan: form login/register --}}
                    <div class="p-6 sm:p-8 bg-white/90">
                        {{-- Logo di atas form (jika diset di view) --}}
                        @isset($logo)
                            <div class="flex justify-center mb-4">
                                {{ $logo }}
                            </div>
                        @endisset

                        {{-- Judul global (kalau di file login/register sudah ada judul sendiri dan terasa dobel, boleh hapus bagian ini) --}}
                        <h2 class="text-xl font-semibold text-slate-900 mb-1">
                            @yield('auth-title', 'Masuk ke sistem')
                        </h2>
                        <p class="text-xs text-slate-500 mb-4">
                            Gunakan akun yang sudah terdaftar. Siswa dan Admin BK masuk dari halaman yang sama.
                        </p>

                        {{-- Konten utama (form login / register / dll) --}}
                        <div class="text-sm text-slate-700">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
