<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- PENTING UNTUK AJAX --}}
    <title>Daftar - SMK Antartika 1 Sidoarjo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">
            
            {{-- KIRI: FORM REGISTER --}}
            <div class="w-full md:w-3/5 p-8 md:p-12">
                <div class="mb-8">
                    <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun Siswa 🚀</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Lengkapi data diri sesuai kelas yang aktif saat ini.
                    </p>
                    
                    @if ($errors->any())
                        <div class="mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded text-sm text-red-700">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                               placeholder="Nama sesuai absen">
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                               placeholder="email@sekolah.com">
                    </div>

                    {{-- NIS --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIS</label>
                        <input type="number" name="nis" value="{{ old('nis') }}" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                               placeholder="Nomor Induk Siswa">
                    </div>

                    {{-- Tingkatan (ID DITAMBAHKAN) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tingkatan</label>
                        <select name="tingkatan_id" id="tingkatan_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white cursor-pointer">
                            <option value="">Pilih Tingkat</option>
                            @foreach($tingkatanList as $tingkat)
                                <option value="{{ $tingkat->id }}" {{ old('tingkatan_id') == $tingkat->id ? 'selected' : '' }}>
                                    {{ $tingkat->nama_tingkatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jurusan (ID DITAMBAHKAN) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jurusan</label>
                        <select name="jurusan_id" id="jurusan_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white cursor-pointer">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusanList as $jurusan)
                                <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nomor Kelas (ID DITAMBAHKAN & KOSONGKAN DI AWAL) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor Kelas</label>
                        <select name="nama_unik" id="nama_unik" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-gray-100 cursor-not-allowed" disabled>
                            <option value="">Pilih Tingkat & Jurusan dulu</option>
                            {{-- Opsi akan diisi oleh JavaScript --}}
                        </select>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                               placeholder="Minimal 8 karakter">
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                               placeholder="Ulangi password">
                    </div>

                    {{-- Tombol --}}
                    <div class="md:col-span-2 mt-4">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition transform hover:-translate-y-0.5">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-sm text-gray-500">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500">
                        Masuk di sini
                    </a>
                </div>
            </div>

            {{-- KANAN: GAMBAR --}}
            <div class="hidden md:block w-2/5 bg-gradient-to-br from-indigo-600 to-blue-500 relative overflow-hidden">
                <div class="absolute inset-0 bg-pattern opacity-10"></div>
                <div class="h-full flex flex-col justify-center items-center text-white p-8 text-center">
                    <div class="bg-white/20 p-4 rounded-full mb-6 backdrop-blur-sm">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Sistem Informasi BK</h3>
                    <p class="text-indigo-100 text-sm">
                        Mendukung kesuksesan siswa SMK Antartika 1 Sidoarjo.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT UNTUK DROPDOWN DINAMIS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tingkatanSelect = document.getElementById('tingkatan_id');
            const jurusanSelect = document.getElementById('jurusan_id');
            const nomorKelasSelect = document.getElementById('nama_unik');

            function checkAndFetchClasses() {
                const tingkatanId = tingkatanSelect.value;
                const jurusanId = jurusanSelect.value;

                // Reset Dropdown Nomor Kelas
                nomorKelasSelect.innerHTML = '<option value="">Memuat data...</option>';
                nomorKelasSelect.disabled = true;
                nomorKelasSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                nomorKelasSelect.classList.remove('bg-white');

                // Hanya fetch jika kedua dropdown sudah dipilih
                if (tingkatanId && jurusanId) {
                    fetch('{{ route("get.nomor.kelas") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            tingkatan_id: tingkatanId,
                            jurusan_id: jurusanId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        nomorKelasSelect.innerHTML = '<option value="">Pilih Nomor</option>';
                        
                        if (data.length > 0) {
                            data.forEach(nomor => {
                                const option = document.createElement('option');
                                option.value = nomor;
                                option.textContent = nomor;
                                nomorKelasSelect.appendChild(option);
                            });
                            // Aktifkan dropdown
                            nomorKelasSelect.disabled = false;
                            nomorKelasSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
                            nomorKelasSelect.classList.add('bg-white');
                        } else {
                            nomorKelasSelect.innerHTML = '<option value="">Tidak ada kelas tersedia</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        nomorKelasSelect.innerHTML = '<option value="">Gagal memuat</option>';
                    });
                } else {
                    nomorKelasSelect.innerHTML = '<option value="">Pilih Tingkat & Jurusan dulu</option>';
                }
            }

            // Pasang Event Listener
            tingkatanSelect.addEventListener('change', checkAndFetchClasses);
            jurusanSelect.addEventListener('change', checkAndFetchClasses);
        });
    </script>
</body>
</html>