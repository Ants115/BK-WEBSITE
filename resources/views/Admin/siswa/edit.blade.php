<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Siswa: ') . $siswa->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                <form method="POST" action="{{ route('admin.siswa.update', $siswa->id) }}">
    @csrf
    @method('PATCH') <div>
        <x-input-label for="name" :value="__('Nama Lengkap')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $siswa->name)" required autofocus />
    </div>

    <div class="mt-4">
        <x-input-label for="nis" :value="__('NIS')" />
        <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis', $siswa->biodataSiswa->nis ?? '')" required />
    </div>

    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $siswa->email)" required />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __('Update Siswa') }}
        </x-primary-button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>