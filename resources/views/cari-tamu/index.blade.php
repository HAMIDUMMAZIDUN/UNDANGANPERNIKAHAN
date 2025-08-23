@extends('layouts.app')

@section('page_title', 'Cari Tamu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Cari Tamu Undangan</h1>
        <p class="text-slate-500 mt-1">Ketik nama tamu atau kategori untuk memulai pencarian.</p>
    </div>

    {{-- Form Pencarian --}}
    <div class="mb-5">
        <form action="{{ route('cari-tamu.index') }}" method="GET">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tamu atau kategori..." class="w-full pl-4 pr-10 py-3 border border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition">
                <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Hasil Pencarian --}}
    <div class="bg-white shadow-md rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($guests as $guest)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $guest->affiliation }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($guest->check_in_time)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Sudah Hadir</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">Belum Hadir</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-16 text-slate-500">
                            @if(request('search'))
                                <p class="font-semibold">Tamu tidak ditemukan.</p>
                                <p class="text-sm mt-1">Tidak ada tamu yang cocok dengan kata kunci "{{ request('search') }}".</p>
                            @else
                                <p class="font-semibold">Silakan mulai pencarian.</p>
                                <p class="text-sm mt-1">Gunakan kotak di atas untuk mencari tamu.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($guests->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $guests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
