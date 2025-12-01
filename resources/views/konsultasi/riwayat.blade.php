<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pengajuan Konsultasi Anda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse($riwayat as $item)
                        <div class="border-l-4 
                            @if($item->status == 'Menunggu Persetujuan') border-yellow-400
                            @elseif($item->status == 'Disetujui') border-green-400
                            @elseif($item->status == 'Ditolak') border-red-400
                            @else border-blue-400 @endif
                            pl-4 py-2 mb-4 bg-gray-50 rounded-r-md p-4">
                            
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-lg text-gray-800">Konsultasi dengan: {{ $item->guru->name }}</p>
                                    <p class="text-sm text-gray-600">Topik: {{ $item->topik }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                </div>
                            </div>
                            
                            <div class="mt-3 flex justify-between items-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($item->status == 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                                    @elseif($item->status == 'Disetujui') bg-green-100 text-green-800
                                    @elseif($item->status == 'Ditolak') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $item->status }}
                                </span>

                                @if($item->status == 'Menunggu Persetujuan')
                                    <form action="{{ route('konsultasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan dan menghapus pengajuan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-bold hover:underline transition duration-150 ease-in-out">
                                            Batalkan Pengajuan
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($item->status == 'Disetujui' || $item->status == 'Dijadwalkan Ulang')
                                <div class="mt-3 bg-blue-50 border border-blue-100 p-3 rounded text-blue-800 text-sm flex justify-between items-center">
                                    
                                    <div>
                                        <span class="font-bold">📅 Jadwal Final:</span> 
                                        {{ \Carbon\Carbon::parse($item->jadwal_disetujui)->translatedFormat('l, d F Y') }} 
                                        pukul {{ \Carbon\Carbon::parse($item->jadwal_disetujui)->format('H:i') }}
                                    </div>

                                    @if($item->status == 'Disetujui')
                                        <a href="{{ route('konsultasi.cetak', $item->id) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150 ml-4">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Cetak Tiket
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if($item->pesan_guru)
                                <div class="mt-3 p-3 bg-gray-100 rounded-md border border-gray-200">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pesan dari Guru:</p>
                                    <p class="text-sm text-gray-700 italic mt-1">"{{ $item->pesan_guru }}"</p>
                                </div>
                            @endif
                            <!-- Hasil Konseling (Muncul jika status Selesai) -->
                            @if($item->status == 'Selesai' && $item->hasil_konseling)
                                <div class="mt-3 p-4 bg-green-50 border border-green-200 rounded-md">
                                    <p class="text-xs font-bold text-green-700 uppercase tracking-wide mb-1">✅ Hasil Konseling:</p>
                                    <p class="text-sm text-gray-800 leading-relaxed">
                                        {!! nl2br(e($item->hasil_konseling)) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat</h3>
                            <p class="mt-1 text-sm text-gray-500">Anda belum pernah mengajukan jadwal konsultasi.</p>
                            <div class="mt-6">
                                <a href="{{ route('konsultasi.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Buat Janji Temu Baru
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>