<form action="{{ route('admin.pelanggaran.destroy', $pelanggaran->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
    @csrf
    @method('DELETE') <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
        Hapus
    </button>
</form>