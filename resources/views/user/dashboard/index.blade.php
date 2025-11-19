<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Digital Guestbook') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6 border-b-4 border-black">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-black text-black uppercase tracking-widest">DIGITAL GUESTBOOK BY BIRU ID</h1>
                    <p class="text-sm font-bold text-gray-600 mt-1">IG : BY BIRU ID - WA : 0895-2621-6334</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm md:text-base">
                    <div class="flex">
                        <span class="font-bold w-40">Nama Pengantin</span>
                        <span class="font-bold mx-2">:</span>
                        <span class="font-semibold text-gray-800">Reza & Nabila</span>
                    </div>
                    <div class="flex">
                        <span class="font-bold w-48">Tanggal / Lokasi Acara</span>
                        <span class="font-bold mx-2">:</span>
                        <span class="font-semibold text-gray-800 uppercase">1 November 2025 / CASA EUNOIA</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                
                <div class="w-full lg:w-3/4">
                    <div class="bg-white shadow-lg overflow-hidden border border-gray-300">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="bg-black text-white text-sm uppercase tracking-wider">
                                    <th rowspan="2" class="py-3 px-4 text-left border-r border-gray-600 w-12">No.</th>
                                    <th rowspan="2" class="py-3 px-4 text-left border-r border-gray-600">Nama Tamu</th>
                                    <th colspan="2" class="py-2 px-4 text-center border-b border-gray-600">Undangan Yang Dikirim</th>
                                </tr>
                                <tr class="bg-black text-white text-sm uppercase">
                                    <th class="py-2 px-2 text-center w-24 border-r border-gray-600">Online</th>
                                    <th class="py-2 px-2 text-center w-24">Fisik</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                                @forelse($guests as $index => $guest)
                                <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }} hover:bg-blue-50 transition">
                                    <td class="py-2 px-4 border-r border-gray-300 font-medium text-center">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4 border-r border-gray-300 uppercase font-bold">{{ $guest->name }}</td>
                                    
                                    <td class="py-2 px-4 border-r border-gray-300 text-center">
                                        <input type="checkbox" disabled {{ $guest->is_online_invited ? 'checked' : '' }} 
                                               class="w-5 h-5 text-black border-gray-400 rounded focus:ring-black">
                                    </td>
                                    
                                    <td class="py-2 px-4 text-center">
                                        <input type="checkbox" disabled {{ $guest->is_physical_invited ? 'checked' : '' }} 
                                               class="w-5 h-5 text-black border-gray-400 rounded focus:ring-black">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500 italic">Belum ada data tamu.</td>
                                </tr>
                                @endforelse
                                
                                @for($i = 0; $i < 10; $i++)
                                <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                                    <td class="py-3 border-r border-gray-300">&nbsp;</td>
                                    <td class="py-3 border-r border-gray-300">&nbsp;</td>
                                    <td class="py-3 border-r border-gray-300 text-center">
                                        <input type="checkbox" disabled class="w-5 h-5 border-gray-300 rounded bg-transparent">
                                    </td>
                                    <td class="py-3 text-center">
                                        <input type="checkbox" disabled class="w-5 h-5 border-gray-300 rounded bg-transparent">
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-full lg:w-1/4 space-y-4">
                    
                    <div class="bg-black p-1 shadow-lg">
                        <div class="bg-black text-white font-bold text-center py-1 uppercase text-sm tracking-wider">
                            Warning
                        </div>
                        <div class="bg-[#FFF200] p-4 text-sm text-black font-medium leading-relaxed space-y-2">
                            <p class="font-bold underline">PENULISAN VIP</p>
                            <ul class="list-none space-y-1">
                                <li>VIP Bpk. Agus Budiman</li>
                                <li>VIP Ibu Atalia Dan Pasangan</li>
                            </ul>

                            <p class="font-bold underline mt-3">GUNAKAN DAN BUKAN &</p>
                            <ul class="list-none space-y-1">
                                <li>Bpk. Asep & Pasangan <span class="text-red-600 font-bold">(X)</span></li>
                                <li>Nazar Ilham & Pasangan <span class="text-red-600 font-bold">(X)</span></li>
                                <li>Bpk. Asep Dan Pasangan <span class="text-green-700 font-bold">(✔)</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-black p-1 shadow-lg">
                         <div class="bg-black text-white font-bold text-center py-1 uppercase text-sm tracking-wider">
                            Warning
                        </div>
                        <div class="bg-[#FFF200] p-4 text-sm text-black font-medium leading-relaxed">
                            <p>
                                Untuk List Di Samping, digunakan untuk tamu yang diberikan undangan fisik & Tamu VIP.
                            </p>
                            <p class="mt-2">
                                Untuk tamu yang diberikan undangan online, langsung saja di link ke-2 yang di share oleh admin.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>