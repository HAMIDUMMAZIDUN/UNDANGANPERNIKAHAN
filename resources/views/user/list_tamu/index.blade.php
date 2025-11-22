<!-- MENAMBAHKAN TITLE DINAMIS DI SINI -->
<x-app-layout title="List Tamu">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Tamu') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen font-sans">
        <div class="max-w-[98%] mx-auto px-2">
            
            <!-- 1. HEADER JUDUL (Posisi Paling Atas) -->
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-black text-black uppercase tracking-wide">DIGITAL GUESTBOOK BY BIRU ID</h1>
                <p class="text-sm md:text-base font-bold text-black mt-1">IG : BY BIRU ID - WA : 0895-2621-6334</p>
            </div>

            <!-- 2. INFO PENGANTIN -->
            <div class="mb-6 max-w-4xl">
                <div class="grid grid-cols-[180px_10px_auto] gap-y-2 text-sm md:text-base font-bold text-black">
                    <div>Nama Pengantin</div>
                    <div>:</div>
                    <div class="uppercase">{{ Auth::user()->name }}</div>

                    <div>Tanggal Acara / Lokasi Acara</div>
                    <div>:</div>
                    <div class="uppercase">
                        {{ Auth::user()->event_date ?? '1 November 2025' }} / {{ Auth::user()->event_location ?? 'CASA EUNOIA' }}
                    </div>
                </div>
            </div>

            <!-- 3. LAYOUT UTAMA (KONTEN KIRI & SIDEBAR KANAN) -->
            <div class="flex flex-col lg:flex-row gap-6">
                
                <!-- BAGIAN KIRI: TOOLS & TABEL DATA -->
                <div class="w-full lg:w-3/4">
                    
                    <!-- A. TOOLBAR (SEARCH, SCAN, DELETE, IMPORT/EXPORT) -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-3 mb-4 print:hidden bg-gray-50 p-3 rounded-lg border border-gray-200">
                        
                        <!-- Kiri: Search & Bulk Delete -->
                        <div class="w-full md:w-7/12 flex gap-2 items-center">
                            <!-- Form Pencarian -->
                            <form method="GET" action="{{ route('guests.index') }}" class="flex-grow flex gap-2">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="Scan Barcode / Cari Nama..." autofocus autocomplete="off">
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2">Cari</button>
                                
                                @if(request('search'))
                                    <a href="{{ route('guests.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-xs px-3 py-2 flex items-center">Reset</a>
                                @endif
                            </form>

                            <!-- Tombol Hapus Massal (Muncul jika checkbox dipilih) -->
                            <button id="bulk-delete-btn" onclick="confirmBulkDelete()" class="hidden items-center px-3 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 focus:outline-none transition ease-in-out duration-150 whitespace-nowrap shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                (<span id="selected-count">0</span>)
                            </button>
                        </div>

                        <!-- Kanan: Ekspor & Impor -->
                        <div class="flex gap-2">
                            <a href="{{ route('guests.export') }}" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Ekspor
                            </a>

                            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'import-guest-modal')" class="bg-blue-600 hover:bg-blue-700 text-xs py-2 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                Impor
                            </x-primary-button>
                        </div>
                    </div>
                    
                    <!-- PESAN SUKSES -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- B. TABEL DATA TAMU -->
                    <div class="border border-black">
                        <table class="min-w-full border-collapse border border-black text-black">
                            <thead>
                                <tr class="bg-black text-white text-sm uppercase font-bold tracking-wider">
                                    <!-- CHECKBOX SELECT ALL -->
                                    <th rowspan="2" class="py-2 px-2 text-center border border-gray-600 w-10">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer">
                                    </th>
                                    <th rowspan="2" class="py-2 px-2 text-left border border-gray-600 w-10">No.</th>
                                    <th rowspan="2" class="py-2 px-4 text-left border border-gray-600">Nama Tamu</th>
                                    <th colspan="2" class="py-1 px-2 text-center border border-gray-600 w-48">Undangan Yang Dikirim</th>
                                    <th rowspan="2" class="py-2 px-2 text-center border border-gray-600 w-24">Aksi</th>
                                </tr>
                                <tr class="bg-black text-white text-sm uppercase font-bold">
                                    <th class="py-1 px-2 text-center border-r border-gray-600 w-24">Online</th>
                                    <th class="py-1 px-2 text-center w-24">Fisik</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-semibold text-black">
                                @forelse($guests as $index => $guest)
                                <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-300' }} border-b border-black hover:bg-blue-50 transition">
                                    
                                    <!-- CHECKBOX PER ROW -->
                                    <td class="py-1 px-2 border-r border-black text-center">
                                        <input type="checkbox" name="ids" class="checkbox-item rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" value="{{ $guest->id }}">
                                    </td>

                                    <td class="py-1 px-2 border-r border-black text-center">{{ $index + 1 }}</td>
                                    
                                    <!-- Nama Tamu -->
                                    <td class="py-1 px-4 border-r border-black uppercase">
                                        @if(request('search'))
                                            {!! preg_replace('/(' . preg_quote(request('search')) . ')/i', '<span class="bg-yellow-200">$1</span>', $guest->name) !!}
                                        @else
                                            {{ $guest->name }}
                                        @endif
                                    </td>
                                    
                                    <!-- Checkbox Online (Display Only) -->
                                    <td class="py-1 px-2 border-r border-black text-center bg-transparent">
                                        <div class="flex justify-center items-center h-full">
                                            <input type="checkbox" disabled {{ $guest->is_online_invited ? 'checked' : '' }} class="w-5 h-5 text-gray-800 border-2 border-gray-600 rounded bg-white focus:ring-0">
                                        </div>
                                    </td>
                                    
                                    <!-- Checkbox Fisik (Display Only) -->
                                    <td class="py-1 px-2 border-r border-black text-center bg-transparent">
                                        <div class="flex justify-center items-center h-full">
                                            <input type="checkbox" disabled {{ $guest->is_physical_invited ? 'checked' : '' }} class="w-5 h-5 text-gray-800 border-2 border-gray-600 rounded bg-white focus:ring-0">
                                        </div>
                                    </td>

                                    <!-- KOLOM AKSI -->
                                    <td class="py-1 px-2 text-center bg-transparent">
                                        <div class="flex justify-center items-center gap-2 h-full" x-data>
                                            <button x-on:click.prevent="$dispatch('open-modal', 'edit-guest-{{ $guest->id }}')" class="text-blue-600 hover:text-blue-800" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <button x-on:click.prevent="$dispatch('open-modal', 'delete-guest-{{ $guest->id }}')" class="text-red-600 hover:text-red-800" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                        <!-- MODAL EDIT (Disertakan untuk setiap baris) -->
                                        <x-modal name="edit-guest-{{ $guest->id }}" focusable>
                                            <form method="post" action="{{ route('guests.update', $guest->id) }}" class="p-6 text-left">
                                                @csrf @method('PUT')
                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Data Tamu</h2>
                                                <div class="mb-4">
                                                    <x-input-label for="name_{{ $guest->id }}" value="Nama Tamu" />
                                                    <x-text-input id="name_{{ $guest->id }}" name="name" type="text" class="mt-1 block w-full" value="{{ $guest->name }}" required />
                                                </div>
                                                <div class="mb-4">
                                                    <x-input-label for="pax_{{ $guest->id }}" value="Jumlah Pax (Orang)" />
                                                    <x-text-input id="pax_{{ $guest->id }}" name="pax" type="number" class="mt-1 block w-full" value="{{ $guest->pax }}" required />
                                                </div>
                                                <div class="flex gap-4 mb-4">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="is_online_invited" value="1" {{ $guest->is_online_invited ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                        <span class="ml-2 text-sm text-gray-600">Undangan Online</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="is_physical_invited" value="1" {{ $guest->is_physical_invited ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                        <span class="ml-2 text-sm text-gray-600">Undangan Fisik</span>
                                                    </label>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <x-primary-button>Simpan</x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <!-- MODAL HAPUS -->
                                        <x-modal name="delete-guest-{{ $guest->id }}" focusable>
                                            <form method="post" action="{{ route('guests.destroy', $guest->id) }}" class="p-6 text-left">
                                                @csrf @method('DELETE')
                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Apakah Anda yakin?</h2>
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Data tamu <strong>{{ $guest->name }}</strong> akan dihapus permanen.</p>
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <x-danger-button>Hapus Tamu</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-gray-300 border-b border-black">
                                    <td colspan="6" class="py-4 text-center italic">
                                        @if(request('search'))
                                            Tamu "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Belum ada data tamu.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BAGIAN KANAN: SIDEBAR WARNING -->
                <div class="w-full lg:w-1/4 space-y-0">
                    <div class="border border-black shadow-md mb-4">
                        <div class="bg-black text-white font-bold text-center py-1 uppercase text-sm tracking-wider">WARNING</div>
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
                    <div class="border border-black shadow-md">
                        <div class="bg-black text-white font-bold text-center py-1 uppercase text-sm tracking-wider">WARNING</div>
                        <div class="bg-[#FFFF00] p-4 text-sm text-black font-semibold leading-relaxed">
                            <p class="mb-2">Untuk List Di Samping, digunakan untuk tamu yang diberikan undangan fisik & Tamu VIP.</p>
                            <p>Untuk tamu yang diberikan undangan online, langsung saja di link ke 2 yang di share oleh admin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM KHUSUS BULK DELETE (Hidden Form - Dikirim via JS) -->
    <form id="bulk-delete-form" action="{{ route('guests.bulk_destroy') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulk-delete-ids">
    </form>

    <!-- MODAL IMPOR EXCEL -->
    <x-modal name="import-guest-modal" focusable>
        <form method="post" action="{{ route('guests.import') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Impor Data Tamu') }}</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Silakan unggah file Excel (.xlsx) yang berisi daftar tamu.') }}</p>
            <div class="mt-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Pilih File Excel</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file" name="file" accept=".xlsx, .xls, .csv" required>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Batal') }}</x-secondary-button>
                <x-primary-button class="bg-blue-600 hover:bg-blue-700">{{ __('Impor Data') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- SCRIPT UNTUK CHECKBOX & HAPUS MASSAL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.checkbox-item');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const selectedCount = document.getElementById('selected-count');

            // 1. Toggle Select All
            if(selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                    });
                    toggleBulkDeleteBtn();
                });
            }

            // 2. Toggle Individual Checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkDeleteBtn);
            });

            // 3. Tampilkan/Sembunyikan Tombol Hapus Massal
            function toggleBulkDeleteBtn() {
                const checkedCount = document.querySelectorAll('.checkbox-item:checked').length;
                selectedCount.innerText = checkedCount;
                
                if (checkedCount > 0) {
                    bulkDeleteBtn.classList.remove('hidden');
                    bulkDeleteBtn.classList.add('inline-flex');
                } else {
                    bulkDeleteBtn.classList.add('hidden');
                    bulkDeleteBtn.classList.remove('inline-flex');
                }
            }
        });

        // 4. Konfirmasi & Submit Hapus Massal
        function confirmBulkDelete() {
            if(confirm('Yakin ingin menghapus data yang dipilih?')) {
                const selectedIds = Array.from(document.querySelectorAll('.checkbox-item:checked'))
                    .map(cb => cb.value);
                
                if (selectedIds.length > 0) {
                    // Masukkan ID ke input hidden di form bulk delete
                    document.getElementById('bulk-delete-ids').value = selectedIds.join(',');
                    // Submit form
                    document.getElementById('bulk-delete-form').submit();
                }
            }
        }
    </script>

</x-app-layout>