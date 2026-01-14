<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    @if ($user && $user->role === 'guru_bk')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @elseif($user)
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @else
                        <a href="{{ url('/') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @endif
                </div>

                @if($user)
                    <div class="hidden sm:-my-px sm:ms-10 sm:flex items-center">
                        @if ($user->role === 'guru_bk')
                            {{-- MENU ADMIN BK (via dropdown hamburger kecil) --}}
                            <div x-data="{ adminMenuOpen: false }" class="relative">
                                <button
                                    type="button"
                                    @click="adminMenuOpen = !adminMenuOpen"
                                    @click.away="adminMenuOpen = false"
                                    class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                           viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    <span>Menu Admin BK</span>
                                </button>

                                <div
                                    x-show="adminMenuOpen"
                                    x-transition
                                    class="origin-top-left absolute left-0 mt-2 w-72 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                >
                                    <div class="py-2 text-sm text-gray-700">
                                        <div class="px-4 pb-2 pt-3 font-semibold text-xs text-gray-400 uppercase">
                                            Navigasi Utama
                                        </div>

                                        <a href="{{ route('admin.dashboard') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Admin Dashboard
                                        </a>

                                        <a href="{{ route('admin.konsultasi.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.konsultasi.index') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Konsultasi Siswa
                                        </a>

                                        <a href="{{ route('admin.konsultasi.laporan') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.konsultasi.laporan') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Laporan Konsultasi
                                        </a>

                                        <div class="px-4 pt-3 pb-1 font-semibold text-xs text-gray-400 uppercase">
                                            Siswa &amp; Kelas
                                        </div>

                                        <a href="{{ route('admin.siswa.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.siswa.index') || request()->routeIs('admin.siswa.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Daftar Siswa
                                        </a>

                                        <a href="{{ route('admin.kelas.index') }}"
                                        class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.kelas.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Manajemen Kelas & Wali Kelas
                                        </a>

                                        <a href="{{ route('admin.kenaikan-kelas.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.kenaikan-kelas.index') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Kenaikan Kelas
                                        </a>

                                        <div class="px-4 pt-3 pb-1 font-semibold text-xs text-gray-400 uppercase">
                                            Pelanggaran &amp; Prestasi
                                        </div>

                                        <a href="{{ route('admin.pelanggaran.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.pelanggaran.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Manajemen Pelanggaran
                                        </a>

                                        <a href="{{ route('admin.prestasi.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.prestasi.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Prestasi Siswa
                                        </a>

                                        <div class="px-4 pt-3 pb-1 font-semibold text-xs text-gray-400 uppercase">
                                            Arsip &amp; Guru BK
                                        </div>

                                        <a href="{{ route('admin.arsip.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.arsip.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Arsip Alumni
                                        </a>

                                        <a href="{{ route('admin.guru-bk.index') }}"
                                           class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.guru-bk.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Guru BK
                                        </a>

                                        <a href="{{ route('admin.staf-guru.index') }}"
                                        class="block px-4 py-2 rounded-md {{ request()->routeIs('admin.staf-guru.*') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                            Staf Guru
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        @elseif ($user->role === 'wali_kelas')
                            {{-- MENU UNTUK WALI KELAS --}}
                            <x-nav-link :href="route('wali.dashboard')" :active="request()->routeIs('wali.dashboard')">
                                {{ __('Dashboard Wali Kelas') }}
                            </x-nav-link>
                        @else
                            {{-- MENU UNTUK SISWA --}}
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            {{-- HANYA TAMPIL JIKA STATUS BELUM LULUS --}}
                            @if($user->biodataSiswa && $user->biodataSiswa->status !== 'Lulus')
                                <x-nav-link :href="route('konsultasi.create')" :active="request()->routeIs('konsultasi.create')">
                                    {{ __('Buat Janji Temu') }}
                                </x-nav-link>
                            @endif

                            <x-nav-link :href="route('konsultasi.riwayat')" :active="request()->routeIs('konsultasi.riwayat')">
                                {{ __('Riwayat Konsultasi') }}
                            </x-nav-link>
                        @endif
                    </div>
                @endif
            </div>

            @if($user)
                <div class="hidden sm:flex sm:items-center sm:ms-6">

                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <button class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>

                                    @if($user->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                                            {{ $user->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-100 font-semibold text-xs text-gray-500 uppercase">
                                    Notifikasi Baru
                                </div>

                                @forelse($user->unreadNotifications as $notification)
                                    <x-dropdown-link :href="route('konsultasi.baca_notifikasi', $notification->id)" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="font-bold {{ $notification->data['status'] == 'Disetujui' ? 'text-green-600' : ($notification->data['status'] == 'Ditolak' ? 'text-red-600' : 'text-blue-600') }}">
                                            {{ $notification->data['status'] }}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">
                                            {{ \Illuminate\Support\Str::limit($notification->data['pesan'], 40) }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-1 italic">
                                            Dari: {{ $notification->data['guru_nama'] ?? 'Guru BK' }}
                                        </div>
                                    </x-dropdown-link>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        Tidak ada notifikasi baru.
                                    </div>
                                @endforelse

                                @if($user->unreadNotifications->count() > 0)
                                    <div class="block px-4 py-2 text-xs text-center text-gray-500 border-t border-gray-100">
                                        Klik pesan untuk melihat detail
                                    </div>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ $user->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            @if($user)
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        @click="open = ! open"
                        class="relative inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>

                        @if($user->unreadNotifications->count() > 0)
                            <span class="absolute top-2 right-2 block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white"></span>
                        @endif
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if($user)
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                @if ($user->role === 'guru_bk')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.konsultasi.index')" :active="request()->routeIs('admin.konsultasi.index')">
                        {{ __('Konsultasi Siswa') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.konsultasi.laporan')" :active="request()->routeIs('admin.konsultasi.laporan')">
                        {{ __('Laporan Konsultasi') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.guru-bk.index')" :active="request()->routeIs('admin.guru-bk.*')">
                        {{ __('Guru BK / Staf BK') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.siswa.index')" :active="request()->routeIs('admin.siswa.*')">
                        {{ __('Daftar Siswa') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.kenaikan-kelas.index')" :active="request()->routeIs('admin.kenaikan-kelas.index')">
                        {{ __('Kenaikan Kelas') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.pelanggaran.index')" :active="request()->routeIs('admin.pelanggaran.*')">
                        {{ __('Manajemen Pelanggaran') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.prestasi.index')" :active="request()->routeIs('admin.prestasi.*')">
                        {{ __('Prestasi Siswa') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.arsip.index')" :active="request()->routeIs('admin.arsip.*')">
                        {{ __('Arsip Alumni') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('wali.dashboard')" :active="request()->routeIs('wali.dashboard')">
                        {{ __('Dashboard Wali Kelas') }}
                    </x-responsive-nav-link>
                @elseif ($user->role === 'wali_kelas')
                    <x-responsive-nav-link :href="route('wali.dashboard')" :active="request()->routeIs('wali.dashboard')">
                        {{ __('Dashboard Wali Kelas') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    {{-- MOBILE MENU: HANYA TAMPIL JIKA STATUS BELUM LULUS --}}
                    @if($user->biodataSiswa && $user->biodataSiswa->status !== 'Lulus')
                        <x-responsive-nav-link :href="route('konsultasi.create')" :active="request()->routeIs('konsultasi.create')">
                            {{ __('Buat Janji Temu') }}
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('konsultasi.riwayat')" :active="request()->routeIs('konsultasi.riwayat')">
                        {{ __('Riwayat Konsultasi') }}
                        @if($user->unreadNotifications->count() > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ $user->unreadNotifications->count() }} Baru
                            </span>
                        @endif
                    </x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    @endif
</nav>