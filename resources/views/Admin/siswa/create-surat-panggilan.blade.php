<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Surat Panggilan Orang Tua untuk: {{ $siswa->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('admin.siswa.cetakSuratPanggilan', $siswa->id) }}" target="_blank">
                        @csrf

                        <div>
                            <label for="pesan" class="block text-sm font-medium text-gray-700">
                                Pesan / Isi Surat
                            </label>
                            <p class="text-xs text-gray-500 mb-2">Tulis isi surat panggilan di sini. Anda bisa menambahkan detail waktu, tempat, dan keperluan pertemuan.</p>
                            <textarea name="pesan" id="pesan" rows="10" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                      required>{{ old('pesan', "Dengan ini kami mengharap kehadiran Bapak/Ibu Orang Tua/Wali dari siswa tersebut di atas untuk hadir di sekolah pada:\n\nHari/Tanggal : \nHari, DD MMMM YYYY\n\nWaktu : \n09:00 WIB\n\nTempat : \nRuang Bimbingan Konseling\n\nKeperluan : \nMembicarakan perkembangan belajar putra/putri Bapak/Ibu.\n\nDemikian surat panggilan ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.") }}</textarea>
                            <x-input-error :messages="$errors->get('pesan')" class="mt-2" />
                        </div>
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Cetak PDF Surat Panggilan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>