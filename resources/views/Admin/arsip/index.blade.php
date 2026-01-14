<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Arsip Alumni (Daftar Angkatan)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Pilih Tahun Kelulusan</h3>
                    <p class="text-sm text-gray-500">Klik pada tahun angkatan untuk melihat daftar alumni.</p>
                </div>

                @if($angkatan->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($angkatan as $item)
                           {{-- GANTI BARIS INI --}}
<a href="{{ route('admin.arsip.show', str_replace('/', '-', $item->tahun_lulus)) }}" class="block group">
                                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 hover:bg-indigo-600 hover:text-white hover:shadow-lg transition duration-300 text-center cursor-pointer">
                                    
                                    {{-- Ikon Topi Wisuda --}}
                                    <div class="mb-3 inline-flex items-center justify-center w-12 h-12 rounded-full bg-white text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                    </div>

                                    <h4 class="text-xl font-bold group-hover:text-white text-gray-800">
                                        Angkatan {{ $item->tahun_lulus }}
                                    </h4>
                                    
                                    <p class="text-xs mt-2 text-indigo-400 group-hover:text-indigo-200 font-medium">
                                        Klik untuk detail &rarr;
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Alumni</h3>
                        <p class="mt-1 text-sm text-gray-500">Data kelulusan siswa belum tersedia.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>