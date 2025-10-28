@extends('admin.layouts.app')

{{-- Alpine.js untuk interaktivitas --}}
@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Daftar Event</h2>
            <p class="text-gray-500 text-sm">Semua event yang dibuat oleh client.</p>
        </div>
    </div>

    {{-- Baris Filter --}}
    <div class="flex flex-col md:flex-row justify-end items-center mb-6 gap-4">
        
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            
            <form id="searchForm" action="{{ route('admin.events.index') }}" method="GET" class="relative w-full md:w-auto">
                @if(request('start_date'))
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                @endif
                @if(request('end_date'))
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                @endif

                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input id="searchInput" type="text" name="search" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full md:w-64 text-sm" placeholder="Cari nama event / client..." value="{{ request('search') }}">
            </form>

            <form id="dateFilterForm" action="{{ route('admin.events.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="document.getElementById('dateFilterForm').submit();" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
                <span class="text-gray-500">To</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="document.getElementById('dateFilterForm').submit();" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full table-auto min-w-[1400px]"> {{-- Lebar minimal ditambah --}}
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mempelai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Waktu Acara</th> {{-- Judul diubah --}}
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telp Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Tamu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $events->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/60x40/f59e0b/334155?text=Ev' }}" alt="Foto Event" class="h-10 w-16 rounded object-cover">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->name }}</td>
                        {{-- TAMBAHAN BARU: Kolom Paket --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span title="Pria: {{ $event->groom_name ?? 'N/A' }}">{{ Str::limit($event->groom_name ?? 'N/A', 15) }}</span> &
                            <span title="Wanita: {{ $event->bride_name ?? 'N/A' }}">{{ Str::limit($event->bride_name ?? 'N/A', 15) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->date)->isoFormat('D MMM YYYY') }}</td>
                        {{-- PENYEMPURNAAN: Lokasi Akad & Resepsi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="font-medium" title="{{ $event->akad_location ?? 'N/A' }}">Akad: {{ Str::limit($event->akad_location ?? 'N/A', 25) }} ({{ $event->akad_time ?? 'N/A' }})</span>
                            <span class="block text-xs" title="{{ $event->resepsi_location ?? 'N/A' }}">Resepsi: {{ Str::limit($event->resepsi_location ?? 'N/A', 25) }} ({{ $event->resepsi_time ?? 'N/A' }})</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->phone_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $event->guests_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('events.public.show', $event->slug) }}" target="_blank" class="text-teal-600 hover:text-teal-900" title="Lihat Undangan">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- PERBAIKAN: Colspan disesuaikan menjadi 14 --}}
                        <td colspan="14" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data event ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $events->withQueryString()->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        
        if (searchInput && searchForm) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 500); 
            });
        }
    });
</script>
@endpush

