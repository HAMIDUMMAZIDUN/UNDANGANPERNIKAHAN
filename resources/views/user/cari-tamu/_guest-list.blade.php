@if($guests->isEmpty() && !request('search'))
    <div class="text-center py-12">
        <p class="text-slate-500">Mulai ketik untuk mencari tamu.</p>
    </div>
@elseif($guests->isEmpty())
    <div class="text-center py-12">
        <p class="text-slate-500">Tidak ada tamu yang cocok dengan pencarian Anda.</p>
    </div>
@else
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-slate-200">
            @foreach ($guests as $guest)
                <li>
                    <div class="px-4 py-4 sm:px-6 flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-md font-medium text-amber-700 truncate">{{ $guest->name }}</p>
                            <p class="text-sm text-slate-500 truncate">{{ $guest->affiliation }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if ($guest->check_in_time)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Sudah Hadir
                                </span>
                            @else
                                <button 
                                    type="button" 
                                    class="action-checkin inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700"
                                    data-guest-id="{{ $guest->id }}"
                                    data-guest-name="{{ $guest->name }}"
                                    data-guest-affiliation="{{ $guest->affiliation ?? '' }}"> {{-- ATRIBUT BARU --}}
                                    Check-in
                                </button>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
