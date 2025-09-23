<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

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