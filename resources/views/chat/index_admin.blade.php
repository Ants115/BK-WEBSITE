<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chat Terbaru</h3>

                    @if($users->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            Belum ada pesan dari siswa manapun.
                        </div>
                    @else
                        <div class="grid gap-4">
                            @foreach($users as $user)
                                <a href="{{ route('chat.show', $user->id) }}" class="block hover:bg-gray-50 transition duration-150 border rounded-lg p-4 flex items-center justify-between group">
                                    <div class="flex items-center gap-4">
                                        {{-- Avatar Inisial --}}
                                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 group-hover:text-indigo-600">{{ $user->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            <span class="text-xs text-gray-400">Klik untuk membalas</span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-indigo-600">
                                        <i class="ri-chat-3-line text-2xl"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>