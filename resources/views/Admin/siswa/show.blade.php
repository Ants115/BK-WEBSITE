<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Siswa: {{ $siswa->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Form Tambah Pelanggaran -->
            <div class="md:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tambah Catatan Pelanggaran</h3>
                        
                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.pelanggaran-siswa.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="siswa_user_id" value="{{ $siswa->id }}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="pelanggaran_id" class="block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
                                    <select name="pelanggaran_id" id="pelanggaran_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('pelanggaran_id') border-red-500 @enderror" required>
                                        <option value="" disabled selected>-- Pilih Pelanggaran --</option>
                                        @foreach ($masterPelanggaran as $pelanggaran)
                                            <option value="{{ $pelanggaran->id }}" @selected(old('pelanggaran_id') == $pelanggaran->id)>
                                                {{ $pelanggaran->nama_pelanggaran }} ({{ $pelanggaran->poin }} poin)
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('pelanggaran_id')" class="mt-2" />
                                </div>
                        
                                <div>
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                                    <input type="date" name="tanggal" id="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', now()->toDateString()) }}" required>
                                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                                </div>
                        
                                <div>
                                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                                    <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('keterangan') }}</textarea>
                                    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Simpan Pelanggaran
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail & Riwayat -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                       <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4">Profil Siswa</h3>
                            <p><strong>Nama:</strong> {{ $siswa->name }}</p>
                            <p><strong>NIS:</strong> {{ $siswa->biodataSiswa->nis ?? 'N/A' }}</p>
                            <p><strong>Kelas:</strong> {{ $siswa->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</p>
                            
                            @php
                                $bgColorClass = 'bg-blue-100 border-blue-400 text-blue-700'; // Aman
                                if ($totalPoin >= 100) $bgColorClass = 'bg-red-100 border-red-400 text-red-700';
                                elseif ($totalPoin >= 50) $bgColorClass = 'bg-yellow-100 border-yellow-400 text-yellow-700';
                                elseif ($totalPoin >= 25) $bgColorClass = 'bg-orange-100 border-orange-400 text-orange-700';
                            @endphp

                            <div class="mt-4 p-4 {{ $bgColorClass }} border rounded-lg">
                                <p class="text-xl font-bold">Total Poin Pelanggaran: {{ $totalPoin }}</p>
                            </div>

                            <!-- 👇 KODE DUPLIKAT DIHAPUS, HANYA TERSISA SATU BLOK TOMBOL 👇 -->
                            <div class="mt-4 flex space-x-2">
                                @if($jenisSurat)
                                    <a href="{{ route('admin.siswa.cetakSuratPeringatan', ['siswa' => $siswa->id, 'jenis' => $jenisSurat]) }}" target="_blank" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        <span>Cetak {{ $jenisSurat }}</span>
                                    </a>
                                @endif

                                <a href="{{ route('admin.siswa.createSuratPanggilan', $siswa->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span>Buat Surat Panggilan Ortu</span>
                                </a>
                            </div>
                       </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                       <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4">Riwayat Pelanggaran</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="py-2 px-4 text-left">Tanggal</th>
                                            <th class="py-2 px-4 text-left">Pelanggaran</th>
                                            <th class="py-2 px-4 text-center">Poin</th>
                                            <th class="py-2 px-4 text-left">Keterangan</th>
                                            <th class="py-2 px-4 text-left">Dicatat oleh</th>
                                            <th class="py-2 px-4 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($siswa->pelanggaranSiswa as $item)
                                            <tr class="border-b">
                                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}</td>
                                                <td class="py-2 px-4">{{ $item->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                                                <td class="py-2 px-4 text-center">{{ $item->pelanggaran->poin ?? 'N/A' }}</td>
                                                <td class="py-2 px-4">{{ $item->keterangan ?? '-' }}</td>
                                                <td class="py-2 px-4">{{ $item->pelapor->name ?? 'N/A' }}</td>
                                                <td class="py-2 px-4">
                                                    <form action="{{ route('admin.pelanggaran-siswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-gray-500">
                                                    Belum ada riwayat pelanggaran.
                                                </td>
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
</x-app-layout>