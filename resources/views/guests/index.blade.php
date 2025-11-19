<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tambah Tamu Baru</h3>
                <form action="{{ route('guests.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Tamu" required
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </form>
            </div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Daftar Tamu Undangan</h3>
            <div class="overflow-x-auto relative">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">Nama</th>
                            <th scope="col" class="py-3 px-6">Kode Barcode</th>
                            <th scope="col" class="py-3 px-6">Status Kehadiran</th>
                            <th scope="col" class="py-3 px-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guests as $guest)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $guest->name }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $guest->barcode_code }}
                            </td>
                            <td class="py-4 px-6">
                                @if($guest->arrived_at)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                                        Hadir: {{ \Carbon\Carbon::parse($guest->arrived_at)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                        Belum Hadir
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('guests.barcode', $guest->id) }}" target="_blank" 
                                   class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    Lihat Barcode
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 px-6 text-center">Belum ada data tamu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>