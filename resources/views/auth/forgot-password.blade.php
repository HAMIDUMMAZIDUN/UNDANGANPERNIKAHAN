<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Menggunakan background yang sama dengan halaman login --}}
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-2">Lupa Password Anda?</h2>
        <p class="text-center text-sm text-gray-500 mb-6">
            Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.
        </p>

        {{-- Menampilkan pesan status jika email berhasil dikirim --}}
        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4 text-sm" role="alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        {{-- Menampilkan error validasi --}}
        @error('email')
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="text-green-500 hover:underline font-medium">
                &larr; Kembali ke Login
            </a>
        </p>
    </div>
</body>
</html>
