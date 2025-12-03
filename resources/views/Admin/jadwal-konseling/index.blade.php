<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Konsultasi Guru BK
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <form method="GET" action="{{ route('admin.jadwal-konseling.index') }}" class="flex items-center space-x-2">
                    <select name="guru_id" class="rounded-md border-gray-300 text-sm">
                        <option value="">Semua Guru BK</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ $guruId == $guru->id ? 'selected' : '' }}>
                                {{ $guru->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-2 bg-gray-800 text-white text-xs font-semibold rounded-md">
                        Filter
                    </button>
                </form>

                <a href="{{ route('admin.jadwal-konseling.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    + Tambah Jadwal
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru BK</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal / Hari</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jadwals as $jadwal)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $jadwal->guru->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @if($jadwal->tanggal)
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                                            @elseif($jadwal->hari)
                                                {{ $jadwal->hari }}
                                            @else
                                                <span class="text-gray-400 italic">Tidak ditentukan</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $jadwal->jam_mulai }} @if($jadwal->jam_selesai) - {{ $jadwal->jam_selesai }} @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $jadwal->lokasi ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            {{ $jadwal->keterangan ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-right space-x-2">
                                            <a href="{{ route('admin.jadwal-konseling.edit', $jadwal->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.jadwal-konseling.destroy', $jadwal->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Belum ada jadwal konsultasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $jadwals->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
