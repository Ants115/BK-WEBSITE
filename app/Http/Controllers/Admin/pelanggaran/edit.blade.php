<x-app-layout>
    <div class="p-6">
        <h3 class="font-semibold text-xl">Edit Data Pelanggaran</h3>
        <form action="{{ route('admin.pelanggaran.update', $pelanggaran->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="nama_pelanggaran">Nama Pelanggaran</label>
                <input type="text" name="nama_pelanggaran" id="nama_pelanggaran" value="{{ old('nama_pelanggaran', $pelanggaran->nama_pelanggaran) }}" required>
            </div>
            <div class="mt-4">
                <label for="poin">Poin</label>
                <input type="number" name="poin" id="poin" value="{{ old('poin', $pelanggaran->poin) }}" required>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </form>
    </div>
</x-app-layout>