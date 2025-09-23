<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
    @csrf


        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                       type="password"
                       name="password"
                       required autocomplete="current-password" />

                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer toggle-password"
                      toggle="#password">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    
    <script>
        document.addEventListener('click', function(e) {
            const toggleButton = e.target.closest('.toggle-password');
            
            if (toggleButton) {
                const passwordInput = document.querySelector(toggleButton.getAttribute('toggle'));
                
                if (passwordInput) {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const icon = toggleButton.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }
        });
    </script>
</x-guest-layout>