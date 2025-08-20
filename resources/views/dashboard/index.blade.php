<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="text-center">
    <h1 class="text-3xl font-bold text-pink-600 mb-4">Selamat datang, {{ Auth::user()->name }}!</h1>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">
        Logout
      </button>
    </form>
  </div>
</body>
</html>
