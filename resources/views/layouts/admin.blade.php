<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Font Awesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFF7ED; /* Warna latar belakang seperti di gambar */
        }
        /* Custom scrollbar untuk webkit browsers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 flex flex-col justify-between">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10">
                <div class="bg-gray-800 text-white p-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">ADMIN</h1>
            </div>

            <!-- Menu -->
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('dashboard.admin.index') }}" 
                           class="flex items-center gap-3 text-sm p-3 rounded-lg
                                  {{ request()->routeIs('dashboard.admin.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}">
                            <i class="fas fa-home w-5 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('client.index') }}" 
                           class="flex items-center gap-3 text-sm p-3 rounded-lg
                                  {{ request()->routeIs('client.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}">
                           <i class="fas fa-user-friends w-5 text-center"></i>
                            <span>Client</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('order.history.index') }}" 
                           class="flex items-center gap-3 text-sm p-3 rounded-lg
                                  {{ request()->routeIs('order.history.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}">
                            <i class="fas fa-history w-5 text-center"></i>
                            <span>Order History</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        {{-- PERBAIKAN: Mengarahkan ke route request.client.index --}}
                        <a href="{{ route('request.client.index') }}" 
                           class="flex items-center gap-3 text-sm p-3 rounded-lg
                                  {{ request()->routeIs('request.client.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}">
                            <i class="fas fa-file-alt w-5 text-center"></i>
                            <span>Request Client</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center gap-3 text-sm font-medium text-gray-500 hover:bg-gray-100 p-3 rounded-lg">
                           <i class="fas fa-cog w-5 text-center"></i>
                            <span>Setting</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Logout -->
        <div>
             <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 text-sm font-medium text-gray-500 hover:bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <!-- Top Bar -->
        <header class="flex justify-between items-center mb-8">
             <!-- Search Bar -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input type="text" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-80" placeholder="Search">
            </div>
            <div class="flex items-center gap-6">
                <span class="text-sm font-medium">Open for Order</span>
                <label for="toggle" class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="toggle" class="sr-only" checked>
                        <div class="block bg-gray-200 w-14 h-8 rounded-full"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                    </div>
                </label>
                <style>
                    input:checked ~ .dot {
                        transform: translateX(100%);
                        background-color: #48bb78;
                    }
                     input:checked ~ .block {
                        background-color: #a7f3d0;
                    }
                </style>
                <button class="relative">
                    <i class="fas fa-bell text-xl text-gray-600"></i>
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
