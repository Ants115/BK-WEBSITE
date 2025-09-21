<x-app-layout>
    <div class="p-6">
        <h3 class="font-semibold text-xl">Tambah Kelas Baru</h3>
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

            <div>
                <label for="tingkatan_id">Tingkatan</label>
                <select name="tingkatan_id" id="tingkatan_id" required>
                    <option value="">-- Pilih Tingkatan --</option>
                    @foreach($tingkatanList as $tingkatan)
                        <option value="{{ $tingkatan->id }}">{{ $tingkatan->nama_tingkatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label for="jurusan_id">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($jurusanList as $jurusan)
                        <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label for="nama_unik">Nomor Kelas (Rombel)</label>
                <input type="text" name="nama_unik" id="nama_unik" placeholder="Contoh: 1 atau A" required>
            </div>

            <div class="mt-4">
                <label for="tahun_ajaran">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="tahun_ajaran" placeholder="Contoh: 2025/2026" required>
            </div>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>