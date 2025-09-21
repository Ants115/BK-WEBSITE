<x-app-layout>
    <div class="p-6">
        <h3 class="font-semibold text-xl">Edit Data Kelas</h3>
        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <select name="tingkatan_id" required>
                @foreach($tingkatanList as $tingkatan)
                    <option value="{{ $tingkatan->id }}" @if($kelas->tingkatan_id == $tingkatan->id) selected @endif>
                        {{ $tingkatan->nama_tingkatan }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</x-app-layout>