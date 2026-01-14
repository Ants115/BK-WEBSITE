<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Kelas & Wali Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Sukses/Eror --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- HEADER TOOLS: PENCARIAN & TOMBOL AKSI --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    
                    {{-- Kiri: Form Pencarian (Formulir 1) --}}
                    <div class="w-full md:w-1/2">
                        <form action="{{ route('admin.kelas.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari Kelas / Jurusan..." 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                @if(request('search'))
                                    <a href="{{ route('admin.kelas.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Kanan: Tombol Tambah & Hapus Massal --}}
                    <div class="flex gap-2 w-full md:w-auto justify-end">
                        {{-- Tombol Hapus Massal --}}
                        {{-- PERHATIKAN: Ada atribut form="bulkDeleteForm". Ini menghubungkan tombol ke form di bawah --}}
                        <button type="submit" form="bulkDeleteForm" id="bulkDeleteBtn" disabled 
                                class="bg-red-500 text-white px-4 py-2 rounded-md font-semibold text-sm hover:bg-red-600 transition opacity-50 cursor-not-allowed flex items-center shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Terpilih (<span id="selectedCount">0</span>)
                        </button>

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('admin.kelas.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md font-semibold text-sm hover:bg-green-700 transition shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Kelas
                        </a>
                    </div>
                </div>

                {{-- FORMULIR 2: HAPUS MASSAL (Hanya membungkus Grid) --}}
                <form id="bulkDeleteForm" action="{{ route('admin.kelas.destroyMultiple') }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus kelas juga akan menghapus data terkait jika tidak ada validasi. Yakin ingin menghapus kelas terpilih?');">
                    @csrf
                    @method('DELETE')

                    {{-- GRID KELAS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @forelse($kelas as $item)
                            <div class="relative bg-yellow-50 border border-yellow-100 rounded-lg p-4 hover:shadow-md transition group">
                                
                                {{-- CHECKBOX HAPUS MASSAL --}}
                                <div class="absolute top-3 left-3 z-10">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" 
                                           class="class-checkbox w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer bg-white">
                                </div>

                                {{-- Header Kartu --}}
                                <div class="flex justify-between items-start pl-8">
                                    <div>
                                        <span class="px-2 py-1 text-xs font-bold rounded bg-indigo-100 text-indigo-700">
                                            {{ $item->jurusan->singkatan ?? '-' }}
                                        </span>
                                        <h3 class="mt-2 text-lg font-bold text-gray-800">{{ $item->nama_kelas }}</h3>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $item->tahun_ajaran ?? date('Y') }}</span>
                                </div>

                                {{-- Info Wali Kelas --}}
                                <div class="mt-4 pt-4 border-t border-yellow-200">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Wali Kelas</p>
                                    @if($item->waliKelas)
                                        <p class="text-sm font-medium text-gray-800 truncate" title="{{ $item->waliKelas->name }}">
                                            {{ $item->waliKelas->name }}
                                        </p>
                                    @else
                                        <div class="flex items-center text-yellow-600 bg-yellow-100 px-2 py-1 rounded mt-1 w-fit">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <span class="text-xs font-bold">Belum Ada</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Buttons (Manual Edit) --}}
                                <div class="mt-4 flex justify-end gap-2">
                                    <a href="{{ route('admin.kelas.edit', $item->id) }}" class="text-gray-400 hover:text-blue-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10 text-gray-500">
                                <p>Tidak ada data kelas ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </form> {{-- END FORM BULK DELETE --}}

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $kelas->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.class-checkbox');
            const bulkBtn = document.getElementById('bulkDeleteBtn');
            const countSpan = document.getElementById('selectedCount');

            function updateButtonState() {
                let checkedCount = 0;
                checkboxes.forEach(chk => {
                    if(chk.checked) checkedCount++;
                });

                countSpan.innerText = checkedCount;

                if (checkedCount > 0) {
                    bulkBtn.disabled = false;
                    bulkBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    bulkBtn.disabled = true;
                    bulkBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            checkboxes.forEach(chk => {
                chk.addEventListener('change', updateButtonState);
            });
        });
    </script>
</x-app-layout>