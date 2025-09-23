<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Arsip Alumni Angkatan {{ $tahunAjaran }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('admin.arsip.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Angkatan</a>
                    </div>
                    
                    @forelse ($alumniGroupedByKelas as $namaKelas => $alumniDalamKelas)
                        <div class="mb-6 border rounded-lg overflow-hidden">
                            <div class="bg-gray-100 p-4 border-b">
                                <h3 class="font-semibold text-lg">{{ $namaKelas }}</h3>
                            </div>
                            
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-2 px-4 text-left font-medium text-gray-600">Nama Lengkap</th>
                                        <th class="py-2 px-4 text-left font-medium text-gray-600">NIS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alumniDalamKelas as $alumni)
                                        <tr class="border-t">
                                            <td class="py-2 px-4">{{ $alumni->user->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $alumni->nis }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <p class="text-gray-500">Tidak ada data alumni untuk angkatan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>