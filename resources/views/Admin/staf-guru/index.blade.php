<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Staf / Guru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <form method="GET" action="{{ route('admin.staf-guru.index') }}" class="flex space-x-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, email, NIP..."
                        class="rounded-md border-gray-300 shadow-sm text-sm"
                    >
                    <button type="submit" class="px-3 py-2 bg-gray-800 text-white text-xs font-semibold rounded-md">
                        Cari
                    </button>
                </form>

                {{-- Kalau nanti mau tambah staf lewat form, route create bisa disiapkan --}}
                {{--
                <a href="{{ route('admin.staf-guru.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md
                          font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    + Tambah Staf / Guru
                </a>
                --}}
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIP
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jabatan
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stafList as $staf)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">
                                            <div class="font-semibold text-gray-900">
                                                {{ $staf->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            {{ $staf->email }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            {{ $staf->biodataStaf->nip ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            {{ $staf->biodataStaf->jabatan ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-right space-x-2">
                                            {{-- Aktifkan kalau sudah siap fitur edit/hapus --}}
                                            {{--
                                            <a href="{{ route('admin.staf-guru.edit', $staf->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.staf-guru.destroy', $staf->id) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus staf ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 font-semibold">
                                                    Hapus
                                                </button>
                                            </form>
                                            --}}
                                            <span class="text-xs text-gray-400 italic">
                                                (aksi belum diaktifkan)
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Belum ada data staf / guru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $stafList->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
