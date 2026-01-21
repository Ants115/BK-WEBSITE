<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Siswa
            </h2>
            <a href="{{ route('admin.siswa.index', ['kelas_id' => $siswa->biodataSiswa->kelas_id]) }}" class="text-sm text-gray-500 hover:text-indigo-600 mt-2 md:mt-0">
                &larr; Kembali ke Daftar Kelas
            </a>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI: PROFIL SISWA --}}
                <div class="md:col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-500">
                        <div class="flex flex-col items-center">
                            <div class="h-24 w-24 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-3xl font-bold mb-4">
                                {{ substr($siswa->name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center">{{ $siswa->name }}</h3>
                            <p class="text-sm text-gray-500 text-center mb-4">{{ $siswa->biodataSiswa->nis ?? 'NIS Belum Ada' }}</p>
                            
                            <div class="w-full border-t border-gray-100 py-4">
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-500">Kelas</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $siswa->biodataSiswa->kelas->nama_kelas ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-500">Jurusan</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $siswa->biodataSiswa->kelas->jurusan->singkatan ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-500">Email</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $siswa->email }}</span>
                                </div>
                            </div>

{{-- KARTU POIN PELANGGARAN --}}
<div class="w-full mt-4 p-4 rounded-lg {{ $totalPoin > 0 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
    <div class="text-center">
        <span class="block text-xs uppercase font-bold text-gray-500">Total Poin Pelanggaran</span>
        <span class="block text-3xl font-extrabold {{ $totalPoin > 50 ? 'text-red-600' : ($totalPoin > 0 ? 'text-orange-500' : 'text-green-600') }}">
            {{ $totalPoin }}
        </span>
    </div>

    {{-- Status Surat Peringatan --}}
    @if($jenisSurat)
        <div class="mt-3 text-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                ⚠️ {{ $jenisSurat }}
            </span>
            <div class="mt-2 space-y-2">
                <a href="{{ route('admin.siswa.cetakSuratPeringatan', $siswa->id) }}" target="_blank" class="block text-xs text-blue-600 hover:underline font-bold">
                    Cetak Surat Peringatan &rarr;
                </a>
                <a href="{{ route('admin.siswa.cetakPanggilan', $siswa->id) }}" target="_blank" class="block text-xs text-red-600 hover:underline font-bold">
                    Cetak Panggilan Orang Tua &rarr;
                </a>
            </div>
        </div>
    @else
        <div class="mt-2 text-center text-xs text-green-600 font-semibold">
            Status Aman
        </div>
    @endif
</div> {{-- <--- PENUTUP KARTU POIN (Wajib ada di sini agar grid tidak pecah) --}}

{{-- TOTAL PRESTASI --}}
<div class="w-full mt-4 p-4 rounded-lg bg-blue-50 border border-blue-200">
    <div class="text-center">
        <span class="block text-xs uppercase font-bold text-blue-500">Total Prestasi</span>
        <span class="block text-3xl font-extrabold text-blue-600">
            {{ $totalPrestasi }}
        </span>
    </div>
</div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: RIWAYAT & AKSI --}}
                <div class="md:col-span-2 space-y-6">
                    
                    {{-- TOMBOL AKSI CEPAT --}}
                    <div class="bg-white shadow-sm sm:rounded-lg p-4 flex flex-col sm:flex-row gap-4">
                        {{-- Tombol Pelanggaran --}}
                        <button onclick="openModal('violationModal')" class="flex-1 bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition flex items-center justify-center font-bold shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Catat Pelanggaran
                        </button>
                        {{-- Tombol Prestasi (SUDAH AKTIF) --}}
                        <button onclick="openModal('achievementModal')" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center font-bold shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            Catat Prestasi
                        </button>
                    </div>

                    {{-- TABEL 1: RIWAYAT PELANGGARAN --}}
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-red-100">
                        <div class="px-6 py-4 border-b border-red-100 bg-red-50 flex justify-between items-center">
                            <h3 class="font-bold text-red-700 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Riwayat Pelanggaran
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggaran</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Poin</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
    @forelse ($siswa->pelanggaranSiswa as $history)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{-- Ambil tanggal dari tabel pivot --}}
                {{ \Carbon\Carbon::parse($history->pivot->tanggal)->translatedFormat('d M Y') }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                {{-- Nama pelanggaran langsung dari object $history --}}
                <div class="font-bold">{{ $history->nama_pelanggaran }}</div>
                
                {{-- Keterangan ada di pivot --}}
                <div class="text-xs text-gray-500">{{ $history->pivot->keterangan ?? '-' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-red-600">
                {{-- Poin saat itu ada di pivot --}}
                +{{ $history->pivot->poin_saat_itu }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                {{-- ID untuk hapus juga ada di pivot --}}
                <form action="{{ route('admin.pelanggaran.destroySiswa', $history->pivot->id) }}" method="POST" onsubmit="return confirm('Yakin hapus riwayat ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400 italic">Belum ada pelanggaran.</td>
        </tr>
    @endforelse
</tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TABEL 2: RIWAYAT PRESTASI --}}
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-blue-100">
                        <div class="px-6 py-4 border-b border-blue-100 bg-blue-50 flex justify-between items-center">
                            <h3 class="font-bold text-blue-700 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                Riwayat Prestasi
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Prestasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tingkat</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($prestasi as $pres)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $pres->tanggal ? $pres->tanggal->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="font-bold">{{ $pres->judul }}</div>
                                                <div class="text-xs text-gray-500">{{ $pres->penyelenggara }} ({{ $pres->kategori }})</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $pres->tingkat }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('admin.prestasi.destroySiswa', $pres->id) }}" method="POST" onsubmit="return confirm('Yakin hapus prestasi ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400 italic">Belum ada data prestasi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INPUT PELANGGARAN --}}
    <div id="violationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('violationModal')"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-red-600 px-4 py-3 text-white"><h3 class="text-lg font-bold">Catat Pelanggaran</h3></div>
                <form action="{{ route('admin.pelanggaran.storeSiswa') }}" method="POST">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
                            <select name="pelanggaran_id" required class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="" disabled selected>-- Pilih --</option>
                                @foreach($masterPelanggaran as $mp)
                                    <option value="{{ $mp->id }}">{{ $mp->nama_pelanggaran }} ({{ $mp->poin }} Poin)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" onclick="closeModal('violationModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL INPUT PRESTASI (BARU) --}}
    <div id="achievementModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('achievementModal')"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-blue-600 px-4 py-3 text-white"><h3 class="text-lg font-bold">Catat Prestasi Siswa</h3></div>
                
                <form action="{{ route('admin.prestasi.storeSiswa') }}" method="POST">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Judul Prestasi / Lomba</label>
                            <input type="text" name="judul" required placeholder="Juara 1 Lomba Coding..." class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkat</label>
                                <select name="tingkat" required class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori" required class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                                    <option value="Akademik">Akademik</option>
                                    <option value="Non-akademik">Non-akademik</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Penyelenggara</label>
                            <input type="text" name="penyelenggara" placeholder="Dinas Pendidikan..." class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="2" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan Prestasi</button>
                        <button type="button" onclick="closeModal('achievementModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</x-app-layout>