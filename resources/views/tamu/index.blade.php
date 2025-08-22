@extends('layouts.app')

@section('page_title', 'Tamu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Ages & April</h1>
        <p class="text-slate-500 mt-1">Minggu, 21 September 2025</p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-5">
        <h2 class="text-xl font-semibold text-slate-700">Data Tamu ({{ $guests->count() }} Undangan)</h2>
        <div class="flex items-center gap-2">
            <button onclick="openImportModal()" class="inline-flex items-center justify-center bg-white text-slate-700 px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import Tamu
            </button>
            <a href="{{ route('tamu.create') }}" class="inline-flex items-center justify-center bg-amber-500 text-slate-900 px-4 py-2 rounded-lg shadow-md hover:bg-amber-600 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                + Tambah Tamu
            </a>
        </div>
    </div>

    <div class="mb-5">
        <input type="text" placeholder="Cari nama tamu atau kategori..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition">
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($guests as $guest)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $guest->affiliation }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <div class="relative inline-block text-left">
                                <button data-dropdown-id="{{ $guest->id }}" class="aksi-dropdown-btn p-2 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                </button>
                                <div id="dropdown-{{ $guest->id }}" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-slate-100 z-50 hidden origin-top-right">
                                    <div class="py-1">
                                        <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg><span>Undangan</span></a>
                                        <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 4.186 2.25 2.25 0 000-4.186zM12 10.907a2.25 2.25 0 100 4.186 2.25 2.25 0 000-4.186zM16.783 10.907a2.25 2.25 0 100 4.186 2.25 2.25 0 000-4.186z" /></svg><span>Share Undangan</span></a>
                                        <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a2.25 2.25 0 01-2.25 2.25h-1.5a2.25 2.25 0 01-2.25-2.25v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" /></svg><span>Copy Message</span></a>
                                        <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z" /></svg><span>Download Qrcode</span></a>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <a href="{{ route('tamu.edit', $guest->id) }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.87l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg><span>Edit</span></a>
                                        <form method="POST" action="{{ route('tamu.destroy', $guest->id) }}" onsubmit="return confirm('Yakin ingin menghapus tamu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-medium"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg><span>Delete</span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-16 text-slate-500">
                            <p class="font-semibold">Belum ada data tamu.</p>
                            <p class="text-sm mt-1">Silakan klik tombol "+ Tambah Tamu" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="importModal" class="fixed inset-0 bg-slate-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-slate-900 mt-4">Import Data Tamu</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-slate-500">
                    Upload file Excel (.xlsx) sesuai dengan template yang disediakan untuk menambahkan banyak tamu sekaligus.
                </p>
            </div>
            <div class="my-4">
                <a href="#" class="text-sm font-medium text-amber-600 hover:text-amber-500">
                    Download Template Excel
                </a>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <input type="file" name="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"/>
                </div>
                <div class="items-center px-4 py-3 mt-6">
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-900 text-base font-medium rounded-md w-full shadow-sm hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        Import Sekarang
                    </button>
                </div>
            </form>
            <button onclick="closeImportModal()" class="mt-2 text-sm text-slate-500 hover:text-slate-700">
                Batal
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function toggleDropdown(id) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== `dropdown-${id}`) {
                    el.classList.add('hidden');
                }
            });
            const dropdown = document.getElementById('dropdown-' + id);
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }
        const dropdownButtons = document.querySelectorAll('.aksi-dropdown-btn');
        dropdownButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation();
                const guestId = this.dataset.dropdownId;
                toggleDropdown(guestId);
            });
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.aksi-dropdown-btn') && !e.target.closest('[id^="dropdown-"]')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
    });
    const importModal = document.getElementById('importModal');
    function openImportModal() {
        importModal.classList.remove('hidden');
    }
    function closeImportModal() {
        importModal.classList.add('hidden');
    }
    window.addEventListener('click', function(event) {
        if (event.target == importModal) {
            closeImportModal();
        }
    });
</script>
@endpush
