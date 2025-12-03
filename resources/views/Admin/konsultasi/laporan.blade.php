<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Konsultasi BK
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.konsultasi.laporan') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        {{-- Guru BK --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Guru BK</label>
                            <select name="guru_id" class="block w-full rounded-md border-gray-300 text-sm">
                                <option value="">Semua Guru BK</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}" {{ $guruId == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                            <select name="status" class="block w-full rounded-md border-gray-300 text-sm">
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Dari Tanggal</label>
                            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="block w-full rounded-md border-gray-300 text-sm">
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Sampai Tanggal</label>
                            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="block w-full rounded-md border-gray-300 text-sm">
                        </div>

                        {{-- Search Nama Siswa --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cari Siswa</label>
                            <div class="flex space-x-2">
                                <input type="text" name="q" value="{{ $search }}" placeholder="Nama siswa..." class="flex-1 rounded-md border-gray-300 text-sm">
                                <button type="submit" class="px-3 py-2 bg-gray-800 text-white text-xs font-semibold rounded-md">
                                    Terapkan
                                </button>
                            </div>
                            <div class="mt-1">
                                <a href="{{ route('admin.konsultasi.laporan') }}" class="text-xs text-gray-500 hover:underline">
                                    Reset filter
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Ringkasan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Total semua --}}
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <p class="text-xs font-semibold text-gray-500 uppercase">Total Konsultasi</p>
                            <p class="mt-2 text-2xl font-bold text-gray-800">{{ $totalSemua }}</p>
                        </div>

                        {{-- Per status --}}
                        @php
                            $statusColors = [
                                'Menunggu Persetujuan' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                                'Disetujui'            => 'bg-green-50 border-green-200 text-green-800',
                                'Ditolak'              => 'bg-red-50 border-red-200 text-red-800',
                                'Dijadwalkan Ulang'    => 'bg-blue-50 border-blue-200 text-blue-800',
                                'Selesai'              => 'bg-gray-50 border-gray-200 text-gray-800',
                            ];
                        @endphp

                        @foreach($statusOptions as $value => $label)
                            <div class="border rounded-lg p-4 {{ $statusColors[$value] ?? 'bg-gray-50 border-gray-200 text-gray-800' }}">
                                <p class="text-xs font-semibold uppercase">{{ $label }}</p>
                                <p class="mt-2 text-xl font-bold">
                                    {{ $rekapStatus[$value] ?? 0 }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Rekap per guru --}}
                    <div class="mt-6">
                        <h4 class="text-xs font-semibold text-gray-600 uppercase mb-2">Distribusi per Guru BK</h4>
                        @if($rekapGuru->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200 bg-gray-50">
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru BK</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Konsultasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rekapGuru as $row)
                                            <tr class="border-b border-gray-100">
                                                <td class="px-3 py-2">
                                                    {{ optional($guruMap->get($row->guru_id))->name ?? 'Tidak diketahui' }}
                                                </td>
                                                <td class="px-3 py-2 text-right">
                                                    {{ $row->total }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-xs text-gray-500">Belum ada data untuk ditampilkan.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tabel detail --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Detail Konsultasi</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru BK</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Disetujui</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($konsultasi as $item)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="font-semibold text-gray-900">{{ $item->siswa->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->siswa->email ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $item->guru->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="block text-gray-800">
                                                {{ \Illuminate\Support\Str::limit($item->topik, 60) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            @php
                                                $badgeClasses = 'bg-blue-100 text-blue-800';
                                                if ($item->status === 'Menunggu Persetujuan') {
                                                    $badgeClasses = 'bg-yellow-100 text-yellow-800';
                                                } elseif ($item->status === 'Disetujui') {
                                                    $badgeClasses = 'bg-green-100 text-green-800';
                                                } elseif ($item->status === 'Ditolak') {
                                                    $badgeClasses = 'bg-red-100 text-red-800';
                                                } elseif ($item->status === 'Selesai') {
                                                    $badgeClasses = 'bg-gray-100 text-gray-800';
                                                }
                                            @endphp
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-600">
                                            @if($item->jadwal_disetujui)
                                                {{ \Carbon\Carbon::parse($item->jadwal_disetujui)->translatedFormat('d M Y') }}
                                                <br>
                                                <span class="text-gray-500">
                                                    {{ \Carbon\Carbon::parse($item->jadwal_disetujui)->format('H:i') }} WIB
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Belum ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data konsultasi untuk filter yang dipilih.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $konsultasi->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
