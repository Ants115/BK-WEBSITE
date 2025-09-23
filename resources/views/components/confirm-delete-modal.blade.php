@props(['title' => 'Konfirmasi Hapus', 'buttonText' => 'Ya, Hapus'])

<div
    x-data="{
        show: false,
        action: '',
        message: 'Apakah Anda yakin ingin menghapus item ini?'
    }"
    x-on:open-confirm-modal.window="
        show = true;
        action = $event.detail.action;
        message = $event.detail.message;
    "
    x-show="show"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
>
    <div x-show="show" x-transition.opacity.duration.300ms @click="show = false" class="fixed inset-0 bg-black/50"></div>

    <div
        x-show="show"
        x-transition.scale.origin.top.duration.300ms
        class="relative w-full max-w-md bg-white rounded-lg shadow-lg"
    >
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold">{{ $title }}</h3>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <div class="p-6">
            <p x-text="message"></p>
        </div>
        <div class="flex justify-end p-4 bg-gray-50 border-t rounded-b-lg">
            <button @click="show = false" type="button" class="px-4 py-2 mr-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Batal
            </button>
            <form :action="action" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                    {{ $buttonText }}
                </button>
            </form>
        </div>
    </div>
</div>