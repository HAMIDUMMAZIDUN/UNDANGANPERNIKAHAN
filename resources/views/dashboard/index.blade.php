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
<div class="bg-stone-200 min-h-screen font-sans">
    
    <!-- Header Gambar & Judul Event -->
    <div class="relative">
        <img src="{{ $event->photo_url ?? 'https://placehold.co/1200x600/e2e8f0/64748b?text=Foto+Header' }}" alt="Wedding Header" class="w-full h-48 sm:h-64 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-11/12 max-w-lg">
            <div class="bg-stone-800 bg-opacity-80 backdrop-blur-sm text-white text-center p-4 rounded-xl shadow-lg">
                <p class="text-xs uppercase tracking-widest">The Wedding Of</p>
                <h1 class="text-2xl font-bold">{{ $event->name }}</h1>
                <p class="text-sm">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kartu Event -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
            </div>
        <!-- Tabel Tamu Hadir -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-stone-800">Tamu Hadir</h2>
                        <p class="text-sm text-stone-500">Undangan: <span class="font-semibold">{{ $totalUndangan }}</span> | Hadir: <span class="font-semibold">{{ $jumlahHadir }}</span></p>
                    </div>
                    <button class="bg-stone-700 text-white text-sm font-semibold py-2 px-4 rounded-lg hover:bg-stone-800 transition">
                        Kode Akses
                    </button>
                </div>

                <!-- Form Pencarian -->
                <form action="{{ route('dashboard.index') }}" method="GET" class="relative mb-4">
                    <input type="text" name="search" placeholder="search..." value="{{ request('search') }}" class="w-full pl-4 pr-10 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>

                <!-- Area Data -->
                <div class="space-y-3">
                    @forelse ($guests as $guest)
                        <div class="p-3 border-b border-stone-200">
                            <p class="font-semibold text-stone-800">{{ $guest->name }}</p>
                            <p class="text-sm text-stone-600">{{ $guest->alamat }}</p>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <p class="text-stone-500 text-lg">Tidak ada data!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Kartu (Pagination) -->
            <div class="bg-stone-50 p-4">
                {{ $guests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
