<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Tamu') }}
        </h2>
    </x-slot>

    <!-- Gunakan bg-white agar terlihat seperti kertas dokumen -->
    <div class="py-8 bg-white min-h-screen font-sans">
        <div class="max-w-[98%] mx-auto px-2">
            
            <!-- FITUR EKSPOR & IMPOR (BARU) -->
            <div class="flex justify-end gap-3 mb-6 print:hidden">
                <!-- Tombol Download Template/Ekspor -->
                <a href="{{ route('guests.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Ekspor Excel
                </a>

                <!-- Tombol Trigger Modal Impor -->
                <x-primary-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'import-guest-modal')"
                    class="bg-blue-600 hover:bg-blue-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    Impor Excel
                </x-primary-button>
            </div>

            <!-- HEADER JUDUL -->
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-black text-black uppercase tracking-wide">DIGITAL GUESTBOOK BY BIRU ID</h1>
                <p class="text-sm md:text-base font-bold text-black mt-1">IG : BY BIRU ID - WA : 0895-2621-6334</p>
            </div>

            <!-- INFO PENGANTIN (DINAMIS DARI PROFILE) -->
            <div class="mb-6 max-w-4xl">
                <div class="grid grid-cols-[180px_10px_auto] gap-y-2 text-sm md:text-base font-bold text-black">
                    <!-- Baris 1: Nama Pengantin -->
                    <div>Nama Pengantin</div>
                    <div>:</div>
                    <!-- Mengambil Nama dari Profile User yang Login -->
                    <div class="uppercase">{{ Auth::user()->name }}</div>

                    <!-- Baris 2: Tanggal & Lokasi -->
                    <div>Tanggal Acara / Lokasi Acara</div>
                    <div>:</div>
                    <!-- Mengambil Data Tambahan -->
                    <div class="uppercase">
                        {{ Auth::user()->event_date ?? '1 November 2025' }} / {{ Auth::user()->event_location ?? 'CASA EUNOIA' }}
                    </div>
                </div>
            </div>

            <!-- LAYOUT UTAMA (TABEL + SIDEBAR) -->
            <div class="flex flex-col lg:flex-row gap-6">
                
                <!-- BAGIAN KIRI: TABEL DATA -->
                <div class="w-full lg:w-3/4">
                    
                    <!-- Pesan Sukses Impor -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="border border-black">
                        <table class="min-w-full border-collapse border border-black text-black">
                            <thead>
                                <!-- Header Baris 1 -->
                                <tr class="bg-black text-white text-sm uppercase font-bold tracking-wider">
                                    <th rowspan="2" class="py-2 px-2 text-left border border-gray-600 w-10">No.</th>
                                    <th rowspan="2" class="py-2 px-4 text-left border border-gray-600">Nama Tamu</th>
                                    <th colspan="2" class="py-1 px-2 text-center border border-gray-600 w-48">Undangan Yang Dikirim</th>
                                </tr>
                                <!-- Header Baris 2 (Sub-kolom) -->
                                <tr class="bg-black text-white text-sm uppercase font-bold">
                                    <th class="py-1 px-2 text-center border-r border-gray-600 w-24">Online</th>
                                    <th class="py-1 px-2 text-center w-24">Fisik</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-semibold text-black">
                                @forelse($guests as $index => $guest)
                                <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-300' }} border-b border-black">
                                    <td class="py-1 px-2 border-r border-black text-center">{{ $index + 1 }}</td>
                                    <td class="py-1 px-4 border-r border-black uppercase">{{ $guest->name }}</td>
                                    
                                    <!-- Checkbox Online -->
                                    <td class="py-1 px-2 border-r border-black text-center bg-transparent">
                                        <div class="flex justify-center items-center h-full">
                                            <input type="checkbox" disabled {{ $guest->is_online_invited ? 'checked' : '' }} 
                                                class="w-5 h-5 text-gray-800 border-2 border-gray-600 rounded bg-white focus:ring-0">
                                        </div>
                                    </td>
                                    
                                    <!-- Checkbox Fisik -->
                                    <td class="py-1 px-2 text-center bg-transparent">
                                        <div class="flex justify-center items-center h-full">
                                            <input type="checkbox" disabled {{ $guest->is_physical_invited ? 'checked' : '' }} 
                                                class="w-5 h-5 text-gray-800 border-2 border-gray-600 rounded bg-white focus:ring-0">
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-gray-300 border-b border-black">
                                    <td colspan="4" class="py-4 text-center italic">Belum ada data tamu.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BAGIAN KANAN: SIDEBAR WARNING -->
                <div class="w-full lg:w-1/4 space-y-0">
                    <!-- BOX 1: Penulisan VIP -->
                    <div class="border border-black shadow-md mb-4">
                        <div class="bg-black text-white font-bold text-center py-1 uppercase text-sm tracking-wider">
                            WARNING
                        </div>
                        <div class="bg-[#FFFF00] p-4 text-sm text-black font-semibold leading-relaxed">
                            <p class="font-bold mb-1">PENULISAN VIP</p>
                            <ul class="list-none space-y-1 mb-4">
                                <li>VIP Bpk. Agus Budiman</li>
                                <li>VIP Ibu Atalia Dan Pasangan</li>
                            </ul>

                            <p class="font-bold mb-1">GUNAKAN DAN BUKAN &</p>
                            <ul class="list-none space-y-1">
                                <li>Bpk. Asep & Pasangan</li>
                                <li>Nazar Ilham & Pasangan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- BOX 2: Instruksi -->
                    <div class="border border-black shadow-md">
                        <div class="bg-black text-white font-bold text-center py-1 uppercase text-sm tracking-wider">
                            WARNING
                        </div>
                        <div class="bg-[#FFFF00] p-4 text-sm text-black font-semibold leading-relaxed">
                            <p class="mb-2">
                                Untuk List Di Samping, digunakan untuk tamu yang diberikan undangan fisik & Tamu VIP.
                            </p>
                            <p>
                                Untuk tamu yang diberikan undangan online, langsung saja di link ke 2 yang di share oleh admin
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL IMPOR EXCEL -->
    <x-modal name="import-guest-modal" focusable>
        <form method="post" action="{{ route('guests.import') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Impor Data Tamu') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                {{ __('Silakan unggah file Excel (.xlsx) yang berisi daftar tamu. Pastikan format kolom sesuai (Nama, Pax, Undangan Online, Undangan Fisik).') }}
            </p>

            <!-- Input File -->
            <div class="mt-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Pilih File Excel</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                    id="file_input" type="file" name="file" accept=".xlsx, .xls, .csv" required>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                    {{ __('Impor Data') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

</x-app-layout>