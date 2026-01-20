<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ __('Buat Janji Konsultasi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumb / Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-300 group">
                    <span class="p-1 rounded-full bg-gray-200 group-hover:bg-indigo-100 group-hover:text-indigo-600 mr-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </span>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM PENGAJUAN (CARD UTAMA) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden relative">
                        {{-- Dekorasi Header Card --}}
                        <div class="h-2 bg-indigo-500 w-full absolute top-0 left-0"></div>

                        <div class="p-8">
                            <div class="mb-8">
                                <h3 class="text-2xl font-extrabold text-gray-900">Form Pengajuan </h3>
                                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                                    Ceritakan masalahmu, kami siap mendengarkan. 
                            </div>

                            <form action="{{ route('konsultasi.store') }}" method="POST">
                                @csrf

                                <div class="space-y-6">
                                    {{-- Pilih Guru BK --}}
                                    <div class="group">
                                        <label for="guru_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition">Pilih Guru Pembimbing</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <select name="guru_id" id="guru_id" required 
                                                class="pl-10 w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-300 py-3 text-sm cursor-pointer shadow-sm">
                                                <option value="">-- Pilih Guru --</option>
                                                @foreach($guruBk as $guru)
                                                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <x-input-error :messages="$errors->get('guru_id')" class="mt-2" />
                                    </div>

                                    {{-- Topik Konsultasi --}}
                                    <div class="group">
                                        <label for="topik" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition">Topik Masalah</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                            </div>
                                            <input type="text" name="topik" id="topik" required 
                                                class="pl-10 w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-300 py-3 text-sm shadow-sm"
                                                placeholder="Contoh: Kesulitan Belajar, Karir, dll...">
                                        </div>
                                        <x-input-error :messages="$errors->get('topik')" class="mt-2" />
                                    </div>

                                    {{-- Tanggal & Waktu --}}
                                    <div class="group">
                                        <label for="jadwal_pengajuan" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition">Rencana Waktu Ketemu</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <input type="datetime-local" name="jadwal_pengajuan" id="jadwal_pengajuan" required 
                                                class="pl-10 w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-300 py-3 text-sm shadow-sm text-gray-600">
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1 ml-1">*Pilih waktu sesuai jam kerja sekolah.</p>
                                        <x-input-error :messages="$errors->get('jadwal_pengajuan')" class="mt-2" />
                                    </div>

                                    {{-- Detail Keluhan --}}
                                    <div class="group">
                                        <label for="keluhan" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition">Ceritakan Singkat</label>
                                        <textarea name="keluhan" id="keluhan" rows="5" required 
                                            class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-300 p-4 text-sm shadow-sm"
                                            placeholder="Tuliskan detail apa yang ingin kamu konsultasikan..."></textarea>
                                        <x-input-error :messages="$errors->get('keluhan')" class="mt-2" />
                                    </div>
                                </div>

                                {{-- Tombol Submit --}}
                                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                                        Ajukan Sekarang
                                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: INFO JADWAL GURU --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 rounded-2xl p-6 sticky top-6 shadow-lg">
                        
                        {{-- Header Sidebar --}}
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-indigo-600 rounded-xl text-white shadow-md shadow-indigo-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">Jadwal Guru</h3>
                                <p class="text-xs text-indigo-500 font-medium">Referensi waktu kosong</p>
                            </div>
                        </div>

                        {{-- List Jadwal --}}
                        @if(isset($jadwalList) && $jadwalList->count() > 0)
                            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($jadwalList as $jadwal)
                                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-200 hover:shadow-md transition duration-300 group">
                                        <div class="flex justify-between items-start mb-2">
                                            <p class="font-bold text-gray-800 text-sm group-hover:text-indigo-700 transition">{{ $jadwal->guru->name ?? 'Guru BK' }}</p>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-600 border border-green-100">Aktif</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            {{-- Hari/Tanggal --}}
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span class="font-medium">
                                                    @if($jadwal->hari) {{ $jadwal->hari }} @endif
                                                    @if($jadwal->tanggal) ({{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M') }}) @endif
                                                </span>
                                            </div>

                                            {{-- Jam --}}
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span class="bg-indigo-50 px-2 py-0.5 rounded text-indigo-700 font-bold">
                                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                                </span>
                                            </div>

                                            {{-- Lokasi --}}
                                            @if($jadwal->lokasi)
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    <span>{{ $jadwal->lokasi }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- State Kosong --}}
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-400">Belum ada jadwal</p>
                                <p class="text-xs text-gray-400">Silakan hubungi admin</p>
                            </div>
                        @endif

                        <div class="mt-6 p-3 bg-blue-50 rounded-xl border border-blue-100 flex gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-blue-700 leading-tight">
                                <strong>Tips:</strong> Pilih waktu yang tidak bentrok dengan pelajaran utama.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>