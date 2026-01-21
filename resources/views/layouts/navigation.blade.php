@php
    $user = Auth::user();
@endphp

<nav class="sidebar-nav fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 transform -translate-x-full md:translate-x-0 md:sticky md:top-0 h-screen transition-transform duration-300 flex flex-col shrink-0">
    
    {{-- LOGO AREA --}}
    <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-100 flex-shrink-0">
        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
            BK
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-slate-800 text-sm leading-tight">SMK Antartika 1</span>
            <span class="text-[10px] text-slate-400 uppercase tracking-wider">Sistem Informasi</span>
        </div>
    </div>

    {{-- MENU ITEMS --}}
    <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1 bk-scroll">
        
        {{-- ================= LOGIKA MENU GURU BK (ADMIN) ================= --}}
        @if ($user->role === 'guru_bk' || $user->role === 'admin')
            
            {{-- Navigasi Utama --}}
            <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigasi Utama</div>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-dashboard-line text-lg"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.konsultasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.konsultasi.index') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-chat-smile-2-line text-lg"></i> <span>Konsultasi Siswa</span>
            </a>
            <a href="{{ route('admin.konsultasi.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.konsultasi.laporan') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-file-list-3-line text-lg"></i> <span>Laporan Konsultasi</span>
            </a>

            {{-- Siswa & Kelas --}}
            <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa & Kelas</div>

            <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.siswa.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-team-line text-lg"></i> <span>Daftar Siswa</span>
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kelas.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-building-4-line text-lg"></i> <span>Manajemen Kelas</span>
            </a>
            <a href="{{ route('admin.kenaikan-kelas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kenaikan-kelas.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-skip-up-line text-lg"></i> <span>Kenaikan Kelas</span>
            </a>

            {{-- Pelanggaran & Prestasi --}}
            <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggaran & Prestasi</div>

            <a href="{{ route('admin.pelanggaran.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.pelanggaran.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-alarm-warning-line text-lg"></i> <span>Manajemen Pelanggaran</span>
            </a>
            <a href="{{ route('admin.prestasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.prestasi.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-medal-line text-lg"></i> <span>Prestasi Siswa</span>
            </a>

            {{-- Arsip & Guru --}}
            <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Arsip & Pengguna</div>

            <a href="{{ route('admin.arsip.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.arsip.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-archive-line text-lg"></i> <span>Arsip Alumni</span>
            </a>
            <a href="{{ route('admin.guru-bk.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.guru-bk.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-user-star-line text-lg"></i> <span>Guru BK</span>
            </a>
            <a href="{{ route('admin.staf-guru.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.staf-guru.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-user-settings-line text-lg"></i> <span>Staf Guru</span>
            </a>

        {{-- ================= LOGIKA MENU WALI KELAS ================= --}}
        {{-- PERBAIKAN: Ubah 'wali_kelas' jadi 'walikelas' --}}
        @elseif ($user->role === 'walikelas') 
            
            <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Wali Kelas</div>
            
            <a href="{{ route('wali.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('wali.dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="ri-home-4-line text-lg"></i> <span>Dashboard Wali</span>
            </a>
            {{-- Tambahkan menu wali kelas lain jika ada --}}

        {{-- ================= LOGIKA MENU SISWA (DEFAULT) ================= --}}
        @else
            {{-- Kita kunci lagi biar aman, HANYA SISWA yang bisa lihat menu ini --}}
            @if($user->role === 'siswa')
            
                <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Utama</div>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="ri-home-smile-line text-lg"></i> <span>Dashboard</span>
                </a>

                {{-- Cek Status Kelulusan (Pagar Alumni) --}}
                @if($user->biodataSiswa && $user->biodataSiswa->status !== 'Lulus')
                    <a href="{{ route('konsultasi.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('konsultasi.create') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="ri-add-circle-line text-lg"></i> <span>Buat Janji Temu</span>
                    </a>
                @endif

                <a href="{{ route('konsultasi.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('konsultasi.riwayat') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="ri-history-line text-lg"></i> <span>Riwayat Konsultasi</span>
                </a>

                <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Akademik</div>
                
                <a href="{{ route('siswa.prestasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('siswa.prestasi.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="ri-medal-line text-lg"></i> <span>Prestasi Saya</span>
                </a>
            
            @endif
        @endif

    </div>

    {{-- USER PROFILE BOTTOM (Fixed Footer Sidebar) --}}
    <div class="p-4 border-t border-slate-200 bg-white">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <div class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</div>
                <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-1 py-1.5 text-xs font-medium text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-md transition">
                <i class="ri-user-settings-line"></i> Profil
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-1 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition">
                    <i class="ri-logout-box-r-line"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>