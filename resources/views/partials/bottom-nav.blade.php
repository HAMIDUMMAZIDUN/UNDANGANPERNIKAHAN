<div class="fixed inset-x-0 bottom-4 z-50 flex justify-center md:hidden">
    <nav class="flex items-center space-x-2 rounded-full bg-slate-100 p-2 shadow-lg">

        @php
            // Array menu diubah untuk hanya berisi 3 item yang diminta
            $navItems = [
                'souvenir'   => ['label' => 'Souvenir', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>'],
                'cari-tamu'  => ['label' => 'Cari Tamu', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>'],
                
                // -- PERUBAHAN DI SINI --
                'check-in'   => ['label' => 'Scan QR', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75V4.5Zm0 6.75a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm0 6.75a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm6.75-13.5a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75V4.5Zm0 6.75a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm0 6.75a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm6.75-13.5a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75V4.5Zm0 6.75a.75.75 0 0 1 .75-.75h.75c.414 0 .75.336.75.75v.75c0 .414-.336.75-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Z" /></svg>']
            ]
        @endphp

        @foreach ($navItems as $route => $item)
            <a href="{{ url($route) }}" 
               class="flex items-center justify-center rounded-full h-12 transition-all duration-300
                    {{ Request::is($route.'*')
                        ? 'bg-blue-600 text-white px-5 space-x-2'
                        : 'text-slate-500 w-12 hover:bg-slate-200'
                    }}">
                
                {!! $item['icon'] !!}
                
                <span class="{{ Request::is($route.'*') ? 'block text-sm font-semibold' : 'hidden' }}">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

    </nav>
</div>