@extends('layouts.app')

@section('page_title', 'Data Kehadiran')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-200">
  <button id="openModalBtn" class="bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-green-700 transition">
    Buka Form Check-in
  </button>
</div>

<!-- Start: Modal Check-in Tamu -->
<div id="checkinModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 hidden z-50">
  <div class="bg-stone-100 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
    
    <!-- Header Modal -->
    <div class="bg-stone-50 p-4 flex justify-between items-center border-b border-stone-200">
      <h2 class="text-stone-800 text-xl font-bold">Check-in Tamu</h2>
      <button id="closeModalBtn" class="text-stone-500 hover:text-stone-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Body Modal (Form) -->
    <form action="{{ route('manual.store') }}" method="POST">
      @csrf
      <div class="p-6 space-y-5">
        <div>
          <label for="nama" class="block text-sm font-medium text-stone-700 mb-1">Nama</label>
          <input type="text" name="nama" id="nama" placeholder="Nama Tamu" class="w-full px-4 py-3 text-stone-700 border border-stone-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-500" required>
        </div>
        
        <div>
          <label for="alamat" class="block text-sm font-medium text-stone-700 mb-1">Alamat</label>
          <input type="text" name="alamat" id="alamat" placeholder="Alamat Tamu" class="w-full px-4 py-3 text-stone-700 border border-stone-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-500" required>
        </div>

        <div>
          <label for="jumlah_tamu" class="block text-sm font-medium text-stone-700 mb-1">Jumlah Tamu</label>
          <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="1" min="1" class="w-full px-4 py-3 text-stone-700 border border-stone-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-500" required>
        </div>

        <div class="flex items-center">
          <input id="is_vip" name="is_vip" type="checkbox" value="1" class="h-4 w-4 text-stone-600 border-gray-300 rounded focus:ring-stone-500">
          <label for="is_vip" class="ml-2 block text-sm text-stone-800">Tamu VIP</label>
        </div>

        <div class="pt-4">
           <button type="submit" class="w-full bg-stone-700 text-white font-bold py-3 px-6 rounded-xl shadow-md hover:bg-stone-800 transition duration-300">
             Save
           </button>
        </div>
      </div>
    </form>

  </div>
</div>
<!-- End: Modal Check-in Tamu -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('checkinModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    const openModal = () => modal.classList.remove('hidden');
    const closeModal = () => modal.classList.add('hidden');

    openModalBtn.addEventListener('click', openModal);
    closeModalBtn.addEventListener('click', closeModal);
    
    // Tutup modal jika klik di luar area konten
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
});
</script>
@endpush