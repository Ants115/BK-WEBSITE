<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prestasi Saya
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($prestasi->isEmpty())
                        <p class="text-sm text-gray-600">
                            Belum ada data prestasi yang tercatat.
                            Silakan hubungi Guru BK jika merasa ada prestasi yang belum diinput.
                        </p>
                    @else
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">
                                Total prestasi: <span class="font-semibold">{{ $prestasi->total() }}</span>
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b bg-gray-50">
                                        <th class="px-4 py-2 text-left">#</th>
                                        <th class="px-4 py-2 text-left">Judul</th>
                                        <th class="px-4 py-2 text-left">Kategori</th>
                                        <th class="px-4 py-2 text-left">Tingkat</th>
                                        <th class="px-4 py-2 text-left">Penyelenggara</th>
                                        <th class="px-4 py-2 text-left">Tanggal</th>
                                        <th class="px-4 py-2 text-left">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prestasi as $index => $item)
                                        <tr class="border-b">
                                            <td class="px-4 py-2 align-top">
                                                {{ $prestasi->firstItem() + $index }}
                                            </td>
                                            <td class="px-4 py-2 align-top font-semibold">
                                                {{ $item->judul }}
                                            </td>
                                            <td class="px-4 py-2 align-top">
                                                {{ $item->kategori }}
                                            </td>
                                            <td class="px-4 py-2 align-top">
                                                {{ $item->tingkat }}
                                            </td>
                                            <td class="px-4 py-2 align-top">
                                                {{ $item->penyelenggara ?? '-' }}
                                            </td>
                                            <td class="px-4 py-2 align-top">
                                                {{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 align-top">
                                                {{ $item->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $prestasi->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
