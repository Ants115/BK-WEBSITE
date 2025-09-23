<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Status Kelulusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="p-4 mb-6 bg-green-100 border-l-4 border-green-500 text-green-700">
                        <h3 class="text-lg font-bold">Selamat atas Kelulusan Anda!</h3>
                        <p>Anda telah menyelesaikan studi di SMK Antartika 1 Sidoarjo pada tahun ajaran {{ $user->biodataSiswa->tahun_lulus ?? 'N/A' }}.</p>
                    </div>

                    <h3 class="text-lg font-medium">Profil Anda</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>NIS:</strong> {{ $user->biodataSiswa->nis ?? 'Belum diatur' }}</p>
                        </div>
                        <div>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Kelas Terakhir:</strong> {{ $user->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-sm text-gray-600">
                        <p>Akun Anda beserta riwayat bimbingan konseling selama di sekolah tetap diarsipkan oleh pihak sekolah. Terima kasih telah menjadi bagian dari kami dan semoga sukses selalu menyertai Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>