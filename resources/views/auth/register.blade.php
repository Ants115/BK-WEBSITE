<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="nis" :value="__('NIS')" />
            <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis')" required autocomplete="nis" />
            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="tingkatan_id" :value="__('Tingkatan')" />
            <select name="tingkatan_id" id="tingkatan_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled selected>-- Pilih Tingkatan --</option>
                @foreach ($tingkatanList as $tingkatan)
                    <option value="{{ $tingkatan->id }}" {{ old('tingkatan_id') == $tingkatan->id ? 'selected' : '' }}>
                        {{ $tingkatan->nama_tingkatan }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('tingkatan_id')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="jurusan_id" :value="__('Jurusan')" />
            <select name="jurusan_id" id="jurusan_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled selected>-- Pilih Jurusan --</option>
                @foreach ($jurusanList as $jurusan)
                    <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                        {{ $jurusan->nama_jurusan }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('jurusan_id')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="nama_unik" :value="__('Nomor Kelas')" />
            <select name="nama_unik" id="nama_unik" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled selected>-- Pilih Nomor Kelas --</option>
                @foreach ($nomorKelasList as $nomor)
                    <option value="{{ $nomor }}" {{ old('nama_unik') == $nomor ? 'selected' : '' }}>
                        {{ $nomor }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('nama_unik')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                       type="password"
                       name="password"
                       required autocomplete="new-password" />
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer toggle-password"
                      toggle="#password">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                       type="password"
                       name="password_confirmation" required autocomplete="new-password" />
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer toggle-password"
                      toggle="#password_confirmation">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
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