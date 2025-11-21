<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WeddingPass</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Script sederhana untuk handle dark mode
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white pb-24">
    
    <!-- TOP NAVIGATION -->
    <nav class="bg-white dark:bg-gray-800 p-4 shadow flex justify-between items-center fixed top-0 w-full z-40">
        <!-- Logo -->
        <h1 class="text-xl font-bold text-blue-600">DIGITAL GUESTBOOK BY BIRU ID</h1>
        
        <!-- Right Side Buttons -->
        <div class="flex items-center gap-2">
            <!-- 2. Profile Button -->
            <a href="{{ route('profile.edit') }}" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-600 transition" title="Edit Profile">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </a>

            <!-- 3. Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-lg bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 transition" title="Logout" onclick="return confirm('Apakah anda yakin ingin keluar?');">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>

        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="p-4 pt-20">
        {{ $slot }}
    </main>

    <!-- BOTTOM NAVIGATION -->
    <div class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
            
            <!-- 1. LIST TAMU -->
            <a href="{{ route('guests.index') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group {{ request()->routeIs('guests.index') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 {{ request()->routeIs('guests.index') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                <span class="text-xs group-hover:text-blue-600">List Tamu</span>
            </a>
            
            <!-- 2. SERVER 1 -->
            <a href="{{ route('server1') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group {{ request()->routeIs('server1') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 {{ request()->routeIs('server1') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM2 16a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="text-xs group-hover:text-blue-600">Server 1</span>
            </a>

            <!-- 3. SERVER 2 -->
            <a href="{{ route('server2') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group {{ request()->routeIs('server2') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 {{ request()->routeIs('server2') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm14 1.5a1 1 0 11-2 0 1 1 0 012 0zM2 13a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2zm14 1.5a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs group-hover:text-blue-600">Server 2</span>
            </a>

            <!-- 4. TAMU HADIR -->
            <a href="{{ route('attendance') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group {{ request()->routeIs('attendance') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 {{ request()->routeIs('attendance') ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs group-hover:text-blue-600 text-center leading-none">Tamu Hadir</span>
            </a>

        </div>
    </div>

</body>
</html>