<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-slate-800 text-white p-4 flex flex-col transition-transform duration-300 ease-in-out transform -translate-x-full z-50">
    
    <div class="px-2 pt-12 pb-6">
        <h2 class="text-xl font-bold text-white tracking-wider">Menu</h2>
    </div>

    <nav class="flex-grow space-y-2">
        @php
            // Definisikan menu item beserta ikon SVG-nya yang telah disesuaikan
            $menuItems = [
                'dashboard' => ['label' => 'dashboard', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>'],
                'tamu' => ['label' => 'tamu', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.318.232-.656.328-1.014a6.375 6.375 0 011.028 6.373zM5.25 14.25A2.25 2.25 0 013 12V7.5a2.25 2.25 0 012.25-2.25h1.5a2.25 2.25 0 012.25 2.25v4.5a2.25 2.25 0 01-2.25 2.25h-1.5z" /></svg>'],
                'kehadiran' => ['label' => 'kehadiran', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'],
                'rsvp' => ['label' => 'rsvp', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>'],
                'sapa' => ['label' => 'sapa', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.76 9.76 0 01-2.53-.388m-5.185-3.32a.75.75 0 01.63 1.11A9.753 9.753 0 0012 21a9.753 9.753 0 008.307-4.492.75.75 0 011.11-.63zM3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>'],
                'cari-tamu' => ['label' => 'cari-tamu', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>'],
                'check-in' => ['label' => 'check-in', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" /></svg>'],
                'manual' => ['label' => 'Manual', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>'],
                'souvenir' => ['label' => 'Souvenir', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>'],
                'gift' => ['label' => 'Gift', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a2.25 2.25 0 01-2.25 2.25H5.25a2.25 2.25 0 01-2.25-2.25v-8.25M12 4.875A2.625 2.625 0 1014.625 2.25 2.625 2.625 0 0012 4.875zM12 11.25a2.625 2.625 0 100-5.25 2.625 2.625 0 000 5.25z" /></svg>'],
                'setting' => ['label' => 'Setting', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-1.007 1.11-1.226a2.25 2.25 0 012.58 0c.55.219 1.02.684 1.11 1.226l.094.542c.065.383.311.717.668.977l.473.348a2.25 2.25 0 012.75 0l.473-.348c.358-.26.602-.594.668-.977l.094-.542c.09-.542.56-1.007 1.11-1.226a2.25 2.25 0 012.58 0c.55.219 1.02.684 1.11 1.226l.094.542c.065.383.311.717.668.977l.473.348a2.25 2.25 0 012.75 0l.473-.348c.358-.26.602-.594.668-.977l.094-.542c.09-.542.56-1.007 1.11-1.226a2.25 2.25 0 012.58 0c.55.219 1.02.684 1.11 1.226l.094.542c.065.383.311.717.668.977l.473.348a2.25 2.25 0 010 2.75l-.473.348c-.358.26-.602-.594-.668.977l-.094.542c-.09.542-.56 1.007-1.11 1.226a2.25 2.25 0 01-2.58 0c-.55-.219-1.02-.684-1.11-1.226l-.094-.542c-.065-.383-.311-.717-.668-.977l-.473-.348a2.25 2.25 0 01-2.75 0l-.473.348c-.358.26-.602-.594-.668.977l-.094.542c-.09.542-.56 1.007-1.11 1.226a2.25 2.25 0 01-2.58 0c-.55-.219-1.02-.684-1.11-1.226l-.094-.542c-.065-.383-.311-.717-.668-.977l-.473-.348a2.25 2.25 0 010-2.75l.473-.348c.358-.26.602-.594.668-.977l.094-.542zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" /></svg>'],
            ];
        @endphp

       @foreach ($menuItems as $route => $data)
            <a href="{{ url($route) }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors duration-200
                      {{ Request::is($route.'*') 
                         ? 'bg-amber-500 text-slate-900 font-semibold shadow-inner' 
                         : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                
                {!! $data['icon'] !!}
                <span>{{ $data['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto pb-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-red-600 hover:text-white transition-colors duration-200">
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>