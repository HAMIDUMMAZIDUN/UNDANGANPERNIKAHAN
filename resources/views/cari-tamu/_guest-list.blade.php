{{-- Tabel Hasil Pencarian --}}
<div class="bg-white shadow-md rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($guests as $guest)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $guest->affiliation }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($guest->check_in_time)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Hadir</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">Belum Hadir</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            @if(!$guest->check_in_time)
                                <button type="button" class="action-checkin inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700"
                                    data-guest-id="{{ $guest->id }}" data-guest-name="{{ $guest->name }}">
                                    Check-in
                                </button>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-16 text-slate-500">
                            @if(request('search'))
                                <p class="font-semibold">Tamu tidak ditemukan.</p>
                                <p class="text-sm mt-1">Tidak ada tamu yang cocok dengan kata kunci "{{ request('search') }}".</p>
                            @else
                                <p class="font-semibold">Silakan mulai pencarian.</p>
                                <p class="text-sm mt-1">Gunakan kotak di atas untuk mencari tamu.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
