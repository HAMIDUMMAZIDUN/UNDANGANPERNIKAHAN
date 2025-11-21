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
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white pb-24 antialiased font-sans">
    
    <!-- TOP NAVIGATION (Glass Effect) -->
    <nav class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 p-4 fixed top-0 w-full z-40 transition-colors duration-300">
        <div class="max-w-screen-xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h1 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    DIGITAL <span class="text-blue-600">GUESTBOOK</span>
                </h1>
            </div>
            
            <!-- Right Side Buttons -->
            <div class="flex items-center gap-1 sm:gap-2">
                <!-- Profile Button -->
                <a href="{{ route('profile.edit') }}" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" title="Edit Profile">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-full text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 transition-colors" title="Logout" onclick="return confirm('Apakah anda yakin ingin keluar?');">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="p-4 pt-24 max-w-screen-xl mx-auto min-h-screen">
        {{ $slot }}
    </main>

    <!-- BOTTOM NAVIGATION -->
    <div class="fixed bottom-0 left-0 z-50 w-full h-20 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="grid h-full max-w-lg grid-cols-3 mx-auto font-medium">
            
            <!-- 1. LIST TAMU -->
            <a href="{{ route('guests.index') }}" class="flex flex-col items-center justify-center h-full w-full px-2 hover:bg-gray-50 dark:hover:bg-gray-700 group transition-colors {{ request()->routeIs('guests.index') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="text-xs font-semibold text-center">List Tamu</span>
            </a>
            
            <!-- 2. SCAN BACAAN (Ex-Server 1) -->
            <a href="{{ route('server1') }}" class="flex flex-col items-center justify-center h-full w-full px-2 hover:bg-gray-50 dark:hover:bg-gray-700 group transition-colors {{ request()->routeIs('server1') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
                <!-- Icon QR Code / Scan -->
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 16v1m0 0l3-3m-3 3l-3-3M12 16V4m-4 12H6m2-16H4a2 2 0 00-2 2v12a2 2 0 002 2h4m8-16h4a2 2 0 012 2v12a2 2 0 01-2 2h-4"></path>
                </svg>
                <span class="text-xs font-semibold text-center">Scan Bacaan</span>
            </a>

            <!-- 4. TAMU HADIR -->
            <a href="{{ route('attendance') }}" class="flex flex-col items-center justify-center h-full w-full px-2 hover:bg-gray-50 dark:hover:bg-gray-700 group transition-colors {{ request()->routeIs('attendance') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="text-xs font-semibold text-center leading-none">Tamu Hadir</span>
            </a>

        </div>
    </div>

</body>
</html>