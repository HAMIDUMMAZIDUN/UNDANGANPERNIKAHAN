<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pemesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Ikon dari Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 p-4" style="background-color: #fdfaf6;">
    
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-6 md:p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Formulir Pemesanan</h2>
        <p class="text-center text-gray-500 mb-6">Selangkah lagi untuk memiliki undangan impian Anda.</p>

        <div class="mb-6 border rounded-lg p-4 flex items-center gap-4 bg-gray-50">
            <img src="{{ $theme['preview_img'] }}" alt="{{ $theme['nama'] }}" class="w-20 h-24 object-cover rounded-md shadow-sm">
            <div>
                <p class="text-sm text-gray-500">Tema Pilihan Anda:</p>
                <h3 class="text-lg font-semibold text-gray-900">{{ $theme['nama'] }}</h3>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                <p class="font-bold">Oops! Ada kesalahan.</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="orderForm" method="POST" action="{{ route('order.process') }}" class="space-y-5">
            @csrf
            
            <input type="hidden" name="template_id" value="{{ $theme['id'] }}">
            <input type="hidden" name="template_name" value="{{ $theme['nama'] }}">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Handphone (WhatsApp)</label>
                <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" name="password" id="password" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 shadow-sm">
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                 <div class="relative mt-1">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 shadow-sm">
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                         <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                BUAT AKUN & PESAN
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:underline">Masuk di sini</a>
        </p>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = event.currentTarget.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>