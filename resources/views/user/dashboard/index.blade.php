@extends('user.layouts.app')

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
    <div>
        {{-- BAGIAN 1: HEADER --}}
        <div class="relative mb-16"> {{-- Beri margin bawah agar tidak tertimpa konten --}}
            {{-- Gambar Header --}}
            <div class="rounded-lg overflow-hidden">
                <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/1200x600/f59e0b/f59e0b' }}" alt="Wedding Header" class="w-full h-48 sm:h-64 object-cover">
            </div>

            {{-- Kotak Info Acara --}}
            {{-- DIPERBAIKI: Posisi diubah agar setengah di atas gambar, setengah di bawah --}}
            <div class="absolute top-full left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-lg mx-auto">
                <div class="bg-amber-500 bg-opacity-80 backdrop-blur-sm text-white text-center p-4 rounded-xl shadow-lg">
                    <p class="text-xs uppercase tracking-widest">The Wedding Of</p>
                    <h1 class="text-2xl font-bold">{{ $event->name }}</h1>
                    <p class="text-sm">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: KONTEN UTAMA --}}
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6"> 
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-stone-800">
                            @if (request('search'))
                                Hasil Pencarian Tamu
                            @else
                                Tamu Sudah Hadir
                            @endif
                        </h2>
                    </div>
                </div>

                {{-- Statistik Singkat --}}
                {{-- PENYEMPURNAAN: Label diubah agar lebih jelas --}}
                <div class="p-3 bg-stone-50 border border-stone-200 rounded-lg mb-6">
                    <p class="text-sm text-stone-600 text-center">
                        Total Undangan: <span class="font-bold text-stone-900">{{ $totalUndangan }}</span>
                        <span class="mx-2">|</span>
                        Undangan Hadir: <span class="font-bold text-stone-900">{{ $jumlahHadir }}</span>
                        <span class="mx-2">|</span>
                        Total Orang Hadir: <span class="font-bold text-stone-900">{{ $totalOrangHadir }}</span>
                        <span class="text-stone-500 ml-1">Orang</span>
                    </p>
                </div>
                
                {{-- DIPERBAIKI: Nama rute diubah dari 'dashboard.index' menjadi 'dashboard' --}}
                <form action="{{ route('dashboard') }}" method="GET" class="relative mb-4">
                    <input type="text" name="search" placeholder="Cari nama tamu..." value="{{ request('search') }}" class="w-full pl-4 pr-10 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.76l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </button>
                </form>

                {{-- PENYEMPURNAAN: Menggunakan 'divide-y' untuk garis pemisah yang lebih rapi --}}
                <div class="space-y-4 divide-y divide-stone-200">
                    @forelse ($guests as $guest)
                        <div class="pt-4 first:pt-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-stone-800">#{{ $guest->id }}. {{ $guest->name }}</p>
                                    <p class="text-sm text-stone-500 mt-1">
                                        {{ $guest->address ?: '-' }} | {{ $guest->number_of_guests ?: '1' }} Orang
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($guest->check_in_time)
                                        {{-- DIPERBAIKI: Format waktu diubah dari 'H;i:s' menjadi 'H:i:s' --}}
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
                <div class="bg-stone-50 p-4 border-t border-stone-200 rounded-b-lg">
                    {{ $guests->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection