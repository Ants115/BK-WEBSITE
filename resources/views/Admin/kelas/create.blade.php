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
                            <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" placeholder="Contoh: X TKR 1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('nama_kelas')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkatan</label>
                                <select name="tingkatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled selected>-- Pilih Tingkatan --</option>
                                    @foreach($tingkatans as $t)
                                        <option value="{{ $t->id }}" @selected(old('tingkatan_id') == $t->id)>{{ $t->nama_tingkatan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tingkatan_id')" class="mt-1" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                                <select name="jurusan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                                    @foreach($jurusans as $j)
                                        <option value="{{ $j->id }}" @selected(old('jurusan_id') == $j->id)>{{ $j->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jurusan_id')" class="mt-1" />
                            </div>
                        </div>

                        {{-- Bagian Wali Kelas --}}
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas (Opsional)</label>
                            <select name="wali_kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Belum ditetapkan --</option>
                                {{-- Loop menggunakan variabel yang benar: $waliCandidates --}}
                                @foreach($waliCandidates as $guru)
                                    <option value="{{ $guru->id }}" @selected(old('wali_kelas_id') == $guru->id)>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-500">
                                * Hanya user dengan role <b>guru_bk</b> atau <b>wali_kelas</b> yang muncul di sini.
                            </p>
                            <x-input-error :messages="$errors->get('wali_kelas_id')" class="mt-1" />
                        </div>

                        <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition">
                                Simpan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>