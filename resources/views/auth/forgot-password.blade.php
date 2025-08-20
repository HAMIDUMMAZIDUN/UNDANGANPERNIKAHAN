<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">Reset Password</h2>

    @if (session('status'))
      <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <button type="submit"
        class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
        Kirim Link Reset
      </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
      <a href="{{ route('login') }}" class="text-green-500 hover:underline">Kembali ke Login</a>
    </p>
  </div>
</body>
</html>
