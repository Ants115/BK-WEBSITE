<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Kelas Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kelas.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                            <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <x-input-error :messages="$errors->get('nama_kelas')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkatan</label>
                                <select name="tingkatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="" disabled selected>-- Pilih Tingkatan --</option>
                                    @foreach($tingkatans as $t)
                                        <option value="{{ $t->id }}" @selected(old('tingkatan_id') == $t->id)>{{ $t->nama_tingkatan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tingkatan_id')" class="mt-1" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                                <select name="jurusan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                                    @foreach($jurusans as $j)
                                        <option value="{{ $j->id }}" @selected(old('jurusan_id') == $j->id)>{{ $j->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jurusan_id')" class="mt-1" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Wali Kelas (Opsional)</label>
                            <select name="wali_kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Belum ditetapkan --</option>
                                @foreach($waliCandidates as $guru)
                                    <option value="{{ $guru->id }}" @selected(old('wali_kelas_id') == $guru->id)>
                                        {{ $guru->name }} ({{ $guru->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Wali kelas bisa ditetapkan sekarang atau nanti melalui menu edit.
                            </p>
                            <x-input-error :messages="$errors->get('wali_kelas_id')" class="mt-1" />
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
