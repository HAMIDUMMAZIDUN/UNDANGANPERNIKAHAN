<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menggunakan Font Awesome CDN untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-6">Login Aplikasi</h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            {{-- PERUBAHAN DIMULAI DI SINI --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                {{-- 1. Bungkus input dan ikon dalam satu div dengan class 'relative' --}}
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    {{-- 2. Tambahkan ikon mata dengan posisi 'absolute' di dalam div --}}
                    <span id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <i class="fa-solid fa-eye text-gray-400"></i>
                    </span>
                </div>
            </div>
            {{-- PERUBAHAN SELESAI DI SINI --}}


            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-700">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 border-gray-300 rounded">
                    <span class="ml-2">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-green-500 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">
                LOGIN
            </button>
        </form>
    </div>

    {{-- 3. Tambahkan script JavaScript di bawah --}}
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            // Cek tipe input saat ini
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon mata
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>

</body>
</html>