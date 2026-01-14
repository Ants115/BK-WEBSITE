<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Siswa Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tampilkan Error Validasi (Jika ada) --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Gagal Menyimpan!</strong>
                    <span class="block sm:inline">Silakan periksa inputan Anda:</span>
                    <ul class="list-disc mt-2 ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form method="POST" action="{{ route('admin.siswa.store') }}">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Contoh: Budi Santoso">
                        </div>

                        {{-- NIS (Sekaligus jadi Password Default) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIS (Nomor Induk Siswa)</label>
                            <input type="number" name="nis" value="{{ old('nis') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Contoh: 12345678">
                            <p class="text-xs text-gray-500 mt-1">NIS ini akan digunakan sebagai Password awal siswa.</p>
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Contoh: budi@sekolah.com">
                        </div>

                        {{-- PILIHAN KELAS (YANG TADI HILANG) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select name="kelas_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                
                                {{-- Loop Data Kelas dari Controller --}}
                                @foreach($kelas as $item)
                                    <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kelas }} ({{ $item->jurusan->singkatan ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pastikan kelas sudah dipilih agar data bisa disimpan.</p>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.siswa.index') }}" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm font-medium">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium shadow-sm">
                                Simpan Siswa
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>