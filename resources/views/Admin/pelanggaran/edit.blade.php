<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jenis Pelanggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.pelanggaran.update', $pelanggaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="nama_pelanggaran" class="block text-sm font-medium text-gray-700">Nama Pelanggaran</label>
                            <input type="text" name="nama_pelanggaran" id="nama_pelanggaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $pelanggaran->nama_pelanggaran }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="poin" class="block text-sm font-medium text-gray-700">Poin</label>
                            <input type="number" name="poin" id="poin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $pelanggaran->poin }}" required>
                        </div>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.pelanggaran.index') }}" class="text-gray-600 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>