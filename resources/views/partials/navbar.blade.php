<nav class="bg-slate-800 border-b border-slate-700 px-4 py-3 flex items-center justify-between fixed top-0 left-0 right-0 z-40">
  
  <button onclick="toggleSidebar()" class="text-slate-300 hover:text-white text-2xl focus:outline-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>

  @php
      $pageTitle = 'Dashboard'; // Judul default
      $menuItems = [
          'dashboard' => ['label' => 'Dashboard'], 'tamu' => ['label' => 'Tamu'],
          'kehadiran' => ['label' => 'Kehadiran'], 'rsvp' => ['label' => 'RSVP'],
          'sapa' => ['label' => 'Sapa'], 'cari-tamu' => ['label' => 'Cari Tamu'],
          'check-in' => ['label' => 'Check-in'], 'manual' => ['label' => 'Manual'],
          'souvenir' => ['label' => 'Souvenir'], 'gift' => ['label' => 'Gift'],
          'setting' => ['label' => 'Setting'],
      ];

      foreach ($menuItems as $route => $data) {
          if (Request::is($route) || Request::is($route . '/*')) {
              $pageTitle = $data['label'];
              break;
          }
      }
  @endphp

  <span class="text-slate-200 font-bold text-lg">
    {{ $pageTitle }}
  </span>

  <div class="relative">
    <img src="{{ file_exists(public_path('poto-profile/user-' . Auth::id() . '.jpg')) ? asset('poto-profile/user-' . Auth::id() . '.jpg') : asset('images/default-profile.png') }}" 
         alt="Foto Profil" 
         onclick="toggleProfile()" 
         class="h-8 w-8 rounded-full cursor-pointer border-2 border-amber-500 object-cover">
  </div>
</nav>