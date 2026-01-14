<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Prestasi Siswa') }}
            </h2>
            {{-- Breadcrumb simpel agar terlihat pro --}}
            <div class="text-sm text-gray-500 mt-2 md:mt-0">
                Data Kesiswaan <span class="mx-2">/</span> <span class="text-indigo-600 font-medium">Prestasi</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Sukses (Dipercantik) --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start">
                    <div class="p-1 text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                
                {{-- HEADER & FILTER SECTION --}}
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <form method="GET" action="{{ route('admin.prestasi.index') }}">
                        
                        {{-- Layout Grid Filter --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            
                            {{-- Search --}}
                            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cari</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ $search }}" 
                                           class="pl-9 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" 
                                           placeholder="Siswa / Judul...">
                                </div>
                            </div>

                            {{-- Tingkat --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tingkat</label>
                                <select name="tingkat" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                                    <option value="">Semua Tingkat</option>
                                    @foreach($tingkatOptions as $opt)
                                        <option value="{{ $opt }}" @selected($tingkat === $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori</label>
                                <select name="kategori" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriOptions as $opt)
                                        <option value="{{ $opt }}" @selected($kategori === $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kelas --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kelas</label>
                                <select name="kelas_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" @selected(($kelasId ?? null) == $kelas->id)>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun</label>
                                <select name="tahun" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunOptions as $t)
                                        <option value="{{ $t }}" @selected(($tahun ?? null) == $t)>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Baris Tombol Aksi --}}
                        <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-3">
                            <div class="flex gap-2 w-full md:w-auto">
                                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                    Filter
                                </button>
                                <a href="{{ route('admin.prestasi.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-gray-50 transition shadow-sm">
                                    Reset
                                </a>
                            </div>

                            <a href="{{ route('admin.prestasi.create') }}" class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition shadow-md transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Prestasi
                            </a>
                        </div>
                    </form>
                </div>

                {{-- TABEL DATA --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Prestasi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tingkat</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($prestasis as $item)
                                <tr class="hover:bg-indigo-50/30 transition duration-150 group">
                                    
                                    {{-- Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                                        </div>
                                    </td>

                                    {{-- Siswa (Dengan Avatar Inisial) --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold uppercase text-xs mr-3">
                                                {{ substr($item->siswa->name ?? 'X', 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $item->siswa->name ?? '-' }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->siswa->email ?? '' }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $item->siswa->biodataSiswa->kelas->nama_kelas ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Judul --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800">{{ $item->judul_prestasi }}</div>
                                    </td>

                                    {{-- Tingkat (Dengan Badge Warna) --}}
                                    <td class="px-6 py-4">
                                        @php
                                            // Logika warna badge sederhana berdasarkan teks tingkat
                                            $badgeClass = match($item->tingkat) {
                                                'Nasional' => 'bg-red-100 text-red-700 border-red-200',
                                                'Internasional' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'Provinsi' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'Kabupaten/Kota' => 'bg-green-100 text-green-700 border-green-200',
                                                default => 'bg-gray-100 text-gray-600 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeClass }}">
                                            {{ $item->tingkat }}
                                        </span>
                                    </td>

                                    {{-- Kategori --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($item->kategori == 'Akademik')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                📚 Akademik
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                                ⚽ Non-Akademik
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi (Menggunakan Ikon) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.prestasi.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition group-hover:bg-white border border-transparent group-hover:border-indigo-100" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                            
                                            <form action="{{ route('admin.prestasi.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition group-hover:bg-white border border-transparent group-hover:border-red-100" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <p class="font-medium text-gray-600">Belum ada data prestasi.</p>
                                            <p class="text-xs mt-1">Silakan tambahkan prestasi siswa baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $prestasis->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>