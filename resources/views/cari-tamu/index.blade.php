@extends('layouts.app')

@section('page_title', 'Cari Tamu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Cari Tamu Undangan</h1>
        <p class="text-slate-500 mt-1">Ketik nama tamu atau kategori untuk memulai pencarian.</p>
    </div>

    {{-- Form Pencarian --}}
    <div class="mb-5">
        <form action="{{ route('cari-tamu.index') }}" method="GET">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tamu atau kategori..." class="w-full pl-4 pr-10 py-3 border border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition">
                <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Hasil Pencarian --}}
    <div class="bg-white shadow-md rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($guests as $guest)
                        {{-- PERUBAHAN: Baris ini sekarang bisa diklik jika tamu belum hadir --}}
                        <tr 
                            @if(!$guest->check_in_time)
                                class="hover:bg-amber-50 cursor-pointer guest-row" 
                                data-guest-id="{{ $guest->id }}" 
                                data-guest-name="{{ $guest->name }}"
                            @endif
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $guest->affiliation }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($guest->check_in_time)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Sudah Hadir</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">Belum Hadir</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center px-6 py-16 text-slate-500">
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
        @if ($guests->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $guests->links() }}
        </div>
        @endif
    </div>
</div>

{{-- BAGIAN BARU: Modal untuk Check-in --}}
<div id="checkInModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="bg-amber-800 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h3 class="text-lg font-semibold">Tamu Terdaftar</h3>
            <button id="closeModalBtn" class="text-white hover:text-amber-200">&times;</button>
        </div>
        
        <div class="p-6">
            <form id="checkInForm" method="POST" action=""> {{-- Action akan diisi oleh JavaScript --}}
                @csrf
                <input type="hidden" name="search" value="{{ request('search') }}">
                
                <p id="guestName" class="text-xl font-bold text-slate-800 mb-2"></p>
                
                <label for="jumlah_tamu" class="block text-sm font-medium text-slate-700">Jumlah tamu</label>
                <div class="mt-1 flex items-stretch">
                    <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="1" min="1" class="block w-full rounded-l-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                    <button type="submit" class="inline-flex items-center rounded-r-md border border-l-0 border-amber-600 bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Check-in
                    </button>
                </div>
            </form>
            
            <div class="mt-4 text-right">
                <button id="cancelModalBtn" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 border border-transparent rounded-md hover:bg-slate-200">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('checkInModal');
    const guestRows = document.querySelectorAll('.guest-row');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const guestNameEl = document.getElementById('guestName');
    const checkInForm = document.getElementById('checkInForm');

    // Fungsi untuk menampilkan modal
    const showModal = (guestId, guestName) => {
        // 1. Isi nama tamu di modal
        guestNameEl.textContent = guestName;
        
        // 2. Set action form dengan URL yang benar
        // URL di-generate menggunakan template string dan helper `url()` Laravel
        let actionUrl = `{{ url('/guests') }}/${guestId}/checkin`;
        checkInForm.setAttribute('action', actionUrl);
        
        // 3. Tampilkan modal
        modal.classList.remove('hidden');
    };

    // Fungsi untuk menyembunyikan modal
    const hideModal = () => {
        modal.classList.add('hidden');
    };

    // Tambahkan event listener untuk setiap baris tamu yang bisa diklik
    guestRows.forEach(row => {
        row.addEventListener('click', function () {
            const guestId = this.dataset.guestId;
            const guestName = this.dataset.guestName;
            showModal(guestId, guestName);
        });
    });

    // Event listener untuk tombol close dan cancel
    closeModalBtn.addEventListener('click', hideModal);
    cancelModalBtn.addEventListener('click', hideModal);

    // Event listener untuk menutup modal saat klik di luar area modal
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            hideModal();
        }
    });
});
</script>
@endpush