<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #FFF7ED; 
        }
        /* Custom Scrollbar for better aesthetics */
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body x-data="{ isSidebarOpen: true }" class="flex min-h-screen">
    
    @include('admin.partials.sidebar')

    <div class="flex flex-col flex-1 w-full overflow-y-auto">
        
        <div 
            x-show="isSidebarOpen" 
            @click="isSidebarOpen = false" 
            class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden">
        </div>

        <div class="px-8 pt-8">
            @include('admin.partials.navbar')
        </div>

        <main class="flex-1 px-8 py-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm w-full h-full">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>