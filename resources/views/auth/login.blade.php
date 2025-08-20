<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x
</head>
<body class="bg-gradient-to-br from-green-100 to-white min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
    
    {{-- Logo Daun --}}
    <div class="flex justify-center mb-6">
      <div class="bg-green-500 p-3 rounded-full">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">Log In</h2>

    @if(session('error'))
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" placeholder="johndoe@xyz.com" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" placeholder="••••••••" required
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
      </div>

      <div class="flex items-center justify-between">
        <label class="flex items-center text-sm text-gray-700">
          <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 border-gray-300 rounded">
          <span class="ml-2">Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-sm text-green-500 hover:underline">Forgot password?</a>
      </div>

      <button type="submit"
        class="w-full bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">
        Log In
      </button>
    </form>

    {{-- Social Login --}}
    <div class="mt-6 text-center">
      <p class="text-sm text-gray-600 mb-2">Or Sign In with</p>
      <div class="flex justify-center space-x-4">
        <a href="{{ route('social.login', 'facebook') }}" class="text-blue-600 hover:scale-110 transition">
          <i class="fab fa-facebook-f text-xl"></i>
        </a>
        <a href="{{ route('social.login', 'google') }}" class="text-red-500 hover:scale-110 transition">
          <i class="fab fa-google text-xl"></i>
        </a>
        <a href="{{ route('social.login', 'apple') }}" class="text-gray-800 hover:scale-110 transition">
          <i class="fab fa-apple text-xl"></i>
        </a>
      </div>
    </div>

    <p class="mt-6 text-center text-sm text-gray-600">
      Don't have an account?
      <a href="{{ route('register') }}" class="text-green-500 hover:underline">Sign Up</a>
    </p>
  </div>
</body>
</html>
