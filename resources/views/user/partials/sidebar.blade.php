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
                'dashboard' => ['label' => 'Dashboard', 'icon' => 'fa-home', 'route' => 'dashboard.index'],
                'tamu' => ['label' => 'Tamu', 'icon' => 'fa-users', 'route' => 'events.tamu.index'],
                'kehadiran' => ['label' => 'Kehadiran', 'icon' => 'fa-clipboard-check', 'route' => 'kehadiran.index'],
                'rsvp' => ['label' => 'RSVP', 'icon' => 'fa-envelope-open-text', 'route' => 'rsvp.index'],
                'sapa' => ['label' => 'Sapa', 'icon' => 'fa-smile-beam', 'route' => 'sapa.index'],
                'cari-tamu' => ['label' => 'Cari Tamu', 'icon' => 'fa-search', 'route' => 'cari-tamu.index'],
                'check-in' => ['label' => 'Check-in', 'icon' => 'fa-qrcode', 'route' => 'check-in.index'],
                'manual' => ['label' => 'Manual', 'icon' => 'fa-edit', 'route' => 'manual.index'],
                'souvenir' => ['label' => 'Souvenir', 'icon' => 'fa-gift', 'route' => 'souvenir.index'],
                'gift' => ['label' => 'Gift', 'icon' => 'fa-box-open', 'route' => 'gift.index'],
                'setting' => ['label' => 'Setting', 'icon' => 'fa-cog', 'route' => 'user.setting.index'],
            ];
        @endphp

        @foreach ($menuItems as $key => $data)
            @php
                $url = '#';
                $params = [];
                $isActive = false;

                // Handle routes that need an event parameter
                if (in_array($key, ['tamu', 'sapa'])) {
                    if ($firstEvent) {
                        $params = ['event' => $firstEvent->uuid];
                        $url = route($data['route'], $params);
                        $isActive = request()->routeIs('events.tamu.*') || request()->is('sapa*');
                    } else {
                        continue; // Skip if no event exists
                    }
                } 
                // Handle regular routes
                else {
                    $url = route($data['route']);
                    // Check if the current route name matches the menu item's route name pattern
                    // Example: 'user.setting.index' will match 'user.setting.*'
                    $isActive = request()->routeIs($key . '*');

                    // A more specific check for the main setting page itself
                     if ($key === 'setting') {
                         $isActive = request()->routeIs('user.setting.*');
                     }
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

