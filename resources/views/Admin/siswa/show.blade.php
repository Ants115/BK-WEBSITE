<x-app-layout>
    <div class="p-6">
        
    <p class="font-bold">Total Poin Pelanggaran: {{ $totalPoin }}</p>

    <h3 class="font-semibold mt-6">Tambah Catatan Pelanggaran</h3>
<form action="{{ route('guru.pelanggaran.store') }}" method="POST">
    @csrf
    <input type="hidden" name="siswa_user_id" value="{{ $siswa->id }}">

    <input type="date" name="tanggal" required>

    <select name="pelanggaran_id" required>
        @foreach ($masterPelanggaran as $pelanggaran)
            <option value="{{ $pelanggaran->id }}">{{ $pelanggaran->nama_pelanggaran }} ({{ $pelanggaran->poin }} poin)</option>
        @endforeach
    </select>

    @if($jenisSurat)
    <div class="mt-4">
        <a href="{{ route('guru.siswa.cetakSurat', ['id' => $siswa->id, 'jenis' => $jenisSurat]) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Cetak {{ $jenisSurat }}
        </a>
    </div>
@endif

    <textarea name="keterangan" placeholder="Keterangan..."></textarea>

    <button type="submit">Simpan</button>
</form>

        <h3 class="font-semibold">Profil Siswa</h3>
        <p>Nama: {{ $siswa->name }}</p>
        <p>NIS: {{ $siswa->biodataSiswa->nis ?? 'N/A' }}</p>
        <h3 class="font-semibold mt-6">Riwayat Pelanggaran</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelanggaran</th>
                    <th>Poin</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa->pelanggaranSiswa as $item)
                    <tr>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->pelanggaran->nama_pelanggaran }}</td>
                        <td>{{ $item->pelanggaran->poin }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada riwayat pelanggaran.</td>
                        <td>
    <a href="#" class="text-yellow-600">Edit</a>
    <form action="#" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600">Hapus</button>
    </form>
</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>

<div class="p-6">
                        <td>
    <a href="#" class="text-yellow-600">Edit</a>
    <form action="#" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600">Hapus</button>
    </form>
</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>