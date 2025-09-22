<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-6">Dashboard Siswa</h3>

                    <!-- Menampilkan pesan sukses setelah menandai notifikasi -->
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Bagian Notifikasi -->
                    @if($notifikasiList->isNotEmpty())
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Notifikasi Baru</h4>
                            <div class="space-y-3">
                                @foreach($notifikasiList as $notifikasi)
                                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                                        <p class="font-bold">{{ $notifikasi->judul }}</p>
                                        <p>{{ $notifikasi->pesan }}</p>
                                        
                                        <!-- INI BAGIAN YANG PALING PENTING -->
                                        <div class="text-right text-xs mt-2">
                                            <form action="{{ route('notifikasi.tandaiDibaca', $notifikasi->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="font-medium text-blue-600 hover:underline bg-transparent border-none p-0 cursor-pointer">
                                                    Tandai sudah dibaca
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Bagian Profil -->
                    <div class="mb-6 p-4 border rounded-lg">
                        <h4 class="font-semibold mb-2">Profil Anda</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Nama:</strong> {{ $user->name }}</p>
                                <p><strong>NIS:</strong> {{ $user->biodataSiswa->nis ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Kelas:</strong> {{ $user->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Poin Pelanggaran -->
                    <div class="mb-6 p-4 border border-red-400 bg-red-100 text-red-700 rounded-lg">
                        <p class="text-xl font-bold">Total Poin Pelanggaran Anda: {{ $totalPoin }}</p>
                    </div>

                    <!-- Bagian Riwayat Pelanggaran -->
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
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}</td>
                                            <td class="py-2 px-4">{{ $item->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 text-center">{{ $item->pelanggaran->poin ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $item->keterangan ?? '-' }}</td>
                                            <td class="py-2 px-4">{{ $item->pelapor->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada riwayat pelanggaran.</td>
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