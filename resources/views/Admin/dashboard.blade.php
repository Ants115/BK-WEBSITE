<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
    <p class="mb-4">Selamat datang di Dashboard Admin!</p>
    
    <div class="flex gap-4">
        <a href="{{ route('admin.siswa.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            Lihat Daftar Siswa
        </a>
        <a href="{{ route('admin.pelanggaran.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
            Manajemen Pelanggaran
        </a>
    </div>
</div>
        </div>
    </div>
</x-app-layout>