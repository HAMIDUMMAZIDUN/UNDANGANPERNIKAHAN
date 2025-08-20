<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
</head>
<body class="bg-green-100 min-h-screen flex items-center justify-center">

  <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
    
    {{-- Ikon User --}}
    <div class="flex justify-center mb-6">
      <div class="bg-green-500 p-4 rounded-full">
        <i class="fas fa-user text-white text-2xl"></i>
      </div>
    </div>

    <h2 class="text-xl font-semibold text-center text-gray-700 mb-6">Login</h2>

    @if(session('error'))
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email ID</label>
        <input type="email" name="email" id="email" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

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

</body>
</html>
