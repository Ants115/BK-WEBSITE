<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyesuaian Kelas Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-gray-600">Gunakan form ini untuk memindahkan siswa secara individu. Ini berguna untuk siswa yang tinggal kelas, pindah jurusan, atau koreksi data.</p>
                    
                    <!-- Form Pencarian Siswa -->
                    <form action="{{ route('admin.siswa.penyesuaian') }}" method="GET" class="mb-6">
                        <div class="flex items-center">
                            <input type="text" name="search" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Cari berdasarkan Nama atau NIS..." value="{{ $query ?? '' }}" required>
                            <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Cari</button>
                        </div>
                    </form>

                    <!-- Hasil Pencarian dan Form Update -->
                    @if($query)
                        @if($siswas->count() > 0)
                            <h3 class="text-lg font-medium text-gray-800 border-b pb-2 mb-4">Hasil Pencarian</h3>
                            <div class="space-y-4">
                                @foreach($siswas as $s)
                                    <form action="{{ route('admin.siswa.update-kelas') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $s->id }}">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center p-3 border rounded-md">
                                            <div>
                                                <div class="font-bold">{{ $s->name }}</div>
                                                <div class="text-sm text-gray-500">NIS: {{ $s->biodataSiswa->nis ?? 'N/A' }}</div>
                                            </div>
                                            <div class="text-sm">
                                                <span class="font-semibold">Kelas Saat Ini:</span> {{ $s->biodataSiswa->kelas->nama_kelas ?? 'Belum ada kelas' }}
                                            </div>
                                            <div class="md:col-span-1">
                                            <select name="kelas_id" class="w-full block rounded-md border-gray-300 shadow-sm">
                                                @php
                                                    // Ambil tingkatan siswa saat ini, jika ada.
                                                    $tingkatanSiswaSaatIni = $s->biodataSiswa->kelas->tingkatan_id ?? null;
                                                @endphp

                                                {{-- Opsi Default (Kelas saat ini) --}}
                                                <option value="{{ $s->biodataSiswa->kelas_id }}" selected>
                                                    {{ $s->biodataSiswa->kelas->nama_kelas ?? 'Pilih Kelas' }}
                                                </option>
                                                
                                                {{-- Grup Opsi Tinggal Kelas --}}
                                                @if(isset($kelasPerTingkat[1]) && $tingkatanSiswaSaatIni != 1)
                                                    <optgroup label="Tinggal Kelas (Tingkat X)">
                                                        @foreach($kelasPerTingkat[1] as $kelas)
                                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                                
                                                {{-- Grup Opsi Pindah Paralel / Tingkat XI --}}
                                                @if(isset($kelasPerTingkat[2]))
                                                    <optgroup label="Pindah/Tetap di Tingkat XI">
                                                        @foreach($kelasPerTingkat[2] as $kelas)
                                                            {{-- Jangan tampilkan kelas saat ini lagi di sini --}}
                                                            @if($kelas->id != $s->biodataSiswa->kelas_id)
                                                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                @endif

                                                {{-- Grup Opsi Naik Kelas / Tingkat XII --}}
                                                @if(isset($kelasPerTingkat[3]) && $tingkatanSiswaSaatIni != 3)
                                                    <optgroup label="Naik Kelas (Tingkat XII)">
                                                        @foreach($kelasPerTingkat[3] as $kelas)
                                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            </select>
                                            </div>
                                            <div>
                                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">Pindahkan</button>
                                            </div>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        @else
                             <p class="text-center text-gray-500 py-4">Siswa dengan nama atau NIS '{{ $query }}' tidak ditemukan.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
