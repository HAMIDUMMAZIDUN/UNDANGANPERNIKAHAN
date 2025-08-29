@extends('user.layouts.app')

@section('page_title', 'Cari Tamu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <h1 class="text-3xl font-bold text-slate-800">Cari Tamu Undangan</h1>
            
            <a href="{{ route('manual.index') }}" class="inline-flex items-center rounded-md border border-transparent bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Check-in Manual
            </a>
        </div>
        <p class="text-slate-500 mt-2">Ketik nama tamu atau kategori untuk memulai pencarian.</p>
    </div>

    {{-- Form Pencarian --}}
    <div class="mb-5">
        <form id="searchForm" onsubmit="return false;">
            <div class="relative">
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Cari nama tamu atau kategori..." class="w-full pl-4 pr-10 py-3 border border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                </div>
            </div>
        </form>
    </div>

    {{-- Wadah untuk menampilkan hasil dinamis --}}
    <div id="resultsContainer">
        @include('user.cari-tamu._guest-list', ['guests' => $guests])
    </div>
</div>

{{-- Modal Check-in --}}
@include('user.cari-tamu._checkin-modal')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('resultsContainer');
    const modal = document.getElementById('checkInModal');
    const checkInForm = document.getElementById('checkInForm');
    const guestNameEl = document.getElementById('guestName');
    const guestAffiliationEl = document.getElementById('guestAffiliation'); // DEFINISIKAN ELEMEN KATEGORI
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    let debounceTimer;

    // --- Fungsi-Fungsi ---

    const debounce = (func, delay) => {
        return function(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const performSearch = async () => {
        const query = searchInput.value;
        const url = `{{ route('api.tamu.search') }}?search=${encodeURIComponent(query)}`;
        try {
            const response = await fetch(url);
            resultsContainer.innerHTML = await response.text();
        } catch (error) {
            console.error('Error fetching search results:', error);
            resultsContainer.innerHTML = '<p class="text-center text-red-500">Terjadi kesalahan saat mencari.</p>';
        }
    };

    // FUNGSI DIPERBARUI UNTUK MENERIMA KATEGORI
    const showModal = (guestId, guestName, guestAffiliation) => {
        if (!modal || !checkInForm || !guestNameEl || !guestAffiliationEl) return;
        guestNameEl.textContent = guestName;
        guestAffiliationEl.textContent = guestAffiliation; // TAMPILKAN KATEGORI
        let actionUrl = `/guests/${guestId}/checkin`;
        checkInForm.setAttribute('action', actionUrl);
        modal.classList.remove('hidden');
    };

    const hideModal = () => {
        if (modal) modal.classList.add('hidden');
    };

    const handleCheckinSubmit = async (event) => {
        event.preventDefault();
        const form = event.target;
        const url = form.getAttribute('action');
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        
        submitButton.disabled = true;
        submitButton.textContent = 'Memproses...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });
            const result = await response.json();
            if (response.ok) {
                hideModal();
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: result.message, showConfirmButton: false, timer: 3000 });
                performSearch();
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: result.message });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Tidak dapat terhubung ke server.' });
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Check-in';
        }
    };

    // --- Pemasangan Event Listener ---

    searchInput.addEventListener('keyup', debounce(performSearch, 300));

    // LISTENER DIPERBARUI UNTUK MENGAMBIL DATA KATEGORI
    document.body.addEventListener('click', function(event) {
        const checkinButton = event.target.closest('.action-checkin');
        if (checkinButton) {
            const guestId = checkinButton.dataset.guestId;
            const guestName = checkinButton.dataset.guestName;
            const guestAffiliation = checkinButton.dataset.guestAffiliation; // AMBIL DATA KATEGORI
            if (guestId && guestName) {
                showModal(guestId, guestName, guestAffiliation); // KIRIM KATEGORI KE FUNGSI
            }
        }
    });

    if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', hideModal);
    if (modal) modal.addEventListener('click', (event) => (event.target === modal) && hideModal());

    if (checkInForm) {
        checkInForm.addEventListener('submit', handleCheckinSubmit);
    }
});
</script>
@endpush
