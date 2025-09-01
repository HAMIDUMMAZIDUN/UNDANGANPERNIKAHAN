<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Pastikan SweetAlert2 sudah ada di layout utama atau sisipkan di sini --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6 backdrop-blur-sm bg-white/80">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-2">Lupa Password Anda?</h2>
        <p class="text-center text-sm text-gray-500 mb-6">
            Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.
        </p>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <button type="submit"
                    class="w-full bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 transition duration-300 ease-in-out">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="text-green-600 hover:underline font-medium">
                &larr; Kembali ke Login
            </a>
        </p>
    </div>

    {{-- Script untuk menampilkan SweetAlert --}}
    <script>
        // Menampilkan pesan SUKSES
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('status') }}',
                confirmButtonColor: '#28a745' // Warna hijau
            });
        @endif

        // Menampilkan pesan GAGAL (termasuk error custom kita)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545' // Warna merah
            });
        @endif
    </script>

</body>
</html>