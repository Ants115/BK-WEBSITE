<x-app-layout>
    <x-slot name="header">
            <div class="flex flex-col">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ request('kelas_id') ? 'Data Siswa Kelas: ' . $currentKelas->nama_kelas : 'Pilih Kelas Siswa' }}
                </h2>
                
                {{-- Tambahan: Menampilkan Wali Kelas --}}
                @if(request('kelas_id'))
                    <p class="text-sm text-gray-500 mt-1">
                        Wali Kelas: 
                        <span class="font-bold text-indigo-600">
                            {{ $currentKelas->waliKelas->name ?? 'Belum ditentukan' }}
                        </span>
                    </p>
                @endif
            </div>
        </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ========================================== --}}
            {{-- MODE 1: TAMPILAN DAFTAR SISWA (TABLE)      --}}
            {{-- ========================================== --}}
            @if(request('kelas_id'))
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    {{-- Header Tools --}}
                    <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.siswa.index') }}" class="flex items-center text-gray-500 hover:text-indigo-600 font-medium transition">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Ganti Kelas
                        </a>

                        {{-- Form Pencarian --}}
                        <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex-1 max-w-md w-full">
                            <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama atau NIS..." 
                                    class="w-full rounded-full border-gray-300 pl-4 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </div>
                        </form>

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('admin.siswa.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                            + Tambah Siswa Baru
                        </a>
                    </div>

                    {{-- Tabel Siswa --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama & NIS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($siswas as $index => $siswa)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $siswas->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                                                    {{ substr($siswa->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $siswa->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $siswa->biodataSiswa->nis ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                <span>{{ $siswa->email }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center space-x-3">
                                                {{-- Detail --}}
                                                <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Detail Profil">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>

                                                {{-- Mutasi (Pindah Kelas) --}}
                                                <button onclick="openMutasiModal('{{ $siswa->id }}', '{{ $siswa->name }}', '{{ $currentKelas->id }}')" 
                                                        class="text-yellow-600 hover:text-yellow-900" title="Pindahkan / Mutasi Siswa">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                </button>

                                                {{-- Edit --}}
                                                <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="text-green-600 hover:text-green-900" title="Edit Data">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                
                                                {{-- Hapus --}}
                                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin hapus siswa ini?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Siswa">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada siswa di kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $siswas->links() }}
                    </div>
                </div>

                {{-- MODAL MUTASI (PINDAH KELAS) --}}
                <div id="mutasiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeMutasiModal()"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('admin.siswa.updateKelas') }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Pindahkan Siswa
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 mb-4">
                                                    Pilih kelas baru untuk siswa <span id="modalSiswaName" class="font-bold text-gray-800"></span>.
                                                </p>
                                                <input type="hidden" name="user_id" id="modalUserId">
                                                
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas Tujuan</label>
                                                <select name="kelas_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    @foreach($allKelas as $kls)
                                                        <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        Simpan Pindah
                                    </button>
                                    <button type="button" onclick="closeMutasiModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function openMutasiModal(userId, userName, currentKelasId) {
                        document.getElementById('modalUserId').value = userId;
                        document.getElementById('modalSiswaName').innerText = userName;
                        
                        // Opsional: Set dropdown ke kelas saat ini sebagai default (atau kosongkan)
                        // document.querySelector('select[name="kelas_id"]').value = currentKelasId;
                        
                        document.getElementById('mutasiModal').classList.remove('hidden');
                    }
                    function closeMutasiModal() {
                        document.getElementById('mutasiModal').classList.add('hidden');
                    }
                </script>

            {{-- ========================================== --}}
            {{-- MODE 2: TAMPILAN SELEKTOR KELAS (GRID)     --}}
            {{-- ========================================== --}}
            @else
                <div class="space-y-8">
                    @foreach($jurusans as $jurusan)
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                                    {{ $jurusan->singkatan }}
                                </span>
                                {{ $jurusan->nama_jurusan }}
                            </h3>
                            
                            @if($jurusan->kelas->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach($jurusan->kelas as $kelasItem)
                                        <a href="{{ route('admin.siswa.index', ['kelas_id' => $kelasItem->id]) }}" 
                                           class="group relative block p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition-all duration-200 text-center">
                                            
                                            <div class="text-lg font-bold text-gray-700 group-hover:text-indigo-700">
                                                {{ $kelasItem->nama_kelas }}
                                            </div>
                                            
                                            {{-- Indikator Jumlah Siswa (Optional: Butuh withCount di controller jika mau akurat) --}}
                                            <div class="mt-1 text-xs text-gray-400 group-hover:text-indigo-500">
                                                Klik untuk lihat
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">Belum ada kelas untuk jurusan ini.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>