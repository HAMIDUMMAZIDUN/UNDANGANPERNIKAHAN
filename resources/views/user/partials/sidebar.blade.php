<aside 
    id="sidebar" 
    class="bg-slate-800 text-white w-64 fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40"
    :class="{ 'translate-x-0': sidebarOpen }"
    x-cloak>
    
    <div class="px-4 pt-8 pb-4">
        <a href="{{ route('dashboard.index') }}" class="text-2xl font-bold text-white tracking-wider">
        Menu
        </a>
    </div>

    <nav class="flex-grow px-2 space-y-1 overflow-y-auto">
        @php
            $firstEvent = Auth::user()->events()->first();
            
            $menuItems = [
                'dashboard' => ['label' => 'Dashboard', 'icon' => 'fa-home'],
                'tamu' => ['label' => 'Tamu', 'icon' => 'fa-users'],
                'kehadiran' => ['label' => 'Kehadiran', 'icon' => 'fa-clipboard-check'],
                'rsvp' => ['label' => 'RSVP', 'icon' => 'fa-envelope-open-text'],
                'sapa' => ['label' => 'Sapa', 'icon' => 'fa-smile-beam'],
                'cari-tamu' => ['label' => 'Cari Tamu', 'icon' => 'fa-search'],
                'check-in' => ['label' => 'Check-in', 'icon' => 'fa-qrcode'],
                'manual' => ['label' => 'Manual', 'icon' => 'fa-edit'],
                'souvenir' => ['label' => 'Souvenir', 'icon' => 'fa-gift'],
                'gift' => ['label' => 'Gift', 'icon' => 'fa-box-open'],
                'setting' => ['label' => 'Setting', 'icon' => 'fa-cog'],
            ];
        @endphp

        @foreach ($menuItems as $route => $data)
            @php
                $isActive = false;
                $url = '#';
                if ($route === 'tamu') {
                    if ($firstEvent) {
                        $url = route('events.tamu.index', ['event' => $firstEvent->uuid]);
                        $isActive = request()->routeIs('events.tamu.*');
                    } else {
                        continue; // Lewati item 'Tamu' jika user belum punya event
                    }
                } else {
                    $url = url($route);
                    $isActive = request()->is($route.'*');
                }
            @endphp

            <a href="{{ $url }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ $isActive ? 'bg-amber-500 text-slate-900 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <i class="fas {{ $data['icon'] }} w-6 text-center"></i>
                <span class="ml-3">{{ $data['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="p-4 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-slate-300 hover:bg-red-600 hover:text-white transition-colors duration-200">
                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                <span class="ml-3">Logout</span>
            </button>
        </form>
    </div>
</aside>
