<header class="flex justify-between items-center">
    <div class="flex items-center gap-4">
        <button @click="isSidebarOpen = !isSidebarOpen" class="text-gray-600 focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
            </span>
            <input type="text" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full md:w-80" placeholder="Search">
        </div>
    </div>

    <div class="flex items-center gap-6">
        <span class="text-sm font-medium hidden sm:block">Open for Order</span>
        <label for="toggle" class="flex items-center cursor-pointer">
            <div class="relative">
                <input type="checkbox" id="toggle" class="sr-only" checked>
                <div class="block bg-gray-200 w-14 h-8 rounded-full"></div>
                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
            </div>
        </label>
        <style>
            input:checked ~ .dot { transform: translateX(100%); background-color: #48bb78; }
            input:checked ~ .block { background-color: #a7f3d0; }
        </style>
        <button class="relative">
            <i class="fas fa-bell text-xl text-gray-600"></i>
            <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
        </button>
    </div>
</header>