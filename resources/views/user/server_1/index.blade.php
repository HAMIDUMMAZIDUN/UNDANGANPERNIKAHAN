<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Bacaan (Server 1)') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen font-sans">
        <div class="max-w-[98%] mx-auto px-2">
            
            <!-- AREA SCANNER (FOCUS UTAMA) -->
            <div class="max-w-3xl mx-auto mb-8">
                <div class="bg-gray-100 p-6 rounded-lg shadow-inner border border-gray-300 text-center">
                    <h3 class="text-lg font-bold mb-2 text-gray-700">SCAN BARCODE / KETIK NAMA TAMU</h3>
                    
                    <form method="GET" action="{{ route('server1') }}" class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 16v1m0 0l3-3m-3 3l-3-3M12 16V4m-4 12H6m2-16H4a2 2 0 00-2 2v12a2 2 0 002 2h4m8-16h4a2 2 0 012 2v12a2 2 0 01-2 2h-4"></path></svg>
                        </div>
                        <!-- AUTOFOCUS: Siap menerima input scanner -->
                        <input type="text" name="search" 
                            class="block w-full p-4 pl-12 text-lg text-gray-900 border border-blue-500 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 shadow-lg" 
                            placeholder="Scan QR Code atau Ketik Nama..." autofocus autocomplete="off" required>
                        <button type="submit" class="absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-white">
                            CHECK-IN
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-2">*Pastikan kursor berkedip di kolom input sebelum melakukan scanning.</p>
                </div>
            </div>

            <!-- NOTIFIKASI HASIL SCAN -->
            @if(session('success'))
                <div class="max-w-3xl mx-auto mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-center shadow-md" role="alert">
                    <p class="font-bold text-lg">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-3xl mx-auto mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 text-center shadow-md" role="alert">
                    <p class="font-bold text-lg">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="max-w-3xl mx-auto mb-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 text-center shadow-md" role="alert">
                    <p class="font-bold text-lg">{{ session('warning') }}</p>
                </div>
            @endif

            <!-- INFO PENGANTIN -->
            <div class="mb-6 max-w-4xl mx-auto text-center md:text-left">
                <div class="grid grid-cols-1 md:grid-cols-[180px_10px_auto] gap-y-1 text-sm md:text-base font-bold text-black">
                    <div>Nama Pengantin</div>
                    <div class="hidden md:block">:</div>
                    <div class="uppercase mb-2 md:mb-0">{{ Auth::user()->name }}</div>

                    <div>Tanggal Acara</div>
                    <div class="hidden md:block">:</div>
                    <div class="uppercase">
                        {{ Auth::user()->event_date ?? '1 November 2025' }} / {{ Auth::user()->event_location ?? 'CASA EUNOIA' }}
                    </div>
                </div>
            </div>

            <!-- TABEL DATA TAMU YANG SUDAH SCAN (SERVER 1) -->
            <div class="flex flex-col gap-6">
                <div class="w-full">
                    <!-- Container dengan max-height agar jika data ribuan tetap rapi (scroll di dalam tabel) -->
                    <!-- Saya set max-height screen agar tabel bisa di-scroll tanpa menghilangkan header input -->
                    <div class="border border-black overflow-y-auto max-h-[600px]">
                        <table class="min-w-full border-collapse border border-black text-black relative">
                            <!-- Sticky Header: Agar judul kolom tetap terlihat saat scroll ke bawah -->
                            <thead class="sticky top-0 z-10">
                                <tr class="bg-blue-900 text-white text-sm uppercase font-bold tracking-wider">
                                    <th class="py-3 px-2 text-center border border-gray-600 w-10">No.</th>
                                    <th class="py-3 px-4 text-left border border-gray-600">Nama Tamu (Server 1)</th>
                                    <th class="py-3 px-2 text-center border border-gray-600 w-32">Pax</th>
                                    <th class="py-3 px-2 text-center border border-gray-600 w-48">Waktu Scan</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-semibold text-black bg-white">
                                @forelse($guests as $index => $guest)
                                <!-- Highlight baris pertama (yang baru saja di-scan) -->
                                <tr class="{{ $index == 0 ? 'bg-green-100 border-2 border-green-500' : ($loop->even ? 'bg-white' : 'bg-gray-100') }} border-b border-black transition">
                                    <!-- Menampilkan Nomor Urut 1, 2, 3... dst tanpa batasan -->
                                    <td class="py-2 px-2 border-r border-black text-center">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4 border-r border-black uppercase relative">
                                        {{ $guest->name }}
                                        @if($index == 0)
                                            <span class="absolute right-2 top-2 inline-flex items-center bg-green-600 text-white text-xs px-2 py-0.5 rounded animate-pulse">BARU</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-2 border-r border-black text-center">{{ $guest->pax }}</td>
                                    <td class="py-2 px-2 text-center text-gray-700">
                                        {{ $guest->check_in_at ? \Carbon\Carbon::parse($guest->check_in_at)->format('H:i:s') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-gray-50 border-b border-black">
                                    <td colspan="4" class="py-8 text-center italic text-gray-500">
                                        Belum ada tamu yang melakukan scan di Server 1.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Tampilkan total data di bawah tabel -->
                    <div class="mt-2 text-right text-sm font-bold text-gray-700">
                        Total Tamu Scan: {{ count($guests) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>