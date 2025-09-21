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
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Email</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>

                        <thead>
    <tr>
        <th>Nama Pelanggaran</th>
        <th>Poin</th>
        <th>Aksi</th> </tr>
</thead>
<tbody>
    @foreach ($pelanggaranList as $pelanggaran)
        <tr>
            <td>{{ $pelanggaran->nama_pelanggaran }}</td>
            <td>{{ $pelanggaran->poin }}</td>
            <td>
                <a href="{{ route('admin.pelanggaran.edit', $pelanggaran->id) }}" class="text-yellow-600 hover:text-yellow-900">
                    Edit
                </a>
            </td>
        </tr>
    @endforeach
</tbody>

                        <tbody>
                            @foreach ($siswas as $siswa)
                                <tr>
                                     <td>
                                     <a href="{{ route('admin.pelanggaran.create') }}">...
                                            {{ $siswa->name }}
                                            </a>
                                    </td>
                                    <td>{{ $siswa->biodataSiswa->nis ?? 'N/A' }}</td>
                                    <td>{{ $siswa->email }}</td>
                                    <td>{{ $siswa->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>