<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat dengan <span class="text-indigo-600">{{ $lawanBicara->name }}</span>
            </h2>
            <a href="{{ route('chat.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden flex flex-col h-[80vh]">
                
                {{-- AREA CHAT --}}
                <div class="flex-1 p-6 overflow-y-auto bg-gray-50 space-y-4" id="chat-box">
                    @forelse($chats as $chat)
                        <div class="flex w-full {{ $chat->pengirim_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] flex flex-col {{ $chat->pengirim_id == Auth::id() ? 'items-end' : 'items-start' }}">
                                <div class="px-4 py-3 rounded-2xl shadow-sm text-sm 
                                    {{ $chat->pengirim_id == Auth::id() 
                                        ? 'bg-indigo-600 text-white rounded-br-none' 
                                        : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none' }}">
                                    {{ $chat->isi }}
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 mx-1">
                                    {{ $chat->created_at->format('d M H:i') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <i class="ri-chat-1-line text-4xl mb-2"></i>
                            <p>Belum ada riwayat percakapan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- FORM INPUT --}}
                <div class="p-4 bg-white border-t border-gray-200">
                    <form action="{{ route('chat.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="penerima_id" value="{{ $lawanBicara->id }}">
                        
                        <input type="text" name="isi" required autocomplete="off" autofocus
                            class="flex-1 border-gray-300 rounded-full px-4 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Ketik balasan untuk {{ $lawanBicara->name }}...">
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-semibold transition shadow-md">
                            Kirim <i class="ri-send-plane-fill ml-1"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Auto scroll ke pesan paling bawah
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</x-app-layout>