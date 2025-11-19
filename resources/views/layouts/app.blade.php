<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark"> <head>
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
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white pb-20">
    
    <nav class="bg-white dark:bg-gray-800 p-4 shadow flex justify-between items-center">
        <h1 class="text-xl font-bold">WeddingPass</h1>
        <button id="theme-toggle" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700">
            <span id="theme-icon">🌙/☀️</span>
        </button>
    </nav>

    <main class="p-4">
        {{ $slot }}
    </main>

    <div class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
            <a href="{{ route('dashboard') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M..."/></svg>
                <span class="text-sm text-gray-500 dark:text-gray-400">Home</span>
            </a>
            
            <a href="{{ route('guests.index') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M..."/></svg>
                <span class="text-sm text-gray-500 dark:text-gray-400">Tamu</span>
            </a>

            <a href="{{ route('scan.page') }}" class="inline-flex flex-col items-center justify-center px-5 bg-blue-600 text-white hover:bg-blue-700">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </a>

            <a href="#" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                 <span class="text-sm text-gray-500 dark:text-gray-400">Profil</span>
            </a>
        </div>
    </div>

</body>
</html>