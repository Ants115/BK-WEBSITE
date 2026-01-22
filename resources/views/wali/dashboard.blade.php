<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Wali Kelas
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Wali: {{ $user->name }} ({{ $user->email }})
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($totalKelas === 0)
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-600">
                        Anda belum ditetapkan sebagai wali kelas pada kelas manapun.
                    </div>
                </div>
            @else
                {{-- Ringkasan Kartu --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="text-xs text-gray-500 uppercase mb-1">Jumlah Kelas</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $totalKelas }}</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="text-xs text-gray-500 uppercase mb-1">Total Siswa</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="text-xs text-gray-500 uppercase mb-1">Total Poin Pelanggaran</div>
                        <div class="text-2xl font-bold text-red-600">{{ $totalPoinPelanggaran }}</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="text-xs text-gray-500 uppercase mb-1">Total Prestasi</div>
                        <div class="text-2xl font-bold text-green-600">{{ $totalPrestasi }}</div>
                    </div>
                </div>

                {{-- Per Kelas --}}
                @foreach($kelasData as $data)
                    @php
                        /** @var \App\Models\Kelas $kelas */
                        $kelas = $data['kelas'];
                    @endphp

                    <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Kelas {{ $kelas->nama_kelas }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    Tingkat: {{ $kelas->tingkatan->nama_tingkatan ?? '-' }} ·
                                    Jurusan: {{ $kelas->jurusan->nama_jurusan ?? '-' }}
                                </p>
                            </div>
                            <div class="flex gap-4 text-xs">
                                <div>
                                    <div class="text-gray-500 uppercase">Siswa</div>
                                    <div class="font-bold text-gray-800 text-base">
                                        {{ $data['jumlah_siswa'] }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-gray-500 uppercase">Poin Pelanggaran</div>
                                    <div class="font-bold text-red-600 text-base">
                                        {{ $data['poin_pelanggaran'] }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-gray-500 uppercase">Prestasi</div>
                                    <div class="font-bold text-green-600 text-base">
                                        {{ $data['jumlah_prestasi'] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                Daftar Siswa
                            </h4>

                            @if($data['siswas']->isEmpty())
                                <p class="text-sm text-gray-500">Belum ada siswa di kelas ini.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase">No</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase">NIS</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase">Nama</th>
                                                <th class="px-3 py-2 text-center font-semibold text-gray-500 uppercase">Poin Pelanggaran</th>
                                                <th class="px-3 py-2 text-center font-semibold text-gray-500 uppercase">Jumlah Prestasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($data['siswas'] as $i => $siswa)
                                               @php
                                                    // Langsung sum kolom 'poin' dari koleksi pelanggaranSiswa
                                                    $poin = $siswa->pelanggaranSiswa->sum('poin');
                                                    
                                                    $jumlahPrestasi = $siswa->prestasi->count();
                                                @endphp
                                                <tr>
                                                    <td class="px-3 py-2 text-gray-700">{{ $i + 1 }}</td>
                                                    <td class="px-3 py-2 text-gray-700">
                                                        {{ $siswa->biodataSiswa->nis ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-gray-800">
                                                        {{ $siswa->name }}
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px]
                                                            {{ $poin >= 50 ? 'bg-red-100 text-red-700' : ($poin > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                                            {{ $poin }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] bg-blue-100 text-blue-700">
                                                            {{ $jumlahPrestasi }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</x-app-layout>
