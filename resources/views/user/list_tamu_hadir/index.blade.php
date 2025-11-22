<x-app-layout>
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Tamu Hadir') }}
        </h2>
    </x-slot>


    <div class="py-8 bg-white min-h-screen font-sans text-sm md:text-base">
        <div class="max-w-[98%] mx-auto px-1">

            <!-- TOMBOL DOWNLOAD PDF (BARU) -->
            <div class="flex justify-end mb-4 print:hidden">
                <a href="{{ route('attendance.pdf') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF Rekapan
                </a>
            </div>

            <!-- BAGIAN 1: HEADER INFO PENGANTIN -->
            <div class="grid grid-cols-1 md:grid-cols-[2fr_3fr] gap-y-1 mb-6 font-bold text-black uppercase text-sm md:text-base">
                <div class="flex items-center">
                    <span class="w-64">NAMA PENGANTIN</span>
                    <span class="mr-2">:</span>
                </div>
                <div class="text-left">{{ Auth::user()->name }}</div>

                <div class="flex items-center">
                    <span class="w-64">TANGGAL ACARA / LOKASI ACARA</span>
                    <span class="mr-2">:</span>
                </div>
                <div class="text-left">
                    {{ Auth::user()->event_date ?? '1 NOVEMBER 2025' }} / {{ Auth::user()->event_location ?? 'CASA EUNOIA' }}
                </div>
            </div>

            <!-- BAGIAN 2: TABEL RINGKASAN (SINGLE SERVER) -->
            <div class="mb-8 w-full overflow-x-auto">
                <table class="w-full border-collapse border border-black text-center font-bold text-sm md:text-base">
                    <thead>
                        <tr>
                            <!-- Kolom Biru (Kiri) -->
                            <th class="bg-[#0000FF] text-white py-1 border border-black uppercase tracking-wide w-2/3">
                                BY BIRU ID
                            </th>
                            <!-- Kolom Hitam (Kanan - Total) -->
                            <th class="bg-black text-white py-1 border border-black uppercase w-1/3">
                                TOTAL DATA
                            </th>
                        </tr>
                        <tr>
                            <!-- Sub-Header Biru -->
                            <th class="bg-[#0000FF] text-white py-1 border border-black text-lg tracking-widest">
                                0895-2621-6334
                            </th>
                            <!-- Sub-Header Hitam -->
                            <th class="bg-black text-white border border-black py-1">
                                SCAN BACAAN
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris 1: Jumlah Undangan -->
                        <tr class="h-10 bg-white text-black">
                            <td class="text-left px-4 border border-black">
                                Jumlah Undangan Yang Datang
                            </td>
                            <td class="border border-black text-lg">{{ $total_invites }}</td>
                        </tr>
                        <!-- Baris 2: Jumlah Orang (Pax) -->
                        <tr class="h-10 bg-white text-black">
                            <td class="text-left px-4 border border-black">
                                Jumlah Orang Yang Datang
                            </td>
                            <td class="border border-black text-lg">{{ $total_pax }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- BAGIAN 3: TABEL DATA TAMU (Tanpa Kolom Server) -->
            <div class="w-full overflow-x-auto">
                <div class="flex justify-end mb-1">
                    <span class="text-white text-xs bg-black px-2 py-1">Terakhir Diperbarui: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}</span>
                </div>
                
                <table class="w-full border-collapse border border-black text-sm">
                    <thead>
                        <tr class="bg-black text-white text-center font-bold font-serif h-12 border border-black">
                            <th class="border border-white w-12">No.</th>
                            <th class="border border-white text-left px-4">Nama Lengkap</th>
                            <th class="border border-white w-32 leading-tight px-2">Jumlah Tamu<br>(Orang)</th>
                            <th class="border border-white w-40 leading-tight px-2">Waktu Masuk (WIB)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#d1e7dd] text-black">
                        
                        @forelse($guests as $index => $guest)
                        <tr class="border border-black h-7 hover:bg-green-200 transition">
                            <td class="border-r border-black text-center">{{ $index + 1 }}</td>
                            
                            <td class="border-r border-black px-2 relative">
                                {{ $guest->name }}
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>

                            <td class="border-r border-black text-center font-bold">
                                {{ $guest->pax ?? 1 }}
                            </td>
                            
                            <td class="text-center border-r border-black">
                                <!-- UPDATE WAKTU DI SINI -->
                                {{ $guest->check_in_at ? \Carbon\Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr class="bg-white border-b border-black">
                            <td colspan="4" class="py-4 text-center italic">Belum ada tamu yang hadir.</td>
                        </tr>
                        @endforelse

                        <!-- Baris Kosong Pelengkap -->
                        @for($i = 0; $i < 5; $i++)
                        <tr class="border border-black h-7">
                            <td class="border-r border-black">&nbsp;</td>
                            <td class="border-r border-black px-2 relative">
                                &nbsp;
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>
                            <td class="border-r border-black">&nbsp;</td>
                            <td class="border-r border-black">&nbsp;</td>
                        </tr>
                        @endfor

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>