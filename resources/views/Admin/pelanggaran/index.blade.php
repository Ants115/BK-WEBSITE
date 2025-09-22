<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Master Pelanggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Daftar Jenis Pelanggaran</h3>
                        <a href="{{ route('admin.pelanggaran.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Pelanggaran Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Nama Pelanggaran</th>
                                <th class="py-2 px-4 text-left">Poin</th>
                                <th class="py-2 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelanggaranList as $pelanggaran)
                                <tr class="border-b">
                                    <td class="py-2 px-4">{{ $pelanggaran->nama_pelanggaran }}</td>
                                    <td class="py-2 px-4">{{ $pelanggaran->poin }}</td>
                                    <td class="py-2 px-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.pelanggaran.edit', $pelanggaran->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.pelanggaran.destroy', $pelanggaran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Belum ada data pelanggaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $pelanggaranList->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>