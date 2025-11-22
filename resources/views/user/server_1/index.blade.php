<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Bacaan') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen font-sans" x-data="{ showManualInput: false }">
        <div class="max-w-4xl mx-auto px-4">
            
            <!-- 1. HEADER JUDUL -->
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-black text-black uppercase tracking-wide">DIGITAL GUESTBOOK SERVER 1</h1>
                <p class="text-sm md:text-base font-bold text-black mt-1">LOKASI: PINTU MASUK UTAMA</p>
            </div>

            <!-- 2. AREA PENCARIAN -->
            <div class="mb-8 relative max-w-xl mx-auto">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center shadow-md animate-pulse">
                        <strong class="font-bold text-lg">SUKSES!</strong><br>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center shadow-md">
                        <strong class="font-bold text-lg">SUDAH CHECK-IN!</strong><br>
                        <span class="block sm:inline">{{ session('warning') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center shadow-md">
                        <strong class="font-bold text-lg">TIDAK DITEMUKAN!</strong><br>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form Pencarian -->
                <form id="checkin-form" method="GET" action="{{ route('server1') }}" class="relative" autocomplete="off">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        
                        <input type="text" id="search-input" name="search" 
                               class="block w-full p-4 pl-12 text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                               placeholder="Scan Barcode / Ketik Nama Tamu..." autofocus required>
                        
                        <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Check In</button>
                    </div>

                    <!-- Dropdown Autocomplete -->
                    <div id="autocomplete-results" class="hidden absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-xl mt-1 max-h-60 overflow-y-auto"></div>
                </form>
            </div>

            <!-- 3. TABEL STATUS (UPDATE: Tambah Kolom Aksi) -->
            <div class="border border-black rounded-lg overflow-hidden shadow-lg">
                <div class="bg-black text-white p-3 font-bold uppercase tracking-wider text-center">
                    Status Check-In Terkini (10 Terakhir)
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase">Waktu (WIB)</th>
                                <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase">Nama Tamu</th>
                                <th class="py-3 px-4 text-center text-xs font-bold text-gray-600 uppercase">Pax</th>
                                <th class="py-3 px-4 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                                <th class="py-3 px-4 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            
                            {{-- BARIS MERAH (Nama Tidak Ditemukan) --}}
                            @if(session('not_found_name'))
                            <tr class="bg-red-100 border-l-4 border-red-600">
                                <td class="py-4 px-4 text-sm text-red-600 font-bold whitespace-nowrap">
                                    {{ now()->timezone('Asia/Jakarta')->format('H:i:s') }}
                                </td>
                                <td class="py-4 px-4 text-sm font-black text-red-800 uppercase">
                                    {{ session('not_found_name') }} <span class="text-xs font-normal ml-2 italic">(Tidak terdaftar)</span>
                                </td>
                                <td class="py-4 px-4 text-center text-sm font-bold text-red-800">-</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2 py-1 text-xs font-bold leading-tight rounded-full bg-red-200 text-red-900">
                                        Gagal
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <!-- Tombol Input Manual -->
                                    <button x-on:click="$dispatch('open-modal', 'manual-input-modal')" 
                                            class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-3 rounded shadow-sm transition flex items-center justify-center mx-auto gap-1 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Input Manual
                                    </button>
                                </td>
                            </tr>
                            @endif

                            {{-- LOOP DATA TAMU --}}
                            @forelse($guests as $guest)
                                @php
                                    $rowClass = 'hover:bg-gray-50';
                                    $statusText = 'Sukses';
                                    $statusBadge = 'bg-gray-100 text-gray-800';

                                    // Pewarnaan Baris
                                    if(session('highlight_id') == $guest->id) {
                                        if(session('warning')) {
                                            $rowClass = 'bg-yellow-100 border-l-4 border-yellow-500'; 
                                            $statusText = 'Duplikat';
                                            $statusBadge = 'bg-yellow-200 text-yellow-900';
                                        } elseif(session('success')) {
                                            $rowClass = 'bg-green-100 border-l-4 border-green-500';
                                            $statusText = 'Baru Masuk';
                                            $statusBadge = 'bg-green-200 text-green-900';
                                        }
                                    }
                                @endphp

                                <tr class="{{ $rowClass }} transition duration-150 ease-in-out">
                                    <td class="py-3 px-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('H:i:s') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-gray-900 uppercase">
                                        {{ $guest->name }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-center font-semibold text-gray-900">
                                        {{ $guest->pax }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-1 text-xs font-bold leading-tight rounded-full {{ $statusBadge }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <!-- FITUR HAPUS DATA TAMU -->
                                        <form action="{{ route('guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Yakin ingin MENGHAPUS data tamu {{ $guest->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50" title="Hapus Data">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                @if(!session('not_found_name'))
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500 italic bg-gray-50">
                                        Belum ada tamu check-in hari ini.
                                    </td>
                                </tr>
                                @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- REFRESH -->
            <div class="mt-6 text-center">
                <a href="{{ route('server1') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Halaman
                </a>
            </div>
        </div>
    </div>

    <!-- MODAL INPUT MANUAL -->
    <x-modal name="manual-input-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                Input Tamu Manual
            </h2>
            <p class="mb-4 text-sm text-gray-600">
                Masukkan data tamu yang tidak terdaftar. Tamu ini akan langsung otomatis di-Check In.
            </p>

            <form method="POST" action="{{ route('guests.store') }}">
                @csrf
                <input type="hidden" name="is_manual_checkin" value="1">

                <div class="mb-4">
                    <x-input-label for="manual_name" value="Nama Tamu" />
                    <x-text-input id="manual_name" class="block mt-1 w-full uppercase" type="text" name="name" 
                                  :value="session('not_found_name')" required autofocus />
                </div>

                <div class="mb-6">
                    <x-input-label for="manual_pax" value="Jumlah Pax (Orang)" />
                    <x-text-input id="manual_pax" class="block mt-1 w-full" type="number" name="pax" value="1" min="1" required />
                </div>

                <div class="flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Batal
                    </x-secondary-button>
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                        Simpan & Check In
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const resultsDiv = document.getElementById('autocomplete-results');
            const form = document.getElementById('checkin-form');
            let debounceTimer;

            searchInput.focus();

            searchInput.addEventListener('input', function() {
                const query = this.value;
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    resultsDiv.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetchSearchResults(query);
                }, 300);
            });

            function fetchSearchResults(query) {
                fetch(`{{ route('guests.ajax_search') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        
                        if (data.length > 0) {
                            resultsDiv.classList.remove('hidden');
                            
                            data.forEach(guest => {
                                const isCheckedIn = guest.check_in_at !== null;
                                const statusText = isCheckedIn ? '(Sudah Masuk)' : '';
                                const statusClass = isCheckedIn ? 'text-red-500 font-bold' : 'text-green-600';
                                const bgClass = isCheckedIn ? 'bg-gray-50 opacity-75' : 'hover:bg-blue-50';
                                
                                const item = document.createElement('div');
                                item.className = `p-3 border-b last:border-b-0 flex justify-between items-center cursor-pointer ${bgClass}`;
                                item.innerHTML = `
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 uppercase text-sm md:text-base">${guest.name}</span>
                                        <span class="text-xs ${statusClass}">${statusText}</span>
                                    </div>
                                    <span class="text-xs font-semibold bg-gray-200 px-2 py-1 rounded text-gray-700">${guest.pax} Pax</span>
                                `;

                                item.addEventListener('click', function() {
                                    searchInput.value = guest.name;
                                    resultsDiv.classList.add('hidden');
                                    form.submit();
                                });

                                resultsDiv.appendChild(item);
                            });
                        } else {
                            resultsDiv.classList.remove('hidden');
                            resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500 italic text-center">Tamu tidak ditemukan</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching autocomplete:', error);
                    });
            }

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>