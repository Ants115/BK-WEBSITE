<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500">Permintaan Baru</div>
                    <div class="text-3xl font-bold">{{ $permintaanBaru }}</div>
                    @if($permintaanBaru > 0)
                        <a href="{{ route('admin.konsultasi.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Permintaan &rarr;</a>
                    @else
                        <div class="text-sm text-gray-400">Semua aman terkendali</div>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500">Konsultasi Hari Ini</div>
                    <div class="text-3xl font-bold">{{ $jadwalHariIni }}</div>
                    <div class="text-sm text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500">Total Siswa Aktif</div>
                    <div class="text-3xl font-bold">{{ $totalSiswaAktif }}</div>
                    <a href="{{ route('admin.siswa.index') }}" class="text-sm text-blue-600 hover:underline">Kelola Siswa &rarr;</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="font-bold text-lg mb-4">Agenda Konsultasi Terdekat</h3>
                    @if($jadwalTerdekat->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($jadwalTerdekat as $jadwal)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">{{ $jadwal->siswa->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $jadwal->topik }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-blue-600">
                                            {{ \Carbon\Carbon::parse($jadwal->jadwal_disetujui)->format('H:i') }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($jadwal->jadwal_disetujui)->translatedFormat('d M Y') }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Belum ada jadwal konsultasi yang akan datang.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Siswa Aktif</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalSiswaAktif }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Catatan Pelanggaran</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPelanggaran }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Statistik Lain</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">-</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Statistik Lain</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">-</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">5 Siswa dengan Poin Tertinggi</h3>
                        <ol class="list-decimal list-inside space-y-2">
                            @forelse($siswaPoinTertinggi as $siswa)
                                <li>{{ $siswa->name }} - <span class="font-bold">{{ $siswa->total_poin }} Poin</span></li>
                            @empty
                                <li class="list-none text-gray-500">Belum ada data pelanggaran.</li>
                            @endforelse
                        </ol>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">5 Pelanggaran Paling Sering Terjadi</h3>
                        <ol class="list-decimal list-inside space-y-2">
                            @forelse($pelanggaranTeratas as $pelanggaran)
                                <li>{{ $pelanggaran->nama_pelanggaran }} - <span class="font-bold">{{ $pelanggaran->jumlah_kasus }} Kasus</span></li>
                            @empty
                                <li class="list-none text-gray-500">Belum ada data pelanggaran.</li>
                            @endforelse
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>