<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menggunakan Font Awesome CDN untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
{{-- Menyesuaikan background dan styling agar konsisten --}}
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-6">Atur Password Baru</h2>

        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', request()->email) }}" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            {{-- Password Baru dengan Ikon Mata --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <span id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <i class="fa-solid fa-eye text-gray-400"></i>
                    </span>
                </div>
            </div>

            {{-- Konfirmasi Password dengan Ikon Mata --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <span id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <i class="fa-solid fa-eye text-gray-400"></i>
                    </span>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">
                Simpan Password Baru
            </button>
        </form>
    </div>

    <script>
        // Fungsi generik untuk toggle password
        function setupPasswordToggle(toggleId, inputId) {
            const toggleElement = document.getElementById(toggleId);
            const inputElement = document.getElementById(inputId);
            const icon = toggleElement.querySelector('i');

            toggleElement.addEventListener('click', function () {
                const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                inputElement.setAttribute('type', type);

                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        }

        // Terapkan fungsi ke kedua input password
        setupPasswordToggle('togglePassword', 'password');
        setupPasswordToggle('togglePasswordConfirmation', 'password_confirmation');
    </script>

</body>
</html>
