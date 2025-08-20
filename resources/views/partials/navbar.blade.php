<nav class="bg-white shadow-md px-4 py-3 flex items-center justify-between fixed top-0 left-0 right-0 z-40">
  <button onclick="toggleSidebar()" class="text-pink-600 text-2xl focus:outline-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>

  <span class="text-pink-600 font-semibold text-lg">
    {{ $pageTitle ?? 'Dashboard' }}
  </span>

  <div class="relative">
    <img src="{{ asset('poto-profile/user-' . Auth::id() . '.jpg') }}" alt="Foto Profil" onclick="toggleProfile()" class="h-8 w-8 rounded-full cursor-pointer border-2 border-pink-500 object-cover">
  </div>
</nav>
