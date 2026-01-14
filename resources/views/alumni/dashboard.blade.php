<x-app-layout>
    {{-- HANYA HEADER JIKA DIPERLUKAN (Bisa dihapus jika ingin full card) --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Alumni') }}
        </h2>
    </x-slot> --}}

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- LOGIKA PEMISAH: JIKA ALUMNI --}}
            @if(Auth::user()->biodataSiswa && Auth::user()->biodataSiswa->status === 'Lulus')
                
                {{-- KARTU UTAMA: STYLE SERTIFIKAT MODERN --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 relative">
                    
                    {{-- DEKORASI BACKGROUND --}}
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-green-400 to-teal-500"></div>
                    <div class="absolute top-4 right-6 text-white opacity-20">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 7l11 5 9-4.09V17h2V7L12 2zm1 14.55V22h-2v-5.45L4.21 13.5l.89-1.79L12 14.91l6.9-3.2.89 1.79L13 16.55z"/></svg>
                    </div>

                    <div class="relative pt-16 px-8 pb-8">
                        
                        {{-- FOTO PROFIL & BADGE --}}
                        <div class="flex flex-col md:flex-row items-center md:items-end -mt-12 mb-6 gap-6">
                            <div class="h-28 w-28 rounded-full border-4 border-white shadow-lg bg-gray-200 overflow-hidden flex items-center justify-center text-3xl font-bold text-gray-400">
                                {{ substr(Auth::user()->name, 0, 1) }}
                                {{-- Jika ada foto, ganti dengan: <img src="..." class="h-full w-full object-cover"> --}}
                            </div>
                            <div class="text-center md:text-left flex-1">
                                <h1 class="text-3xl font-bold text-gray-800">{{ Auth::user()->name }}</h1>
                                <p class="text-gray-600 font-medium flex items-center justify-center md:justify-start gap-2 mt-1">
                                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Alumni SMK Antartika 1 Sidoarjo
                                </p>
                            </div>
                            <span class="px-4 py-2 bg-green-100 text-green-700 text-sm font-bold rounded-full shadow-sm border border-green-200">
                                ✔ RESMI LULUS
                            </span>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        {{-- KONTEN UCAPAN --}}
                        <div class="bg-green-50 rounded-xl p-6 border border-green-100 mb-8">
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-white rounded-full shadow-sm text-green-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-900 mb-1">Selamat atas Kelulusan Anda! 🎓</h3>
                                    <p class="text-green-800 text-sm leading-relaxed">
                                        Perjalanan panjang telah usai, namun masa depan baru saja dimulai. 
                                        Anda telah menyelesaikan studi pada Tahun Ajaran 
                                        <strong>{{ Auth::user()->biodataSiswa->tahun_lulus ?? 'Unknown' }}</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- GRID INFORMASI SISWA --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-white hover:shadow-md transition">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nomor Induk Siswa</p>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->biodataSiswa->nis ?? '-' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-white hover:shadow-md transition">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Email Terdaftar</p>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-white hover:shadow-md transition">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Kelas Terakhir</p>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->biodataSiswa->kelas->nama_kelas ?? '-' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-white hover:shadow-md transition">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Status Akun</p>
                                <p class="text-lg font-semibold text-blue-600">Diarsipkan (Read-Only)</p>
                            </div>
                        </div>

                        {{-- FOOTER / PESAN TAMBAHAN --}}
                        <div class="text-center border-t border-gray-100 pt-6">
                            <p class="text-gray-500 text-sm mb-4 italic">
                                "Akun Anda tetap aktif sebagai arsip. Anda dapat melihat kembali riwayat konsultasi masa lalu kapan saja."
                            </p>
                            <a href="{{ route('konsultasi.riwayat') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md transform hover:-translate-y-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Lihat Riwayat Konsultasi
                            </a>
                        </div>

                    </div>
                </div>

            @else
                {{-- TAMPILAN DASHBOARD SISWA AKTIF (Kode Lama/Default) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("You're logged in as Active Student!") }}
                        {{-- Masukkan komponen dashboard siswa aktif di sini nanti --}}
                    </div>
                </div>
            @endif
                
        </div>
       
    </div>
</x-app-layout>