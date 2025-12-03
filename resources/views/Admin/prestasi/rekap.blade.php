<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekap Prestasi Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- FILTER TAHUN --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET"
                          action="{{ route('admin.prestasi.rekap') }}"
                          class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="w-full md:w-1/3">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Tahun Prestasi
                            </label>
                            <select name="tahun" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunOptions as $t)
                                    <option value="{{ $t }}" @selected($tahun == $t)>
                                        {{ $t }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700">
                                Tampilkan
                            </button>
                            <a href="{{ route('admin.prestasi.rekap') }}"
                               class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-200">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RINGKASAN CEPAT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-xs uppercase text-gray-500 mb-1">
                            Total Prestasi Tercatat
                        </p>
                        <p class="text-3xl font-bold text-indigo-700">
                            {{ $totalPrestasi }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            @if($tahun)
                                Untuk tahun {{ $tahun }}
                            @else
                                Semua tahun
                            @endif
                        </p>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-xs uppercase text-gray-500 mb-1">
                            Total Siswa Berprestasi
                        </p>
                        <p class="text-3xl font-bold text-green-700">
                            {{ $totalSiswa }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            Jumlah siswa unik yang memiliki minimal satu prestasi.
                        </p>
                    </div>
                </div>
            </div>

            {{-- REKAP PER KELAS --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Rekap per Kelas
                        </h3>
                        <p class="text-xs text-gray-500">
                            Klik nama kelas untuk melihat daftar prestasi detail.
                        </p>
                    </div>

                    @if($rekapPerKelas->isEmpty())
                        <p class="text-sm text-gray-500">
                            Belum ada data prestasi untuk filter yang dipilih.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Kelas
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Total Prestasi
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Jumlah Siswa
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rekapPerKelas as $row)
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-indigo-700 font-semibold">
                                                <a href="{{ route('admin.prestasi.index', [
                                                        'kelas_id' => $row->kelas_id,
                                                        'tahun'    => $tahun,
                                                    ]) }}"
                                                   class="hover:underline">
                                                    {{ $row->nama_kelas }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2 text-xs text-gray-800">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-semibold">
                                                    {{ $row->total_prestasi }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-xs text-gray-800">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold">
                                                    {{ $row->total_siswa }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- REKAP PER TINGKAT & KATEGORI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Rekap per Tingkat --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Rekap per Tingkat
                        </h3>

                        @if($rekapPerTingkat->isEmpty())
                            <p class="text-sm text-gray-500">Belum ada data.</p>
                        @else
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Tingkat
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Total Prestasi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rekapPerTingkat as $row)
                                        <tr>
                                            <td class="px-3 py-2 text-xs text-gray-800">
                                                {{ $row->tingkat ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-gray-800">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-semibold">
                                                    {{ $row->total_prestasi }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                {{-- Rekap per Kategori --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Rekap per Kategori
                        </h3>

                        @if($rekapPerKategori->isEmpty())
                            <p class="text-sm text-gray-500">Belum ada data.</p>
                        @else
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Kategori
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Total Prestasi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rekapPerKategori as $row)
                                        <tr>
                                            <td class="px-3 py-2 text-xs text-gray-800">
                                                {{ $row->kategori ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-gray-800">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-semibold">
                                                    {{ $row->total_prestasi }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
