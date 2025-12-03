<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Prestasi Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.prestasi.update', $prestasi) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Siswa</label>
                            <select name="siswa_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" @selected(old('siswa_id', $prestasi->siswa_id) == $siswa->id)>
                                        {{ $siswa->name }}
                                        @if($siswa->biodataSiswa && $siswa->biodataSiswa->kelas)
                                            ({{ $siswa->biodataSiswa->kelas->nama_kelas }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Prestasi</label>
                            <input type="text" name="judul" value="{{ old('judul', $prestasi->judul) }}" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                            @error('judul') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                                <select name="tingkat" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                    @foreach($tingkatOptions as $opt)
                                        <option value="{{ $opt }}" @selected(old('tingkat', $prestasi->tingkat) == $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @error('tingkat') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="kategori" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                    @foreach($kategoriOptions as $opt)
                                        <option value="{{ $opt }}" @selected(old('kategori', $prestasi->kategori) == $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @error('kategori') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', $prestasi->tanggal) }}" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                @error('tanggal') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (opsional)</label>
                            <textarea name="keterangan" rows="4" class="w-full rounded-md border-gray-300 shadow-sm text-sm">{{ old('keterangan', $prestasi->keterangan) }}</textarea>
                            @error('keterangan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white hover:bg-indigo-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
