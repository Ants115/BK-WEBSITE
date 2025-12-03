<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prestasi Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FILTER & AKSI --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.prestasi.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        
                        {{-- Search --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Siswa / Judul</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm"
                                placeholder="Nama siswa atau judul prestasi">
                        </div>

                        {{-- Tingkat --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tingkat</label>
                            <select name="tingkat" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua Tingkat</option>
                                @foreach($tingkatOptions as $opt)
                                    <option value="{{ $opt }}" @selected($tingkat === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                            <select name="kategori" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriOptions as $opt)
                                    <option value="{{ $opt }}" @selected($kategori === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kelas --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Kelas</label>
                            <select name="kelas_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" @selected(($kelasId ?? null) == $kelas->id)>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                            <select name="tahun" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunOptions as $t)
                                    <option value="{{ $t }}" @selected(($tahun ?? null) == $t)>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol --}}
                        <div class="md:col-span-5 flex flex-col md:flex-row md:justify-between md:items-end gap-2 mt-2">
                            <div class="flex gap-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700">
                                    Tampilkan
                                </button>
                                <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-200">
                                    Reset
                                </a>
                            </div>
                            <a href="{{ route('admin.prestasi.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700 self-start md:self-auto">
                                + Tambah Prestasi
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Siswa</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Judul Prestasi</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Tingkat</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($prestasis as $item)
                                <tr>
                                    <td class="px-4 py-2 text-xs text-gray-700">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-xs">
                                        <div class="font-semibold text-gray-900">
                                            {{ $item->siswa->name ?? '-' }}
                                        </div>
                                        <div class="text-gray-500">
                                            {{ $item->siswa->email ?? '' }}
                                        </div>
                                        <div class="text-gray-400 text-[11px]">
                                            {{ $item->siswa->biodataSiswa->kelas->nama_kelas ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-800">
                                        {{ $item->judul }}
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-700">
                                        {{ $item->tingkat }}
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-700">
                                        {{ $item->kategori }}
                                    </td>
                                    <td class="px-4 py-2 text-xs">
                                        <a href="{{ route('admin.prestasi.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.prestasi.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Belum ada data prestasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $prestasis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
