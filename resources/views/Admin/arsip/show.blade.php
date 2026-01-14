<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Arsip Alumni Angkatan {{ $tahun_lulus }}
            </h2>
            <a href="{{ route('admin.arsip.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 mt-2 md:mt-0 font-medium">
                &larr; Kembali ke Daftar Angkatan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Alumni</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalSiswa }} <span class="text-sm font-normal text-gray-500">Siswa</span></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Kelas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalKelas }} <span class="text-sm font-normal text-gray-500">Rombel</span></p>
                </div>
            </div>

            {{-- GRID KARTU KELAS --}}
            @if($alumniPerKelas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($alumniPerKelas as $namaKelas => $listSiswa)
                        @php
                            // Ambil jurusan dari siswa pertama untuk label badge (opsional)
                            $jurusan = $listSiswa->first()->biodataSiswa->kelas->jurusan->singkatan ?? 'UMUM';
                        @endphp

                        {{-- KARTU KELAS --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition duration-200">
                            <div class="p-5">
                                <div class="flex justify-between items-start">
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-indigo-100 text-indigo-700">
                                        {{ $jurusan }}
                                    </span>
                                    <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                </div>
                                
                                <h3 class="mt-3 text-lg font-bold text-gray-900">{{ $namaKelas }}</h3>
                                <p class="text-sm text-gray-500">{{ $listSiswa->count() }} Alumni</p>

                                {{-- TOMBOL BUKA MODAL --}}
                                <button onclick="openModal('modal-{{ Str::slug($namaKelas) }}')" 
                                        class="mt-4 w-full py-2 px-4 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-lg hover:bg-indigo-100 transition flex justify-center items-center">
                                    Lihat Daftar
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- MODAL DAFTAR SISWA (Hidden by Default) --}}
                        <div id="modal-{{ Str::slug($namaKelas) }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                {{-- Background Overlay --}}
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('modal-{{ Str::slug($namaKelas) }}')"></div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                                {{-- Modal Content --}}
                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2" id="modal-title">
                                                    Daftar Alumni - {{ $namaKelas }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-4">
                                                    Angkatan {{ $tahun_lulus }}
                                                </p>

                                                {{-- TABEL SISWA DI DALAM MODAL --}}
                                                <div class="overflow-hidden border border-gray-200 rounded-lg">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($listSiswa as $siswa)
                                                                <tr>
                                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $siswa->name }}</td>
                                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $siswa->biodataSiswa->nis ?? '-' }}</td>
                                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $siswa->email }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" onclick="closeModal('modal-{{ Str::slug($namaKelas) }}')" 
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p class="mt-2 text-sm text-gray-500">Belum ada data alumni untuk tahun ini.</p>
                </div>
            @endif
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
    </script>
</x-app-layout>