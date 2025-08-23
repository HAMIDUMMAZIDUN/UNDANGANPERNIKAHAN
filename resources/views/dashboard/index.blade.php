@extends('layouts.app')

@section('page_title', 'Dashboard')

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endif

@section('content')
    <div class="relative">
    
        <div class="rounded-t-lg overflow-hidden">
            {{-- PERBAIKAN: Menambahkan 'storage/' pada path asset --}}
            <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/1200x600/f59e0b/334155?text=Wedding+Header' }}" alt="Wedding Header" class="w-full h-48 sm:h-64 object-cover">
        </div>

        <div class="bg-white rounded-b-lg shadow-md overflow-hidden">
    <div class="pt-20 px-6 pb-6"> 
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-2xl font-bold text-stone-800">Tamu Hadir</h2>
            </div>
        </div>

        <!-- Info Statistik -->
        <div class="p-3 bg-stone-50 border border-stone-200 rounded-lg mb-4">
    <p class="text-sm text-stone-600">
        Undangan: <span class="font-bold text-stone-900">{{ $totalUndangan }}</span> | 
        Hadir: <span class="font-bold text-stone-900">{{ $totalOrangHadir }}</span> /
        <span class="font-bold text-stone-900">{{ $totalPotensiOrang }}</span>
        <span class="text-stone-500">Orang</span>
    </p>
</div>
        <!-- Form Pencarian -->
        <form action="{{ route('dashboard.index') }}" method="GET" class="relative mb-4">
            <input type="text" name="search" placeholder="search..." value="{{ request('search') }}" class="w-full pl-4 pr-10 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-500">
            <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.76l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
            </button>
        </form>

        <!-- Daftar Tamu -->
        <div class="space-y-4">
            @forelse ($guests as $guest)
                <div class="border-b border-stone-200 pb-3 last:border-b-0">
                    <div class="flex justify-between items-start">
                        <!-- Info Tamu -->
                        <div>
                            <p class="font-bold text-stone-800">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}. {{ $guest->name }}</p>
                            <p class="text-sm text-stone-500 mt-1">
                                {{ $guest->address?: '-' }} | {{ $guest->number_of_guests ?: '1' }} Orang
                            </p>
                        </div>
                        <!-- Waktu Kehadiran -->
                        <div class="text-right">
                            @if($guest->check_in_time)
                                <p class="text-xs text-stone-500">{{ \Carbon\Carbon::parse($guest->check_in_time)->format('d/m/Y H:i:s') }}</p>
                            @else
                                <span class="text-xs font-medium text-red-600">Belum Hadir</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-stone-500">
                        @if(request('search'))
                            Tidak ada tamu yang cocok dengan pencarian.
                        @else
                            Belum ada tamu yang hadir.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    @if ($guests->hasPages())
        <div class="bg-stone-50 p-4 border-t border-stone-200">
            {{ $guests->withQueryString()->links() }}
        </div>
    @endif
</div>

        <div class="absolute top-48 sm:top-64 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-lg">
            <div class="bg-stone-800 bg-opacity-80 backdrop-blur-sm text-white text-center p-4 rounded-xl shadow-lg">
                <p class="text-xs uppercase tracking-widest">The Wedding Of</p>
                <h1 class="text-2xl font-bold">{{ $event->name }}</h1>
                <p class="text-sm">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </div>

    </div>
@endsection
