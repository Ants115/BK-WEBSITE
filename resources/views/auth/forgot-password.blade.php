<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Lupa Kata Sandi?</h2>
    <p class="text-sm text-gray-600 mb-6">
        Jangan khawatir. Masukkan email terdaftar, kami akan mengirimkan link reset password.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-input-error :messages="$errors->get('email')" class="mb-4" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" 
                   type="email" name="email" :value="old('email')" required autofocus placeholder="nama@sekolah.com" />
        </div>

        <button type="submit" class="w-full justify-center rounded-md border border-transparent bg-gray-900 py-2.5 px-4 text-sm font-bold text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Kirim Link Reset
        </button>
        
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 hover:underline">
                Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>