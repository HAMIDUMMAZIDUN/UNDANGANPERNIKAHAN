@extends('layouts.admin')

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Request Client</h2>
    </div>

    <!-- Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex border-b border-gray-200">
            <a href="{{ route('request.client.index') }}" class="px-4 py-2 text-sm font-semibold {{ !$status ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">All</a>
            <a href="{{ route('request.client.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'pending' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Pending</a>
            <a href="{{ route('request.client.index', ['status' => 'in_progress']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'in_progress' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">In Progress</a>
            <a href="{{ route('request.client.index', ['status' => 'complete']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'complete' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Complete</a>
            <a href="{{ route('request.client.index', ['status' => 'approve']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'approve' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Approve</a>
        </div>
        <div class="flex items-center gap-2">
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <span class="text-gray-500">To</span>
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <!-- Daftar Request -->
    <div class="space-y-8">
        
        @if(!$status || $status == 'pending')
        <!-- Pending -->
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> Pending ({{ $status ? $pending->count() : $all->where('status', 'pending')->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-gray-200" x-transition>
                @forelse($status ? $pending : $all->where('status', 'pending') as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-orange-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm text-gray-500">waiting-for-approval</span>
                        <a href="#" class="text-sm font-semibold text-red-600">PROCESS TO PAYMENT <i class="fas fa-arrow-right ml-2"></i></a>
                        <button class="text-gray-400"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada permintaan pending.</p>
                @endforelse
            </div>
        </div>
        @endif

        @if(!$status || $status == 'in_progress')
        <!-- In Progress -->
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> In Progress ({{ $status ? $inProgress->count() : $all->where('status', 'in_progress')->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-gray-200" x-transition>
                 @forelse($status ? $inProgress : $all->where('status', 'in_progress') as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-blue-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm text-gray-500">Approved</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 50%"></div>
                        </div>
                        <span class="text-sm font-bold">50%</span>
                        <button class="text-gray-400"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada permintaan dalam proses.</p>
                @endforelse
            </div>
        </div>
        @endif
        
        @if(!$status || $status == 'complete')
        <!-- Complete -->
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> Complete ({{ $status ? $complete->count() : $all->where('status', 'complete')->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-gray-200" x-transition>
                 @forelse($status ? $complete : $all->where('status', 'complete') as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-purple-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm text-gray-500">Approved</span>
                        <span class="text-sm font-semibold text-green-600"><i class="fas fa-check-circle mr-2"></i>Approved</span>
                        <a href="#" class="text-sm font-semibold text-blue-600">Details</a>
                        <button class="text-gray-400"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada permintaan yang selesai.</p>
                @endforelse
            </div>
        </div>
        @endif

        @if(!$status || $status == 'approve')
        <!-- Approve -->
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> Approve ({{ $status ? $approve->count() : $all->where('status', 'approve')->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-gray-200" x-transition>
                 @forelse($status ? $approve : $all->where('status', 'approve') as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-pink-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm text-gray-500">Approved</span>
                        <span class="text-sm font-semibold text-yellow-600 bg-yellow-100 px-2 py-1 rounded">ID</span>
                        <a href="#" class="text-sm font-semibold text-blue-600">Details</a>
                        <button class="text-gray-400"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada permintaan yang disetujui.</p>
                @endforelse
            </div>
        </div>
        @endif

    </div>
@endsection
