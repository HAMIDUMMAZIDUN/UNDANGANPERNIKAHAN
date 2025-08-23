<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('page_title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- TAMBAHKAN pb-20 agar konten tidak tertutup bottom nav di mobile --}}
<body class="bg-gray-50 min-h-screen pb-20 md:pb-0">

    {{-- HAPUS BAGIAN INI KARENA SUDAH TIDAK DIPERLUKAN --}}
    {{-- <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden"></div> --}}

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Sidebar asli untuk Desktop --}}
    @include('partials.sidebar')

    {{-- Bottom Navigation untuk Mobile --}}
    @include('partials.bottom-nav')

    {{-- Konten Utama --}}
    {{-- UBAH padding kiri untuk desktop menjadi md:pl-64 --}}
    <main class="pt-20 transition-all duration-300 md:pl-64">
        <div class="px-4 md:px-8 py-6">
            <div class="max-w-screen-xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Popup Form --}}
    @include('partials.profile-popup')

    {{-- Skrip Global --}}
    <script>
        // FUNGSI INI MASIH DIPERLUKAN UNTUK MEMBUKA/TUTUP SIDEBAR DI DESKTOP
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const mainContent = document.querySelector('main');
            
            sidebar.classList.toggle('-translate-x-full');
            
            // Logika untuk backdrop jika masih ingin digunakan di mobile
            // backdrop.classList.toggle('hidden');

            // Sesuaikan margin konten utama saat sidebar terbuka di desktop
            if (!sidebar.classList.contains('-translate-x-full')) {
                mainContent.classList.add('md:ml-64');
            } else {
                mainContent.classList.remove('md:ml-64');
            }
        }

        function toggleProfile() {
            const popup = document.getElementById('profilePopup');
            popup.classList.toggle('hidden');
        }

        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        @endif
    </script>

    @stack('scripts')

</body>
</html>