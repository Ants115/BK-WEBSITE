<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Kenaikan Kelas & Kelulusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                        <p class="font-bold">Perhatian!</p>
                        <p>Proses ini akan memindahkan semua siswa dari kelas asal ke kelas tujuan. Pastikan pemetaan sudah benar sebelum melanjutkan. Siswa dari kelas XII yang dipilih "Luluskan" akan diubah statusnya menjadi Alumni.</p>
                    </div>

                    <form action="{{ route('admin.kenaikan-kelas.proses') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses kenaikan kelas ini? Tindakan ini tidak dapat diurungkan.');">
                        @csrf
                        <div class="space-y-6">
                            @forelse($kelasGrouped as $tingkatan => $kelasList)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800 border-b pb-2 mb-2">Kelas Tingkat: {{ $tingkatan }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($kelasList as $kelas)
                                            <div class="flex items-center space-x-2">
                                                <label for="promosi_{{ $kelas->id }}" class="w-1/3">{{ $kelas->nama_kelas }}</label>
                                                <span class="w-auto">→</span>
                                                
                                                <!-- ====================================================== -->
                                                <!-- BAGIAN SELECT INI DIPERBARUI TOTAL -->
                                                <!-- ====================================================== -->
                                                <select name="promosi[{{ $kelas->id }}]" id="promosi_{{ $kelas->id }}" class="w-2/3 block rounded-md border-gray-300 shadow-sm">
                                                    <option value="">-- Jangan Ubah --</option>
                                                    
                                                    {{-- Logika untuk kelas XII --}}
                                                    @if($kelas->tingkatan->nama_tingkatan === 'XII')
                                                        <option value="lulus">Luluskan (Jadi Alumni)</option>

                                                    {{-- Logika untuk kelas X dan XI --}}
                                                    @else
                                                        {{-- Panggil fungsi cerdas yang kita buat di Model --}}
                                                        @php
                                                            $kelasTujuanOtomatis = $kelas->getSuggestedNextClass();
                                                        @endphp

                                                        {{-- Jika kelas tujuan otomatis ditemukan, tampilkan sebagai pilihan utama dan pilih otomatis --}}
                                                        @if($kelasTujuanOtomatis)
                                                            <option value="{{ $kelasTujuanOtomatis->id }}" selected>
                                                                Naik ke {{ $kelasTujuanOtomatis->nama_kelas }}
                                                            </option>
                                                        @endif

                                                        {{-- Selalu berikan opsi untuk tinggal kelas --}}
                                                        <option value="{{ $kelas->id }}">Tinggal di {{ $kelas->nama_kelas }}</option>
                                                        
                                                        {{-- Kelompokkan pilihan manual lainnya agar UI bersih --}}
                                                        <optgroup label="Pilihan Manual Lainnya">
                                                            @foreach($daftarKelasTujuan as $tujuan)
                                                                {{-- Tampilkan hanya kelas yang tingkatannya lebih tinggi & BUKAN kelas yang sudah disarankan otomatis --}}
                                                                @if($tujuan->tingkatan_id > $kelas->tingkatan_id && (!$kelasTujuanOtomatis || $tujuan->id != $kelasTujuanOtomatis->id))
                                                                    <option value="{{ $tujuan->id }}">{{ $tujuan->nama_kelas }}</option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endif

                                                </select>
                                                <!-- ====================================================== -->
                                                <!-- AKHIR DARI BAGIAN SELECT YANG DIPERBARUI -->
                                                <!-- ====================================================== -->
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    <p>Tidak ada kelas dengan siswa aktif yang dapat diproses saat ini.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Hanya tampilkan tombol jika ada kelas yang bisa diproses --}}
                        @if($kelasGrouped->count() > 0)
                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                                    Proses Kenaikan Kelas
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
