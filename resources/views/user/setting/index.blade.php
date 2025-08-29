@extends('user.layouts.app')

@section('page_title', 'Setting Event')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Setting Event</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola semua event yang telah Anda buat.</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="space-y-6">
                    @forelse ($events as $event)
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <div class="flex flex-col sm:flex-row items-start gap-5">
                            <div class="w-full sm:w-1/3 flex-shrink-0">
                                <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/400x400/f59e0b/334155?text=Event' }}" alt="Foto Event" class="rounded-lg object-cover w-full h-auto aspect-square">
                            </div>
                            
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-2xl font-bold text-slate-900">{{ $event->name }}</h3>
                                            <div class="bg-slate-800 text-white rounded-full flex items-center justify-center h-6 w-6" title="Event Aktif"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg></div>
                                        </div>
                                        <p class="text-sm text-slate-600">Tanggal: <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($event->date)->isoFormat('D MMMM YYYY') }}</span></p>
                                        <p class="text-sm text-slate-600">Undangan: <span class="font-semibold text-slate-800">{{ $event->guests_count }}</span></p>
                                    </div>
                                    
                                    {{-- Tombol Aksi dengan Dropdown --}}
                                    <div class="relative inline-block text-left">
                                        <button data-dropdown-id="event-{{ $event->id }}" class="event-dropdown-btn text-slate-500 hover:text-slate-800 p-2 rounded-full hover:bg-slate-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </button>
                                        <div id="dropdown-event-{{ $event->id }}" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-slate-100 z-50 hidden origin-top-right">
                                            <div class="py-1">
                                                {{-- Link untuk Edit Event --}}
                                                <a href="{{ route('setting.events.edit', $event) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                                    Edit Event
                                                </a>

                                                {{-- Link untuk Galeri --}}
                                                <a href="{{ route('setting.events.gallery', $event) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                                    Galeri Foto
                                                </a>

                                                {{-- Link untuk Layar Sapa --}}
                                                @if ($event->uuid)
                                                    <a href="{{ route('sapa.index', $event->uuid) }}" target="_blank" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                                        Layar Sapa
                                                    </a>
                                                @else
                                                    <span class="block px-4 py-2 text-sm text-slate-400 cursor-not-allowed" title="Event ini tidak memiliki UUID untuk ditampilkan.">
                                                        Layar Sapa (Error)
                                                    </span>
                                                @endif

                                                {{-- Link untuk QR Check-in --}}
                                                <a href="{{ route('check-in.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                                    QR Check-in
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                     <a href="{{ route('undangan.public', ['event' => $event]) }}" target="_blank" class="text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100" role="menuitem">
                                        Undangan Umum (Buat ke Group Whatsapp)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-500 py-16">
                        <p class="font-semibold">Anda belum memiliki event.</p>
                        <p class="text-sm mt-1">Silakan klik tombol "+ Buat Event Baru" untuk memulai.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function toggleEventDropdown(id) {
            document.querySelectorAll('[id^="dropdown-event-"]').forEach(el => {
                if (el.id !== `dropdown-${id}`) {
                    el.classList.add('hidden');
                }
            });
            const dropdown = document.getElementById('dropdown-' + id);
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        const dropdownButtons = document.querySelectorAll('.event-dropdown-btn');
        dropdownButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation();
                const eventId = this.dataset.dropdownId;
                toggleEventDropdown(eventId);
            });
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.event-dropdown-btn') && !e.target.closest('[id^="dropdown-event-"]')) {
                document.querySelectorAll('[id^="dropdown-event-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
    });
</script>
@endpush
