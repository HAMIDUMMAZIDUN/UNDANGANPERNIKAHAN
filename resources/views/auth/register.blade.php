<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-100 to-white min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">Daftar Akun</h2>

    @if ($errors->any())
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
      @csrf

      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="name" id="name" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <button type="submit"
        class="w-full bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">
        Daftar
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-green-500 hover:underline">Login di sini</a>
    </p>
  </div>
</body>
</html>
