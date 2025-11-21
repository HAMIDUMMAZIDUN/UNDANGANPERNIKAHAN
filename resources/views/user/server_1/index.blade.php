<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Server 1') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen font-sans text-sm md:text-base">
        <div class="max-w-[98%] mx-auto px-1">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 font-bold text-black uppercase">
                <div class="space-y-1">
                    <div class="flex border-b border-black md:border-none">
                        <span class="w-1/2 md:w-64">NAMA PENGANTIN</span>
                        <span class="mx-2">:</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/2 md:w-64">TANGGAL ACARA / LOKASI ACARA</span>
                        <span class="mx-2">:</span>
                    </div>
                </div>
                <div class="text-right md:text-left space-y-1">
                    <div>Reza & Nabila</div>
                    <div>1 NOVEMBER 2025 / CASA EUNOIA</div>
                </div>
            </div>

            <div class="mb-8 w-full overflow-x-auto">
                <table class="w-full border-collapse border border-black text-center font-bold">
                    <thead>
                        <tr>
                            <th colspan="2" class="bg-[#0000FF] text-white py-2 border border-black uppercase text-lg">
                                BY BIRU ID
                            </th>
                            <th colspan="2" class="bg-black text-white py-1 border border-black uppercase w-32">
                                SERVER
                            </th>
                            <th rowspan="2" class="bg-black text-white py-1 border border-black uppercase w-24">
                                TOTAL
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" class="bg-[#0000FF] text-white py-2 border border-black text-lg">
                                0895-2621-6334
                            </th>
                            <th class="bg-black text-white border border-black py-1">1</th>
                            <th class="bg-black text-white border border-black py-1">2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="h-10">
                            <td colspan="2" class="bg-black text-white text-left px-4 border border-black">
                                Jumlah Undangan Yang Datang
                            </td>
                            <td class="bg-white text-black border border-black text-lg">25</td>
                            <td class="bg-white text-black border border-black text-lg">29</td>
                            <td class="bg-white text-black border border-black text-lg">54</td>
                        </tr>
                        <tr class="h-10">
                            <td colspan="2" class="bg-black text-white text-left px-4 border border-black">
                                Jumlah Orang Yang Datang
                            </td>
                            <td class="bg-white text-black border border-black text-lg">38</td>
                            <td class="bg-white text-black border border-black text-lg">51</td>
                            <td class="bg-white text-black border border-black text-lg">89</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full border-collapse border border-black">
                    <thead>
                        <tr class="bg-black text-white text-center font-bold font-serif tracking-wide h-12">
                            <th class="border border-white w-12">No.</th>
                            <th class="border border-white text-left px-4">Nama Lengkap</th>
                            <th class="border border-white w-32 leading-tight">
                                Jumlah<br>Tamu<br>(Orang)
                            </th>
                            <th class="border border-white w-32 leading-tight">
                                Waktu<br>Masuk
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#C6E0B4] text-black text-sm md:text-base">
                        
                        @forelse($guests as $index => $guest)
                        <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">{{ $index + 1 }}</td>
                            <td class="border-r border-black px-2 font-medium truncate max-w-xs md:max-w-none">
                                {{ $guest->name }}
                            </td>
                            <td class="border-r border-black text-center font-bold">
                                {{ $guest->pax ?? 1 }} </td>
                            <td class="text-center font-medium">
                                {{-- Format tanggal: 11/1/2025 --}}
                                {{ date('n/j/Y', strtotime($guest->created_at)) }}
                            </td>
                        </tr>
                        @empty
                        <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">1</td>
                            <td class="border-r border-black px-2">Mrs. Sukarni Wongso dan Keluarga</td>
                            <td class="border-r border-black text-center font-bold">2</td>
                            <td class="text-center">11/1/2025</td>
                        </tr>
                        <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">2</td>
                            <td class="border-r border-black px-2">Hary</td>
                            <td class="border-r border-black text-center font-bold">2</td>
                            <td class="text-center">11/1/2025</td>
                        </tr>
                        <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">3</td>
                            <td class="border-r border-black px-2">Mr. Vedry. dan Keluarga</td>
                            <td class="border-r border-black text-center font-bold">2</td>
                            <td class="text-center">11/1/2025</td>
                        </tr>
                        <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">4</td>
                            <td class="border-r border-black px-2">Mrs. Eca dan Keluarga</td>
                            <td class="border-r border-black text-center font-bold">2</td>
                            <td class="text-center">11/1/2025</td>
                        </tr>
                         <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">5</td>
                            <td class="border-r border-black px-2">Mrs. Emma dan Keluarga</td>
                            <td class="border-r border-black text-center font-bold">2</td>
                            <td class="text-center">11/1/2025</td>
                        </tr>
                        @endforelse

                        @for($i = 0; $i < 5; $i++)
                        <tr class="border border-black h-8">
                            <td class="border-r border-black text-center">&nbsp;</td>
                            <td class="border-r border-black px-2">&nbsp;</td>
                            <td class="border-r border-black text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                        @endfor

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>