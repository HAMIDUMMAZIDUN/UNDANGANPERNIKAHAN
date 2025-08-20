@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
<body class="bg-gray-50 min-h-screen">
  {{-- Konten Utama --}}
  <main class="pt-20 px-6 flex flex-col items-center justify-center min-h-screen text-center">
    <h1 class="text-2xl md:text-3xl font-bold text-pink-600 mb-2">Selamat datang, {{ Auth::user()->name }}!</h1>
    <p class="text-gray-700 text-sm md:text-base">Silakan pilih fitur dari menu untuk mengelola undangan pernikahan.</p>
  </main>

  {{-- Popup Form --}}
  <div id="profilePopup" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
      <button onclick="toggleProfile()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">×</button>
      <h2 class="text-xl font-bold text-pink-600 mb-4 text-center">Update User</h2>
      
      @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

     <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
          <input type="file" name="photo" accept="image/*" class="mt-1 block w-full px-4 py-2 border rounded-md">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Nama</label>
          <input type="text" name="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full px-4 py-2 border rounded-md">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full px-4 py-2 border rounded-md">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Password Lama</label>
          <input type="password" name="current_password" class="mt-1 block w-full px-4 py-2 border rounded-md">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Password Baru</label>
          <input type="password" name="new_password" placeholder="Kosongkan jika tidak ubah password" class="mt-1 block w-full px-4 py-2 border rounded-md">
        </div>

        <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700 transition">Update</button>
      </form>
    </div>
  </div>
    <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('translate-x-0');
      sidebar.classList.toggle('-translate-x-full');
    }

    function toggleProfile() {
      const popup = document.getElementById('profilePopup');
      popup.classList.toggle('hidden');
    }
  </script>
@endsection
