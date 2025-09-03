@extends('admin.layouts.app')

{{-- Alpine.js untuk interaktivitas dropdown --}}
@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Client</h2>
        </div>
        <a href="{{ route('admin.client.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-800 focus:outline-none focus:border-teal-800 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150">
            Tambah Klien
        </a>
    </div>

    {{-- Baris Filter --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        {{-- Filter Status --}}
        <div class="flex border-b border-gray-200">
            <a href="{{ route('admin.client.index') }}" class="px-4 py-2 text-sm font-semibold {{ !request('status') && !request('search') ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">All Client</a>
            <a href="{{ route('admin.client.index', ['status' => 'active']) }}" class="px-4 py-2 text-sm font-semibold {{ request('status') == 'active' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Active</a>
            <a href="{{ route('admin.client.index', ['status' => 'off']) }}" class="px-4 py-2 text-sm font-semibold {{ request('status') == 'off' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Off</a>
        </div>
        
        {{-- Form Pencarian dan Filter Tanggal --}}
        <div class="flex flex-col md:flex-row items-center gap-4">
            <!-- Search Bar -->
            <form id="searchForm" action="{{ route('admin.client.index') }}" method="GET" class="relative">
                 {{-- Simpan parameter lain saat mencari --}}
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('start_date'))
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                @endif
                 @if(request('end_date'))
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                @endif

                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input id="searchInput" type="text" name="search" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full md:w-64 text-sm" placeholder="Cari nama pengantin..." value="{{ request('search') }}">
            </form>

            <!-- Filter Tanggal -->
            <form id="dateFilterForm" action="{{ route('admin.client.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <input 
                    type="date" 
                    name="start_date"
                    value="{{ request('start_date') }}" 
                    onchange="document.getElementById('dateFilterForm').submit();"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                
                <span class="text-gray-500">To</span>

                <input 
                    type="date" 
                    name="end_date"
                    value="{{ request('end_date') }}"
                    onchange="document.getElementById('dateFilterForm').submit();"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama kedua Pengantin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Handphone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($clients as $client)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $clients->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if($client->events->isNotEmpty())
                                {{ $client->events->first()->groom_name ?? 'N/A' }} & {{ $client->events->first()->bride_name ?? 'N/A' }}
                            @else
                                {{ $client->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($client->events->isNotEmpty())
                                {{ $client->events->first()->phone_number ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Reguler</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($client->events->isNotEmpty())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Off</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->created_at->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-800">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
                                     x-transition>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Undangan</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat History</a>
                                    <form action="{{ route('admin.client.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klien ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data klien ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $clients->withQueryString()->links() }}
    </div>
@endsection

@push('scripts')
{{-- Script untuk auto-submit search bar --}}
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
                }, 500); // Jeda 500ms setelah user berhenti mengetik
            });
        }
    });
</script>
@endpush

