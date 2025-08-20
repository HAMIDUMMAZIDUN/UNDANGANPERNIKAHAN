<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ganti Password</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">Ganti Password</h2>

    @if (session('status'))
      <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
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

  {{-- Token dari URL --}}
  <input type="hidden" name="token" value="{{ $token }}">

  {{-- Email --}}
  <div>
    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" id="email" required
      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
  </div>

  {{-- Password Baru --}}
  <div>
    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
    <input type="password" name="password" id="password" required
      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
  </div>

  {{-- Konfirmasi Password --}}
  <div>
    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required
      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
  </div>

  <button type="submit"
    class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
    Simpan Password Baru
  </button>
</form>

  </div>
</body>
</html>
