@extends('layouts.admin')

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Order History</h2>
    </div>

    <!-- Filter dan Aksi -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- Tabs Filter -->
        <div class="flex border-b border-gray-200">
            <a href="{{ route('order.history.index') }}" class="px-4 py-2 text-sm font-semibold {{ !request('status') ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">All</a>
            <a href="{{ route('order.history.index', ['status' => 'complete']) }}" class="px-4 py-2 text-sm font-semibold {{ request('status') == 'complete' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Complete</a>
            <a href="#" class="px-4 py-2 text-sm font-semibold text-gray-500">Packet</a>
        </div>
        <!-- Filter Tanggal -->
        <div class="flex items-center gap-2">
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <span class="text-gray-500">To</span>
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <!-- Tabel Order History -->
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Handphone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $order)
                    @if ($order->user) {{-- Pastikan event memiliki user --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=random" alt="Avatar" class="w-8 h-8 rounded-full">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->phone_number ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Reguler</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($order->date < now())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Complete</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-800">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" x-transition>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Undangan</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat History</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada riwayat pesanan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection
