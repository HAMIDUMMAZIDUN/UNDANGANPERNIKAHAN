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
             <img src="{{ $event->photo_url ? asset($event->photo_url) : 'https://placehold.co/1200x600/e2e8f0/64748b?text=Foto+Header' }}" alt="Wedding Header" class="w-full h-48 sm:h-64 object-cover">
        </div>

        <div class="bg-white rounded-b-lg shadow-md overflow-hidden">
             <div class="pt-20 px-6 pb-6"> 
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-stone-800">Daftar Tamu</h2>
                        <p class="text-sm text-stone-500">Total Undangan: <span class="font-semibold text-stone-800">{{ $totalUndangan }}</span> | Hadir: <span class="font-semibold text-stone-800">{{ $jumlahHadir }}</span></p>
                    </div>
                    <button class="bg-amber-500 text-white text-sm font-semibold py-2 px-4 rounded-lg hover:bg-stone-800 transition">
                        Kode Akses
                    </button>
                </div>

                <form action="{{ route('dashboard.index') }}" method="GET" class="relative mb-4">
                    <input type="text" name="search" placeholder="Cari nama tamu..." value="{{ request('search') }}" class="w-full pl-4 pr-10 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.76l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </button>
                </form>

                <div class="space-y-3">
                    @forelse ($guests as $guest)
                        <div class="p-3 border-b border-stone-200 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-black">{{ $guest->name }}</p>
                                    <p class="text-sm text-stone-600">{{ $guest->alamat }}</p>
                                </div>
                                @if($guest->status == 'hadir')
                                    <span class="bg-green-400 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Hadir</span>
                                @else
                                    <span class="bg-red-400 text-white text-xs font-medium px-2.5 py-1 rounded-full">Belum Hadir</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-stone-500 text-lg">Tidak ada tamu yang cocok dengan pencarian.</p>
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