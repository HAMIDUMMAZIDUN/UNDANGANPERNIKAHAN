<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">
            Lupa Password?
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Tidak masalah. Cukup masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Kirim Tautan Reset Password') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>