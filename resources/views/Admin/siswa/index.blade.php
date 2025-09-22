<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="GET" action="{{ route('admin.siswa.index') }}" class="mb-6">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Cari berdasarkan Nama atau NIS..." class="form-input rounded-l-md w-full border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md">
                                Cari
                            </button>
                        </div>
                    </form>

                     <a href="{{ route('admin.siswa.index') }}" class="bg-gray-500 text-white px-4 py- rounded-lg hover:bg-gray-600">
                        Refresh
                     </a>
                     <div class="flex justify-between items-center mb-4">
    <div class="flex items-center space-x-2">
        </div>

    <a href="{{ route('admin.siswa.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
        Tambah Siswa
    </a>
</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">NIS</th>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Email</th>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswas as $siswa)
                                    <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                            Detail
                        </a>
                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                            Edit
                        </a>
                        <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
                                        <td class="py-2 px-4">{{ $siswa->name }}</td>
                                        <td class="py-2 px-4">{{ $siswa->biodataSiswa->nis ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $siswa->email }}</td>
                                        <td class="py-2 px-4">{{ $siswa->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">
                                            <a href="#" class="text-blue-600 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">
                                            Tidak ada data siswa yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $siswas->appends(['search' => $search])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>