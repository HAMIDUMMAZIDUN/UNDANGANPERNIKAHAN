<div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-pink-600 text-white p-4 pt-16 space-y-4 transform -translate-x-full transition-transform duration-300 z-30">
  <h2 class="text-xl font-bold mb-4">Menu</h2>

  @php
    $menuItems = [
      'dashboard' => 'Dashboard',
      'tamu' => 'Tamu',
      'kehadiran' => 'Kehadiran',
      'rsvp' => 'RSVP',
      'sapa' => 'Sapa',
      'cari-tamu' => 'Cari Tamu',
      'check-in' => 'Check-In',
      'manual' => 'Manual',
      'souvenir' => 'Souvenir',
      'gift' => 'Gift',
      'setting' => 'Setting',
    ];
  @endphp

  @foreach ($menuItems as $route => $label)
    <a href="/{{ $route }}" class="block px-3 py-2 rounded {{ Request::is($route) ? 'bg-pink-700' : 'hover:bg-pink-700' }}">
      {{ $label }}
    </a>
  @endforeach

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="w-full text-left hover:bg-pink-700 px-3 py-2 rounded">Logout</button>
  </form>
</div>
  <main class="pt-20 px-6">
    @yield('content')
  </main>

  {{-- Popup Form --}}
  @include('partials.profile-popup')