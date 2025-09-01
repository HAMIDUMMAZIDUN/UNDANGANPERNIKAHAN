<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Ikon Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Script Google reCAPTCHA --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-6">Login Aplikasi</h2>
        
        {{-- Form dengan ID baru --}}
        <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <span id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <i class="fa-solid fa-eye text-gray-400"></i>
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-700">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 border-gray-300 rounded">
                    <span class="ml-2">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-green-500 hover:underline">Forgot Password?</a>
            </div>

            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

            <button type="submit"
                class="w-full bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">
                LOGIN
            </button>
        </form>
    </div>

    <script>
        // Script untuk SweetAlert error dari backend
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33', 
            });
        @endif

        // Script untuk toggle password
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });

        // ===============================================================
        // == SCRIPT BARU UNTUK VALIDASI RECAPTCHA SEBELUM SUBMIT ==
        // ===============================================================
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            const recaptchaResponse = grecaptcha.getResponse();
            
            // Cek jika reCAPTCHA kosong (belum dicentang)
            if (recaptchaResponse.length === 0) {
                // Hentikan pengiriman form
                event.preventDefault(); 
                
                // Tampilkan SweetAlert peringatan
                Swal.fire({
                    icon: 'warning',
                    title: 'Verifikasi Diperlukan',
                    text: 'Harap centang kotak "Saya bukan robot" terlebih dahulu.',
                });
            }
        });
    </script>
</body>
</html>