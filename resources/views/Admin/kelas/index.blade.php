<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Kelas & Wali Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.kelas.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Kelas</label>
                            {{-- gunakan request('search') supaya tidak perlu variabel $search dari controller --}}
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm"
                                placeholder="Nama kelas"
                            >
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700">
                                Tampilkan
                            </button>
                            <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700">
                                + Tambah Kelas
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Nama Kelas</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Tingkatan</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Jurusan</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Wali Kelas</th>
                                <th class="px-4 py-2 text-left font-semibold text-xs text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kelasList as $kelas)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        {{ $kelas->nama_kelas }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ $kelas->tingkatan->nama_tingkatan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ $kelas->jurusan->nama_jurusan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($kelas->waliKelas)
                                            <div class="font-semibold text-gray-900">{{ $kelas->waliKelas->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $kelas->waliKelas->email }}</div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                Belum ditetapkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-xs">
                                        <a href="{{ route('admin.kelas.edit', $kelas) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('admin.kelas.destroy', $kelas) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus kelas ini?');"
                                        >
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
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Belum ada data kelas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $kelasList->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
