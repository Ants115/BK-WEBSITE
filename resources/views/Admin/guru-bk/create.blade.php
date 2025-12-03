<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Guru BK
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.guru-bk.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Akun (untuk login)</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <p class="mt-1 text-xs text-gray-500">
                                Beritahukan password ini ke guru yang bersangkutan, dan anjurkan untuk menggantinya setelah login.
                            </p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <x-input-error :messages="$errors->get('nip')" class="mt-2"/>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2"/>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan','Guru BK') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2"/>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.guru-bk.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded-md text-sm">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
