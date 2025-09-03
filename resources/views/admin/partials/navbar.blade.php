<header class="flex justify-between items-center">
    <div class="flex items-center gap-4">
        <button @click="isSidebarOpen = !isSidebarOpen" class="text-gray-600 focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        
        <form action="{{ route('dashboard.admin.index') }}" method="GET" class="relative">
        </form>
    </div>

    <div class="flex items-center gap-6">
        <span class="text-sm font-medium hidden sm:block">Open for Order</span>
        <label for="toggle" class="flex items-center cursor-pointer">
            <div class="relative">
                <input
                    type="checkbox"
                    id="toggle"
                    class="sr-only"
                    {{ \Illuminate\Support\Facades\Cache::get('open_for_order', true) ? 'checked' : '' }}
                    @change="
                        fetch('{{ route('admin.settings.toggleOrderStatus') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: $event.target.checked })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert('Failed to update status.');
                            }
                        })
                        .catch(() => {
                            alert('An error occurred. Please try again.');
                        });
                    "
                >
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

