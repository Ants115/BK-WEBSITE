<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Permintaan Konsultasi
        </h2>
    </x-slot>

    <div x-data="{ openModal: '', selectedKonsultasiId: null }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($permintaan as $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="font-semibold text-gray-900">{{ $item->siswa->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->siswa->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @php
                                                $jadwal = $item->jadwal_aktif;
                                            @endphp
                                            @if($jadwal)
                                                {{ $jadwal->format('d M Y, H:i') }}
                                            @else
                                                <span class="text-gray-400 italic">Belum dijadwalkan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $badgeClasses = 'bg-blue-100 text-blue-800';
                                                if ($item->status === \App\Models\Konsultasi::STATUS_MENUNGGU) {
                                                    $badgeClasses = 'bg-yellow-100 text-yellow-800';
                                                } elseif ($item->status === \App\Models\Konsultasi::STATUS_DISETUJUI) {
                                                    $badgeClasses = 'bg-green-100 text-green-800';
                                                } elseif ($item->status === \App\Models\Konsultasi::STATUS_DITOLAK) {
                                                    $badgeClasses = 'bg-red-100 text-red-800';
                                                } elseif ($item->status === \App\Models\Konsultasi::STATUS_SELESAI) {
                                                    $badgeClasses = 'bg-gray-100 text-gray-800';
                                                }
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                                            @if($item->status === \App\Models\Konsultasi::STATUS_MENUNGGU)
                                                {{-- Setujui --}}
                                                <form action="{{ route('admin.konsultasi.setujui', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-semibold">
                                                        Setujui
                                                    </button>
                                                </form>

                                                {{-- Tolak --}}
                                                <button
                                                    @click="openModal = 'tolak'; selectedKonsultasiId = {{ $item->id }}"
                                                    class="text-red-600 hover:text-red-900 font-semibold">
                                                    Tolak
                                                </button>

                                                {{-- Jadwal Ulang --}}
                                                <button
                                                    @click="openModal = 'jadwalUlang'; selectedKonsultasiId = {{ $item->id }}"
                                                    class="text-blue-600 hover:text-blue-900 font-semibold">
                                                    Jadwal Ulang
                                                </button>

                                            @elseif($item->status === \App\Models\Konsultasi::STATUS_DISETUJUI)
                                                {{-- Tandai Selesai --}}
                                                <button
                                                    @click="openModal = 'selesai'; selectedKonsultasiId = {{ $item->id }}"
                                                    class="text-indigo-600 hover:text-indigo-900 font-semibold border border-indigo-600 px-3 py-1 rounded hover:bg-indigo-50">
                                                    Tandai Selesai
                                                </button>

                                            @elseif($item->status === \App\Models\Konsultasi::STATUS_SELESAI)
                                                <span class="text-gray-400 italic">Selesai</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada permintaan konsultasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL TOLAK --}}
        <div x-show="openModal === 'tolak'" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div @click="openModal = ''" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="`/admin/konsultasi/${selectedKonsultasiId}/tolak`" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Tolak Permintaan Konsultasi</h3>
                            <div class="mt-2">
                                <label for="pesan_guru_tolak" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                                <textarea name="pesan_guru" id="pesan_guru_tolak" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Tolak
                            </button>
                            <button @click="openModal = ''" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL JADWAL ULANG --}}
        <div x-show="openModal === 'jadwalUlang'" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div @click="openModal = ''" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="`/admin/konsultasi/${selectedKonsultasiId}/jadwalkan-ulang`" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Jadwalkan Ulang Konsultasi</h3>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tanggal_baru" class="block text-sm font-medium text-gray-700">Tanggal Baru</label>
                                    <input type="date" name="tanggal_baru" id="tanggal_baru" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="jam_baru" class="block text-sm font-medium text-gray-700">Jam Baru</label>
                                    <input type="time" name="jam_baru" id="jam_baru" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="pesan_guru_reschedule" class="block text-sm font-medium text-gray-700">Pesan Tambahan</label>
                                <textarea name="pesan_guru" id="pesan_guru_reschedule" placeholder="Contoh: Maaf, di jadwal sebelumnya saya berhalangan." rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Jadwalkan Ulang
                            </button>
                            <button @click="openModal = ''" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL SELESAI --}}
        <div x-show="openModal === 'selesai'" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div @click="openModal = ''" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="`/admin/konsultasi/${selectedKonsultasiId}/selesaikan`" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Selesaikan Sesi Konsultasi</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-2">
                                    Silakan masukkan catatan hasil konseling untuk rekam jejak siswa.
                                </p>
                                <label for="hasil_konseling" class="block text-sm font-medium text-gray-700">
                                    Catatan / Hasil Konseling
                                </label>
                                <textarea name="hasil_konseling" id="hasil_konseling" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Siswa sepakat untuk memperbaiki kehadiran..." required></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan &amp; Selesai
                            </button>
                            <button @click="openModal = ''" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
