@extends('layouts.app')

@section('page_title', 'cari-tamu')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-200">
  <button id="openModalBtn" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-blue-700 transition">
    Buka Pencarian Tamu
  </button>
</div>

<!-- Start: Modal Pencarian Tamu -->
<div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 hidden z-50">
  <div class="bg-stone-100 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
    
    <!-- Header Modal -->
    <div class="bg-stone-700 p-4 flex justify-between items-center">
      <h2 class="text-white text-xl font-semibold">Cari Tamu Terdaftar</h2>
      <button id="closeModalHeaderBtn" class="text-white hover:text-stone-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Body Modal -->
    <div class="p-6">
      <!-- Input Search -->
      <div class="relative mb-4">
        <input type="text" id="searchInput" placeholder="Ketik nama tamu..." class="w-full px-4 py-3 text-lg border-2 border-stone-300 rounded-full focus:outline-none focus:ring-2 focus:ring-stone-500 focus:border-transparent">
      </div>

      <!-- Status & Hasil Pencarian -->
      <div id="searchStatus" class="text-stone-500 text-sm mb-4 hidden">Mencari data....</div>
      
      <div id="resultsContainer" class="space-y-3 max-h-80 overflow-y-auto">
        {{-- Hasil pencarian akan ditampilkan di sini oleh JavaScript --}}
      </div>
    </div>

    <!-- Footer Modal -->
    <div class="bg-stone-200 p-4 flex justify-end">
      <button id="closeModalFooterBtn" class="bg-gray-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-700 transition">
        Close
      </button>
    </div>

  </div>
</div>
<!-- End: Modal Pencarian Tamu -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil elemen-elemen modal
    const modal = document.getElementById('searchModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalHeaderBtn = document.getElementById('closeModalHeaderBtn');
    const closeModalFooterBtn = document.getElementById('closeModalFooterBtn');
    
    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('resultsContainer');
    const searchStatus = document.getElementById('searchStatus');

    // Fungsi untuk membuka modal
    const openModal = () => modal.classList.remove('hidden');
    // Fungsi untuk menutup modal
    const closeModal = () => modal.classList.add('hidden');

    // Event listener untuk tombol-tombol
    openModalBtn.addEventListener('click', openModal);
    closeModalHeaderBtn.addEventListener('click', closeModal);
    closeModalFooterBtn.addEventListener('click', closeModal);
    // Tutup modal jika klik di luar area konten
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    let searchTimeout;

    searchInput.addEventListener('input', function () {
        const query = this.value;

        // Hapus timeout sebelumnya
        clearTimeout(searchTimeout);
        resultsContainer.innerHTML = ''; // Kosongkan hasil

        if (query.length < 2) {
            searchStatus.classList.add('hidden');
            return; // Jangan cari jika query terlalu pendek
        }

        searchStatus.textContent = 'Mencari data....';
        searchStatus.classList.remove('hidden');

        // Atur timeout baru
        searchTimeout = setTimeout(() => {
            fetch(`/api/tamu/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchStatus.classList.add('hidden');
                    if (data.length > 0) {
                        data.forEach(guest => {
                            const guestElement = document.createElement('div');
                            guestElement.className = 'border-b border-stone-200 pb-3';
                            guestElement.innerHTML = `
                                <p class="font-bold text-stone-800">${guest.name}</p>
                                <p class="text-sm text-stone-600">${guest.address}</p>
                            `;
                            resultsContainer.appendChild(guestElement);
                        });
                    } else {
                        resultsContainer.innerHTML = '<p class="text-stone-500 text-center">Tamu tidak ditemukan.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchStatus.textContent = 'Gagal memuat data.';
                });
        }, 500); // Tunggu 500ms setelah user berhenti mengetik
    });
});
</script>
@endpush
