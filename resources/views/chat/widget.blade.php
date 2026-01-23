<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* Sembunyikan scrollbar tapi tetap bisa discroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex flex-col h-screen">
        {{-- HEADER --}}
        <div class="bg-indigo-600 p-3 text-white flex items-center justify-between sticky top-0 z-10 shadow">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-customer-service-2-line"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Admin BK</h3>
                    <p class="text-[10px] text-indigo-100">Online</p>
                </div>
            </div>
        </div>

        {{-- AREA CHAT --}}
        <div class="flex-1 p-3 overflow-y-auto no-scrollbar flex flex-col gap-3" id="chat-container">
            @foreach($chats as $chat)
                <div class="flex {{ $chat->pengirim_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%] px-3 py-2 rounded-lg text-xs shadow-sm 
                        {{ $chat->pengirim_id == Auth::id() ? 'bg-indigo-500 text-white rounded-br-none' : 'bg-white text-gray-800 border rounded-bl-none' }}">
                        <p>{{ $chat->isi }}</p>
                        <span class="text-[9px] opacity-70 block text-right mt-1">
                            {{ $chat->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FORM INPUT --}}
        <div class="p-2 bg-white border-t sticky bottom-0">
            <form action="{{ route('chat.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="penerima_id" value="{{ $lawanBicara->id }}">
                <input type="text" name="isi" required autocomplete="off"
                    class="flex-1 text-sm border-gray-300 rounded-full px-3 py-2 focus:outline-none focus:border-indigo-500 bg-gray-100"
                    placeholder="Tulis...">
                <button type="submit" class="bg-indigo-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-indigo-700">
                    <i class="ri-send-plane-fill text-sm"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Auto scroll ke bawah
        window.onload = function() {
            const container = document.getElementById('chat-container');
            container.scrollTop = container.scrollHeight;
        }
    </script>
</body>
</html>