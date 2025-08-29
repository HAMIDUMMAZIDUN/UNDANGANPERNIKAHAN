<header class="bg-white shadow-sm fixed top-0 left-0 right-0 h-16 z-20 md:left-64">
    <div class="flex items-center justify-between h-full px-4">
        <!-- Tombol Hamburger (Hanya Mobile) -->
        <button 
            class="text-slate-700 md-hidden" 
            @click.stop="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        {{-- KODE UNTUK MENDEFINISIKAN $pageTitle --}}
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
                // Mencocokkan rute saat ini dengan daftar menu
                if (request()->is($route) || request()->is($route . '/*')) {
                    $pageTitle = $data['label'];
                    break;
                }
            }
        @endphp

        {{-- Menampilkan judul halaman --}}
        <span class="text-black-200 font-bold text-lg">
            {{ $pageTitle }}
        </span>

        <!-- Foto Profil -->
        {{-- PERBAIKAN: x-data dihapus dari sini --}}
        <div class="relative">
            <img src="{{ file_exists(public_path('poto-profile/user-' . Auth::id() . '.jpg')) ? asset('poto-profile/user-' . Auth::id() . '.jpg') : asset('images/default-profile.png') }}" 
                 alt="Foto Profil" 
                 @click="profileMenuOpen = !profileMenuOpen" 
                 class="h-8 w-8 rounded-full cursor-pointer border-2 border-amber-500 object-cover">
            
            <div x-show="profileMenuOpen" @click.outside="profileMenuOpen = false" x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                
                <button @click="profilePopupOpen = true; profileMenuOpen = false" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profil Saya
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
