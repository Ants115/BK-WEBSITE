<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Kelas & Wali Kelas
            </h2>
            <div class="text-sm text-gray-500">
                Total Kelas: {{ $kelasList->total() }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="mb-6 flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ml-3 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Bagian Header & Pencarian --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <form method="GET" action="{{ route('admin.kelas.index') }}" class="flex flex-col md:flex-row gap-4 justify-between items-end">
                    
                    {{-- Form Pencarian --}}
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cari Kelas / Jurusan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="Contoh: X TKR 1, XII RPL...">
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit" class="flex-1 md:flex-none justify-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                            Cari
                        </button>
                        <a href="{{ route('admin.kelas.create') }}" class="flex-1 md:flex-none flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Kelas
                        </a>
                    </div>
                </form>
            </div>

            {{-- GRID KELAS (Tampilan Baru) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($kelasList as $kelas)
                    {{-- Logic Warna Border: Biru jika ada wali kelas, Kuning jika kosong --}}
                    @php
                        $hasWaliKelas = $kelas->waliKelas != null;
                        $borderColor = $hasWaliKelas ? 'border-l-4 border-indigo-500' : 'border-l-4 border-yellow-400';
                        $cardBg = $hasWaliKelas ? 'bg-white' : 'bg-yellow-50';
                    @endphp

                    <div class="{{ $cardBg }} {{ $borderColor }} rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col justify-between h-full">
                        
                        {{-- Bagian Atas: Info Kelas --}}
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-0.5 rounded border border-gray-200">
                                    {{ $kelas->jurusan->singkatan ?? 'Umum' }}
                                </span>
                                <span class="text-xs text-gray-400 font-mono">{{ $kelas->tahun_ajaran }}</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                {{ $kelas->nama_kelas }}
                            </h3>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Wali Kelas</p>
                                @if($hasWaliKelas)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                            {{ substr($kelas->waliKelas->name, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $kelas->waliKelas->name }}">
                                                {{ $kelas->waliKelas->name }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 text-yellow-700 bg-yellow-100 px-3 py-1.5 rounded-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span class="text-xs font-bold">Belum Ada</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Bagian Bawah: Tombol Aksi --}}
                        <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2">
                            <a href="{{ route('admin.kelas.edit', $kelas) }}" class="text-gray-500 hover:text-indigo-600 transition-colors" title="Edit Kelas">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            
                            <form action="{{ route('admin.kelas.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas {{ $kelas->nama_kelas }}? Data siswa di dalamnya mungkin akan terdampak.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors" title="Hapus Kelas">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center p-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <p class="text-gray-500 font-medium">Belum ada data kelas yang ditemukan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $kelasList->links() }}
            </div>

        </div>
    </div>
</x-app-layout>