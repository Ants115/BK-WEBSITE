<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Janji Temu Konsultasi
        </h2>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('konsultasi.store') }}">
                        @csrf

                        <div class="mb-4">
    <label for="guru_id" class="block text-sm font-medium text-gray-700">Pilih Guru BK</label>
    <select name="guru_id" id="guru_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        
        <option value="" disabled selected>-- Pilih Guru Bimbingan Konseling --</option>

        @foreach($guruList as $guru)
            <option value="{{ $guru->id }}">{{ $guru->name }}</option>
        @endforeach
    </select>
</div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="jam" class="block text-sm font-medium text-gray-700">Pilih Jam</label>
                                <input type="time" name="jam" id="jam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="topik" class="block text-sm font-medium text-gray-700">Topik yang Ingin Dibahas</label>
                            <textarea name="topik" id="topik" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                Ajukan Permintaan
                            </button>
                        </div>
                    </form>
                    @if(isset($jadwalList) && $jadwalList->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">
            Jadwal Konsultasi Guru BK
        </h3>
        <p class="text-sm text-gray-600 mb-3">
            Gunakan informasi jadwal berikut sebagai panduan memilih tanggal dan jam konsultasi.
        </p>

        <div class="overflow-x-auto bg-white shadow-sm rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Guru BK</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal / Hari</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwalList as $jadwal)
                        <tr>
                            <td class="px-4 py-2">
                                {{ $jadwal->guru->name ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @if($jadwal->tanggal)
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                                @elseif($jadwal->hari)
                                    {{ $jadwal->hari }}
                                @else
                                    <span class="text-gray-400 italic">Tidak ditentukan</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                {{ $jadwal->jam_mulai }} @if($jadwal->jam_selesai) - {{ $jadwal->jam_selesai }} @endif
                            </td>
                            <td class="px-4 py-2">
                                {{ $jadwal->lokasi ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-gray-600">
                                {{ $jadwal->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>