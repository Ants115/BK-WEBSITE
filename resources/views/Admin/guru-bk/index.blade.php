<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Guru BK
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <form method="GET" action="{{ route('admin.guru-bk.index') }}" class="flex gap-2">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama, email, NIP..."
                            class="rounded-md border-gray-300 text-sm"
                        >
                        <button class="px-3 py-2 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700">
                            Cari
                        </button>
                    </form>

                    <a href="{{ route('admin.guru-bk.create') }}"
                       class="px-4 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                        + Tambah Guru BK
                    </a>
                </div>

                <div class="p-6">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">NIP</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Jabatan</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($gurus as $guru)
                                <tr>
                                    <td class="px-4 py-2">{{ $guru->name }}</td>
                                    <td class="px-4 py-2">{{ $guru->email }}</td>
                                    <td class="px-4 py-2">{{ $guru->biodataStaf->nip ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $guru->biodataStaf->jabatan ?? 'Guru BK' }}</td>
                                    <td class="px-4 py-2 text-xs">
                                        <a href="{{ route('admin.guru-bk.edit', $guru) }}"
                                           class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>

                                        <form action="{{ route('admin.guru-bk.destroy', $guru) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
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
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada data guru BK.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $gurus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
