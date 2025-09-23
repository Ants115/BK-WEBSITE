<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Arsip Alumni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Pilih Angkatan Kelulusan</h3>
                    @if($tahunLulusList->isEmpty())
                        <p class="text-gray-500">Belum ada data alumni yang diarsipkan.</p>
                    @else
                        <div class="list-group">
                            @foreach($tahunLulusList as $tahun)
                                @php
                                    // Ganti slash dengan underscore untuk URL yang aman
                                    $tahunUrl = str_replace('/', '_', $tahun);
                                @endphp
                                <a href="{{ route('admin.arsip.show', $tahunUrl) }}" class="block p-4 mb-2 border rounded-md hover:bg-gray-100">
                                    Arsip Bimbingan Konseling Tahun Ajaran {{ $tahun }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>