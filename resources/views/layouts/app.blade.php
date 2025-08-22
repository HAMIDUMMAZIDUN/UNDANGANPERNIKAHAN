<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('page_title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    {{-- Font Awesome untuk ikon, tambahkan jika belum ada --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden"></div>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Konten Utama --}}
    <main class="pt-20 transition-all duration-300">
        <div class="px-4 md:px-8 py-6">
            <div class="max-w-screen-xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Popup Form --}}
    @include('partials.profile-popup')

    {{-- Skrip Global untuk semua halaman (seperti Sidebar & Profile) --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            // Menggeser sidebar masuk/keluar layar
            sidebar.classList.toggle('-translate-x-full');

            // Menampilkan/menyembunyikan backdrop
            backdrop.classList.toggle('hidden');
        }

        function toggleProfile() {
            const popup = document.getElementById('profilePopup');
            popup.classList.toggle('hidden');
        }
    </script>

    {{-- PENAMBAHAN PENTING: Tempat untuk menampung script dari halaman individual --}}
    @stack('scripts')

</body>
</html>