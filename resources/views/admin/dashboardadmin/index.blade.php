@extends('admin.layouts.app')

@section('content')
    <!-- Header Dashboard -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-500 mt-1">
            Welcome back, <span class="font-semibold">Admin</span> 
            <span class="inline-block ml-2">
                <img src="https://flagcdn.com/w20/id.png" alt="Indonesia Flag">
            </span>
            <span class="text-sm">Indonesia</span>
        </p>
    </div>

    <!-- Grid untuk Statistik dan Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Statistik -->
        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Server Uptime -->
                <div class="bg-{{ $serverStatusColor }}-50 p-6 rounded-2xl flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-{{ $serverStatusColor }}-600 flex items-center gap-2">
                            <span class="h-3 w-3 bg-{{ $serverStatusColor }}-500 rounded-full"></span>
                            {{ $serverStatus }}
                        </p>
                        <p class="text-sm text-{{ $serverStatusColor }}-800 font-medium mt-1">SERVER STATUS</p>
                    </div>
                    <i class="fas fa-server text-4xl text-{{ $serverStatusColor }}-300"></i>
                </div>
                <!-- Card Total Clients (Diubah dari Users) -->
                <div class="bg-teal-500 text-white p-6 rounded-2xl flex justify-between items-center">
                    <div>
                        <p class="text-4xl font-bold">{{ $totalUsers }}</p>
                        {{-- Perubahan di baris berikut --}}
                        <p class="text-sm font-medium mt-1">TOTAL CLIENTS</p> 
                    </div>
                    <i class="fas fa-user-friends text-4xl text-teal-300"></i>
                </div>
                <!-- Card Booking -->
                <div class="bg-teal-500 text-white p-6 rounded-2xl flex justify-between items-center">
                    <div>
                        <p class="text-4xl font-bold">{{ $booking }}</p>
                        <p class="text-sm font-medium mt-1">BOOKING</p>
                    </div>
                    <i class="fas fa-book text-4xl text-teal-300"></i>
                </div>
                <!-- Card Request -->
                <div class="bg-teal-500 text-white p-6 rounded-2xl">
                    <p class="text-4xl font-bold">{{ $totalRequest }} Request</p>
                    <p class="text-sm font-medium mt-1">from {{ $requestFromUsers }} Users</p>
                </div>
                    <!-- Card Activities Users -->
                <div class="bg-white border border-gray-200 p-6 rounded-2xl flex items-center justify-between col-span-1 md:col-span-2">
                    <div class="flex items-center gap-4">
                        <div class="relative w-20 h-20">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path class="text-gray-200"
                                    stroke-dasharray="100, 100"
                                    d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke-width="3"></path>
                                <path class="text-red-500"
                                    stroke-dasharray="{{ $activitiesPercentage }}, 100"
                                    d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke-width="3" stroke-linecap="round"></path>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-lg font-bold text-red-500">
                                {{ $activitiesPercentage }}%
                            </div>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-800">{{ $activitiesUsers }}</p>
                            <p class="text-sm text-gray-500">Activities users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Activity Feed -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 p-6 rounded-2xl h-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">Activity</h3>
                    <button class="text-gray-500 hover:text-gray-800">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="space-y-6 overflow-y-auto h-[450px] custom-scrollbar pr-2">
                    @forelse($activities as $activity)
                    <div class="flex gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            @if(!$loop->last)
                            <div class="absolute left-1/2 top-10 h-full w-px bg-gray-200 -translate-x-1/2"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm">
                                <span class="font-semibold">{{ $activity['user'] }}</span>
                                <span class="text-gray-500">{{ $activity['action'] }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                            @if(isset($activity['details']))
                            <div class="mt-2 text-xs text-gray-500">
                                <p>{{ $activity['details']['text'] }}</p>
                                <div class="flex gap-2 mt-2">
                                    @foreach($activity['details']['images'] as $image)
                                    <img src="{{ $image }}" alt="Activity Image" class="w-16 h-16 object-cover rounded-md">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                        <p class="text-center text-gray-500">No activities found.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection

