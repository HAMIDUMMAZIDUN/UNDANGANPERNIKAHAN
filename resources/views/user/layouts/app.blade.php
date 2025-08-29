<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>@yield('page_title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js untuk Interaktivitas UI -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- SweetAlert2 & Font Awesome (jika perlu) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" ... />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">

    <!-- Wrapper utama dengan state Alpine.js -->
    <div x-data="{ sidebarOpen: false, profileMenuOpen: false, profilePopupOpen: false }">
        @include('user.partials.sidebar')
        <div class="relative min-h-screen md:ml-64 pb-24">
            @include('user.partials.navbar')
            <main class="pt-20 px-4 md:px-8">
                @yield('content')
            </main>
        </div>
        @include('user.partials.bottom-nav')
        @include('user.partials.profile-popup')
        <!-- Overlay untuk latar belakang saat sidebar mobile terbuka -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/60 z-30 md:hidden" x-cloak></div>

    </div>

    @stack('scripts')
</body>
</html>
