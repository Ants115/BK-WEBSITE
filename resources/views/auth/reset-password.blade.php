<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Buat Kata Sandi Baru</h2>
    <p class="text-sm text-gray-600 mb-6">
        Silakan masukkan kata sandi baru untuk mengamankan akun Anda.
    </p>

    <x-input-error :messages="$errors->get('email')" class="mb-2" />
    <x-input-error :messages="$errors->get('password')" class="mb-2" />
    <x-input-error :messages="$errors->get('password_confirmation')" class="mb-4" />

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 bg-gray-100 text-gray-600" 
                   type="email" name="email" :value="old('email', $request->email)" required readonly />
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
            <input id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" 
                   type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" autofocus />
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Kata Sandi</label>
            <input id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" 
                   type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang sandi baru" />
        </div>

        <button type="submit" class="w-full justify-center rounded-md border border-transparent bg-gray-900 py-2.5 px-4 text-sm font-bold text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Simpan Kata Sandi
        </button>
    </form>
</x-guest-layout>