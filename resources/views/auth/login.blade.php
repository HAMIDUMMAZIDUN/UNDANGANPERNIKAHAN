<x-guest-layout>
    <!-- Judul Form -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">
            Selamat Datang Kembali!
        </h2>
        <p class="text-sm text-gray-500">
            Masuk untuk melanjutkan
        </p>
    </div>

    <!-- Menampilkan pesan sukses (jika ada) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- DIPERBARUI: Menampilkan pesan error umum dari controller -->
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-3 rounded-md text-center">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Opsi Tambahan -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- DIPERBARUI: Menambahkan Google reCAPTCHA -->
        <div class="mt-4 flex justify-center">
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        </div>
        <!-- Menampilkan error validasi untuk reCAPTCHA -->
        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2 text-center" />


        <!-- Tombol Login -->
        <div class="flex flex-col items-center mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
        
        <!-- Link ke Halaman Register -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 underline hover:text-indigo-500">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>

<!-- DIPERBARUI: Menambahkan script untuk reCAPTCHA -->
{{-- Sebaiknya letakkan script ini di file layout utama (guest.blade.php) di dalam tag <head> --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>