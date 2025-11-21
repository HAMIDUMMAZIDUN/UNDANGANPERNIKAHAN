<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Tamu Hadir') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen font-sans text-sm md:text-base">
        <div class="max-w-[98%] mx-auto px-1">

            <!-- BAGIAN 1: HEADER INFO PENGANTIN -->
            <div class="grid grid-cols-1 md:grid-cols-[2fr_3fr] gap-y-1 mb-6 font-bold text-black uppercase text-sm md:text-base">
                <!-- Baris 1 -->
                <div class="flex items-center">
                    <span class="w-64">NAMA PENGANTIN</span>
                    <span class="mr-2">:</span>
                </div>
                <div class="text-left">Reza & Nabila</div>

                <!-- Baris 2 -->
                <div class="flex items-center">
                    <span class="w-64">TANGGAL ACARA / LOKASI ACARA</span>
                    <span class="mr-2">:</span>
                </div>
                <div class="text-left">1 NOVEMBER 2025 / CASA EUNOIA</div>
            </div>

            <!-- BAGIAN 2: TABEL RINGKASAN (REKAPITULASI) -->
            <!-- Menggunakan background Biru dan Hitam sesuai gambar -->
            <div class="mb-8 w-full overflow-x-auto">
                <table class="w-full border-collapse border border-black text-center font-bold text-sm md:text-base">
                    <thead>
                        <!-- Baris Header Atas -->
                        <tr>
                            <!-- Kolom Biru -->
                            <th colspan="2" class="bg-[#0000FF] text-white py-1 border border-black uppercase tracking-wide">
                                BY BIRU ID
                            </th>
                            <!-- Kolom Server -->
                            <th colspan="2" class="bg-black text-white py-1 border border-black uppercase w-32">
                                SERVER
                            </th>
                            <th rowspan="2" class="bg-black text-white py-1 border border-black uppercase w-24">
                                TOTAL
                            </th>
                        </tr>
                        <!-- Baris Header Bawah (No Telp & Angka Server) -->
                        <tr>
                            <!-- Kolom Biru (Lanjutan) -->
                            <th colspan="2" class="bg-[#0000FF] text-white py-1 border border-black text-lg tracking-widest">
                                0895-2621-6334
                            </th>
                            <th class="bg-black text-white border border-black py-1">1</th>
                            <th class="bg-black text-white border border-black py-1">2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris Data 1: Jumlah Undangan -->
                        <tr class="h-8 bg-white text-black">
                            <td colspan="2" class="text-left px-4 border border-black">
                                Jumlah Undangan Yang Datang
                            </td>
                            <!-- Note: Anda bisa mengganti angka statis ini dengan variabel dari controller nanti -->
                            <td class="border border-black">44</td>
                            <td class="border border-black">29</td>
                            <td class="border border-black">73</td>
                        </tr>
                        <!-- Baris Data 2: Jumlah Orang -->
                        <tr class="h-8 bg-white text-black">
                            <td colspan="2" class="text-left px-4 border border-black">
                                Jumlah Orang Yang Datang
                            </td>
                            <!-- Note: Anda bisa mengganti angka statis ini dengan variabel dari controller nanti -->
                            <td class="border border-black">69</td>
                            <td class="border border-black">51</td>
                            <td class="border border-black">120</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- BAGIAN 3: TABEL DATA TAMU (HIJAU) -->
            <div class="w-full overflow-x-auto">
                <div class="flex justify-end mb-1">
                    <span class="text-white text-xs bg-black px-2 py-1">Terakhir Diperbarui: {{ now()->format('d/m/Y H:i:s') }}</span>
                </div>
                
                <table class="w-full border-collapse border border-black text-sm">
                    <thead>
                        <tr class="bg-black text-white text-center font-bold font-serif h-12 border border-black">
                            <th class="border border-white w-12">No.</th>
                            <th class="border border-white text-left px-4">Nama Lengkap</th>
                            <th class="border border-white w-32 leading-tight px-2">
                                Jumlah Tamu<br>(Orang)
                            </th>
                            <th class="border border-white w-40 leading-tight px-2">
                                Waktu Masuk
                            </th>
                        </tr>
                    </thead>
                    <!-- Background Hijau Mint (#d1e7dd adalah pendekatan warna Excel hijau muda) -->
                    <tbody class="bg-[#d1e7dd] text-black">
                        
                        @forelse($guests as $index => $guest)
                        <tr class="border border-black h-7 hover:bg-green-200 transition">
                            <td class="border-r border-black text-center">{{ $index + 1 }}</td>
                            
                            <!-- Nama Lengkap dengan Indikator Segitiga Merah (Excel Style) -->
                            <td class="border-r border-black px-2 relative">
                                {{ $guest->name }}
                                <!-- Segitiga Merah Kecil di Pojok Kanan Atas -->
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>

                            <td class="border-r border-black text-center">
                                {{ $guest->pax ?? 1 }}
                            </td>
                            
                            <td class="text-center border-r border-black">
                                <!-- Format Waktu: 01/11/2025 21:45:15 -->
                                {{ $guest->created_at ? \Carbon\Carbon::parse($guest->created_at)->format('d/m/Y H:i:s') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <!-- Tampilan Dummy Jika Data Kosong (Agar tampilan tetap bagus saat preview) -->
                        <tr class="border border-black h-7">
                            <td class="border-r border-black text-center">1</td>
                            <td class="border-r border-black px-2 relative">
                                Ayip
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>
                            <td class="border-r border-black text-center">1</td>
                            <td class="text-center border-r border-black">01/11/2025 21:45:15</td>
                        </tr>
                        <tr class="border border-black h-7">
                            <td class="border-r border-black text-center">2</td>
                            <td class="border-r border-black px-2 relative">
                                BIDANG II HIPMI JABAR
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>
                            <td class="border-r border-black text-center">1</td>
                            <td class="text-center border-r border-black">01/11/2025 20:42:07</td>
                        </tr>
                         <tr class="border border-black h-7">
                            <td class="border-r border-black text-center">3</td>
                            <td class="border-r border-black px-2 relative">
                                Mr. Reza Mansyur d
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>
                            <td class="border-r border-black text-center">1</td>
                            <td class="text-center border-r border-black">01/11/2025 20:41:14</td>
                        </tr>
                        @endforelse

                        <!-- Loop baris kosong tambahan agar tabel terlihat panjang -->
                        @for($i = 0; $i < 10; $i++)
                        <tr class="border border-black h-7">
                            <td class="border-r border-black text-center">&nbsp;</td>
                            <td class="border-r border-black px-2 relative">
                                &nbsp;
                                <!-- Segitiga Merah juga di baris kosong sesuai gambar -->
                                <div class="absolute top-0 right-0 w-0 h-0 border-t-[6px] border-t-red-600 border-l-[6px] border-l-transparent"></div>
                            </td>
                            <td class="border-r border-black text-center">&nbsp;</td>
                            <td class="text-center border-r border-black">&nbsp;</td>
                        </tr>
                        @endfor

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>