<x-app-layout>
    <div class="p-6">
        <a href="{{ route('admin.kelas.create') }}" class="px-4 py-2 bg-green-600 text-white rounded">Tambah Kelas Baru</a>
        <table class="mt-4 min-w-full">
            <thead>
                <tr>
                    <th>Nama Lengkap Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelasList as $kelas)
                    <tr>
                        <td>{{ $kelas->nama_lengkap }}</td> <td>{{ $kelas->tahun_ajaran }}</td>
                        <td>
                            <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="text-yellow-600">Edit</a>
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>