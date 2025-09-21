<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Profil Anda</h3>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>NIS:</strong> {{ $user->biodataSiswa->nis ?? 'Belum diatur' }}</p>
                        </div>
                        <div>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Kelas:</strong> {{ $user->biodataSiswa->kelas->nama_kelas ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <p class="text-xl font-bold">Total Poin Pelanggaran Anda: {{ $totalPoin }}</p>
                    </div>

                    <h3 class="text-lg font-medium mt-8">Riwayat Pelanggaran</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4">Tanggal</th>
                                    <th class="py-2 px-4">Pelanggaran</th>
                                    <th class="py-2 px-4">Poin</th>
                                    <th class="py-2 px-4">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user->pelanggaranSiswa as $item)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                        <td class="py-2 px-4">{{ $item->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 text-center">{{ $item->pelanggaran->poin ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $item->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Alhamdulillah, tidak ada catatan pelanggaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>