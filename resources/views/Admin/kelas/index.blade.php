<a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="text-yellow-600">Edit</a>

<form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 ml-2">Hapus</button>
</form>