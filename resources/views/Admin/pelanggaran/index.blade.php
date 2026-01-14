<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Master Data Pelanggaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Sukses/Error --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors (Global) --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-300 text-red-600 px-4 py-3 rounded text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    {{-- Form Pencarian --}}
                    <form action="{{ route('admin.pelanggaran.index') }}" method="GET" class="w-full md:w-1/2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari pelanggaran..." 
                                class="w-full rounded-md border-gray-300 pl-4 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>

                    {{-- Tombol Tambah (Buka Modal) --}}
                    <button onclick="openModal('addModal')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition shadow-sm font-semibold text-sm">
                        + Tambah Data
                    </button>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pelanggaran</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Poin</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pelanggarans as $index => $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $pelanggarans->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $item->nama_pelanggaran }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $badgeColor = match($item->kategori) {
                                                'Ringan' => 'bg-blue-100 text-blue-800',
                                                'Sedang' => 'bg-yellow-100 text-yellow-800',
                                                'Berat' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                            {{ $item->kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-700">
                                        {{ $item->poin }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                        <div class="flex justify-center space-x-2">
                                            {{-- Tombol Edit --}}
                                            <button onclick="openEditModal({{ $item }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-md">
                                                Edit
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('admin.pelanggaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-md">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                        Belum ada data pelanggaran. Silakan tambah data baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pelanggarans->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH DATA --}}
    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('addModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.pelanggaran.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah Jenis Pelanggaran</h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Pelanggaran</label>
                            <input type="text" name="nama_pelanggaran" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="Ringan">Ringan</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Berat">Berat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poin</label>
                                <input type="number" name="poin" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" onclick="closeModal('addModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT DATA --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('editModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                {{-- Form Action akan diisi via JS --}}
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Pelanggaran</h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Pelanggaran</label>
                            <input type="text" name="nama_pelanggaran" id="editNama" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori" id="editKategori" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="Ringan">Ringan</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Berat">Berat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poin</label>
                                <input type="number" name="poin" id="editPoin" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                        <button type="button" onclick="closeModal('editModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT SEDERHANA UNTUK MODAL --}}
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openEditModal(data) {
            // Isi form dengan data JSON yang dikirim dari tombol
            document.getElementById('editNama').value = data.nama_pelanggaran;
            document.getElementById('editPoin').value = data.poin;
            document.getElementById('editKategori').value = data.kategori;
            
            // Set action URL formulir agar mengarah ke route update yang benar
            // Asumsi URL: /admin/pelanggaran/{id}
            let url = "{{ route('admin.pelanggaran.update', ':id') }}";
            url = url.replace(':id', data.id);
            document.getElementById('editForm').action = url;

            openModal('editModal');
        }
    </script>
</x-app-layout>