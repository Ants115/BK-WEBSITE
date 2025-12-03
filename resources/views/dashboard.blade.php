<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- BAGIAN ATAS: Salam + Statistik Konsultasi --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($konsultasiMendatang)
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 shadow-sm rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Pengingat Jadwal Konsultasi</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Kamu memiliki jadwal konsultasi dengan <strong>{{ $konsultasiMendatang->guru->name }}</strong></p>
                                <p class="mt-1 font-bold">
                                    📅 {{ \Carbon\Carbon::parse($konsultasiMendatang->jadwal_disetujui)->translatedFormat('l, d F Y') }} 
                                    ⏰ Pukul {{ \Carbon\Carbon::parse($konsultasiMendatang->jadwal_disetujui)->format('H:i') }}
                                </p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('konsultasi.riwayat') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 underline">
                                    Lihat Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Halo, {{ $user->name }}! 👋</h3>
                    <p class="text-gray-600">Selamat datang di Sistem Informasi Bimbingan Konseling.</p>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border rounded-lg bg-gray-50">
                            <p class="text-gray-500 text-sm">Total Konsultasi Anda</p>
                            <p class="text-2xl font-bold">
                                {{ $totalPengajuan }} <span class="text-sm font-normal">kali</span>
                            </p>
                        </div>
                        
                        <div class="p-4 border rounded-lg bg-gray-50">
                            <p class="text-gray-500 text-sm">Status Akun</p>
                            <p class="text-2xl font-bold text-green-600">Aktif</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('konsultasi.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Buat Janji Konsultasi Baru
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- BAGIAN BAWAH: Informasi Siswa, Pelanggaran, dan Prestasi --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom kiri: Informasi Siswa + Pelanggaran --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-6">Informasi Siswa</h3>

                            @if (session('success'))
                                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                    <p>{{ session('success') }}</p>
                                </div>
                            @endif

                            {{-- Profil Siswa + Wali Kelas --}}
                            <div class="mb-6 p-4 border rounded-lg">
                                <h4 class="font-semibold mb-2">Profil Anda</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p><strong>Nama:</strong> {{ $user->biodataSiswa->nama_lengkap ?? $user->name }}</p>
                                        <p><strong>NIS:</strong> {{ $user->biodataSiswa->nis ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        @php
                                            $kelas = $user->biodataSiswa?->kelas;
                                            $waliKelas = $kelas?->waliKelas;
                                        @endphp
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Kelas:</strong> {{ $kelas->nama_kelas ?? 'N/A' }}</p>
                                        <p>
                                            <strong>Wali Kelas:</strong>
                                            {{ $waliKelas->name ?? 'Belum ditetapkan' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Poin Pelanggaran --}}
                            <div class="mb-6 p-4 border border-red-400 bg-red-100 text-red-700 rounded-lg">
                                <p class="text-xl font-bold">
                                    Total Poin Pelanggaran Anda: {{ $totalPoin }}
                                </p>
                            </div>

                            {{-- Tabel Riwayat Pelanggaran --}}
                            <div>
                                <h4 class="font-semibold mb-2">Riwayat Pelanggaran</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="py-2 px-4 text-left">Tanggal</th>
                                                <th class="py-2 px-4 text-left">Pelanggaran</th>
                                                <th class="py-2 px-4 text-center">Poin</th>
                                                <th class="py-2 px-4 text-left">Keterangan</th>
                                                <th class="py-2 px-4 text-left">Dicatat oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($user->pelanggaranSiswa as $item)
                                                <tr class="border-b">
                                                    <td class="py-2 px-4">
                                                        {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}
                                                    </td>
                                                    <td class="py-2 px-4">
                                                        {{ $item->pelanggaran->nama_pelanggaran ?? 'N/A' }}
                                                    </td>
                                                    <td class="py-2 px-4 text-center">
                                                        {{ $item->pelanggaran->poin ?? 'N/A' }}
                                                    </td>
                                                    <td class="py-2 px-4">
                                                        {{ $item->keterangan ?? '-' }}
                                                    </td>
                                                    <td class="py-2 px-4">
                                                        {{ $item->pelapor->name ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="py-4 text-center text-gray-500">
                                                        Tidak ada riwayat pelanggaran.
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

                {{-- Kolom kanan: Ringkasan Prestasi --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4">Ringkasan Prestasi</h3>
                            <p class="text-sm text-gray-500">Total Prestasi Anda</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPrestasi }}</p>
                            <a href="{{ route('siswa.prestasi.index') }}"
                               class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                                Lihat detail
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
