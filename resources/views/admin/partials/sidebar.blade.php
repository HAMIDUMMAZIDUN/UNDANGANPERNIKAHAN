<aside
    class="bg-white p-6 flex flex-col justify-between fixed inset-y-0 left-0 transform lg:relative lg:translate-x-0 transition-all duration-300 ease-in-out z-30 custom-scrollbar"
    :class="isSidebarOpen ? 'w-64' : 'w-64 lg:w-20 -translate-x-full lg:translate-x-0'">

    <div>
        <div class="flex items-center gap-3 mb-10">
            <div class="bg-gray-800 text-white p-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800 transition-opacity duration-200" :class="isSidebarOpen ? 'opacity-100' : 'lg:opacity-0 lg:hidden'">ADMIN</h1>
        </div>
        
        <nav>
            <ul>
                <li class="mb-4">
                    <a href="{{ route('dashboard.admin.index') }}" 
                        class="flex items-center gap-3 text-sm p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard.admin.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}"
                        :class="isSidebarOpen ? '' : 'lg:justify-center'">
                        <i class="fas fa-home w-5 text-center text-lg"></i>
                        <span class="transition-opacity duration-200" :class="isSidebarOpen ? 'opacity-100' : 'lg:opacity-0 lg:hidden'">Dashboard</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.client.index') }}" 
                        class="flex items-center gap-3 text-sm p-3 rounded-lg transition-colors {{ request()->routeIs('admin.client.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}"
                        :class="isSidebarOpen ? '' : 'lg:justify-center'">
                        <i class="fas fa-user-friends w-5 text-center text-lg"></i>
                        <span class="transition-opacity duration-200" :class="isSidebarOpen ? 'opacity-100' : 'lg:opacity-0 lg:hidden'">Client</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('order.history.index') }}"
                        class="flex items-center gap-3 text-sm p-3 rounded-lg transition-colors {{ request()->routeIs('order.history.index') ? 'font-semibold text-gray-700 bg-orange-100' : 'font-medium text-gray-500 hover:bg-gray-100' }}"
                        :class="isSidebarOpen ? '' : 'lg:justify-center'">
                        <i class="fas fa-history w-5 text-center text-lg"></i>
                        <span class="transition-opacity duration-200" :class="isSidebarOpen ? 'opacity-100' : 'lg:opacity-0 lg:hidden'">Order History</span>
                    </a>
            </ul>
        </nav>
    </div>

    <div>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center gap-3 text-sm font-medium text-gray-500 hover:bg-gray-100 p-3 rounded-lg transition-colors"
            :class="isSidebarOpen ? '' : 'lg:justify-center'">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
            <span class="transition-opacity duration-200" :class="isSidebarOpen ? 'opacity-100' : 'lg:opacity-0 lg:hidden'">Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>