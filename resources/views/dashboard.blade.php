<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. HERO SECTION: Sapaan Hangat --}}
            <div class="relative bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-8 md:p-10 shadow-xl overflow-hidden text-white">
                {{-- Dekorasi Background --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h1 class="text-3xl font-extrabold mb-2">Halo, {{ $user->name }}! 👋</h1>
                        <p class="text-indigo-100 text-lg max-w-xl">
                            Selamat datang di ruang BK Digital. Jangan ragu untuk bercerita, kami ada di sini untuk mendukungmu.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('konsultasi.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-gray-50 hover:scale-105 transition transform duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Buat Janji Konsultasi
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. ALERT / PENGINGAT (Menggunakan variabel $konsultasiMendatang) --}}
            @if($konsultasiMendatang)
            <div class="bg-white border-l-4 border-blue-500 rounded-xl shadow-sm p-6 flex items-start gap-4">
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-800 text-lg">Pengingat Jadwal Konsultasi</h4>
                    <p class="text-gray-600 mt-1">
                        Kamu memiliki jadwal dengan <span class="font-bold text-blue-600">{{ $konsultasiMendatang->guru->name ?? 'Guru BK' }}</span> pada:
                    </p>
                    <div class="mt-3 flex items-center gap-2 text-sm font-semibold text-gray-700 bg-gray-50 px-3 py-2 rounded-lg w-fit">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::parse($konsultasiMendatang->jadwal_disetujui)->translatedFormat('l, d F Y - H:i') }}
                    </div>
                </div>
                {{-- Link ini mengarah ke riwayat, sama seperti kode lama --}}
                <a href="{{ route('konsultasi.riwayat') }}" class="text-sm font-bold text-blue-600 hover:underline mt-1">Lihat Detail &rarr;</a>
            </div>
            @endif

            {{-- 3. STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Card Konsultasi --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Konsultasi</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">
                            {{ $totalPengajuan ?? '0' }}
                        </h3>
                        <span class="text-sm text-gray-500 mb-1">Kali</span>
                    </div>
                </div>

                {{-- Card Prestasi --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Prestasi</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">
                            {{ $totalPrestasi ?? '0' }}
                        </h3>
                        <span class="text-sm text-gray-500 mb-1">Pencapaian</span>
                    </div>
                    {{-- Link ke prestasi index --}}
                    <a href="{{ route('siswa.prestasi.index') }}" class="text-xs text-green-600 font-bold mt-2 block hover:underline">Lihat Detail &rarr;</a>
                </div>

                {{-- Card Pelanggaran --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Poin Pelanggaran</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">
                            {{ $totalPoin ?? '0' }}
                        </h3>
                        <span class="text-sm text-gray-500 mb-1">Poin</span>
                    </div>
                    {{-- Progress Bar Visual --}}
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ min(($totalPoin ?? 0), 100) }}%"></div>
                    </div>
                </div>
            </div>

            {{-- 4. GRID UTAMA: PROFIL & RIWAYAT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: INFO SISWA --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                        <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </h3>

                        <div class="flex flex-col items-center mb-6">
                            {{-- Avatar Inisial Nama --}}
                            <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-3xl font-bold border-4 border-white shadow-lg mb-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 text-center">{{ $user->biodataSiswa->nama_lengkap ?? $user->name }}</h4>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full mt-1">Siswa Aktif</span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                <span class="text-gray-500 text-sm">NIS</span>
                                <span class="font-bold text-gray-800 text-sm">{{ $user->biodataSiswa->nis ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                <span class="text-gray-500 text-sm">Kelas</span>
                                <span class="font-bold text-gray-800 text-sm">
                                    {{ $user->biodataSiswa->kelas->nama_kelas ?? '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                <span class="text-gray-500 text-sm">Email</span>
                                <span class="font-bold text-gray-800 text-sm truncate max-w-[150px]">{{ $user->email }}</span>
                            </div>
                            <div class="pt-2">
                                <span class="text-gray-500 text-sm block mb-1">Wali Kelas</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                                    <span class="font-bold text-gray-800 text-sm">
                                        {{ $user->biodataSiswa->kelas->waliKelas->name ?? 'Belum ditentukan' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: RIWAYAT PELANGGARAN --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Riwayat Pelanggaran
                            </h3>
                            <span class="text-xs text-gray-400">Terbaru</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                                    <tr>
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Pelanggaran</th>
                                        <th class="px-6 py-4 text-center">Poin</th>
                                        <th class="px-6 py-4">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    {{-- Loop Data Pelanggaran (Menggunakan variabel lama: $user->pelanggaranSiswa) --}}
                                    @forelse($user->pelanggaranSiswa as $item)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-gray-600">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $item->pelanggaran->nama_pelanggaran ?? 'N/A' }}
                                                @if($item->keterangan)
                                                    <div class="text-xs text-gray-500 mt-1 italic">{{ $item->keterangan }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-2 py-1 bg-red-100 text-red-600 font-bold rounded">
                                                    +{{ $item->pelanggaran->poin ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500">
                                                {{ $item->pelapor->name ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- State Kosong --}}
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-3">
                                                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </div>
                                                    <p class="text-gray-900 font-bold">Bersih! Tidak ada pelanggaran.</p>
                                                    <p class="text-gray-500 text-xs mt-1">Pertahankan sikap baikmu di sekolah.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>