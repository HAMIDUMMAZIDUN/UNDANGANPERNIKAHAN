@extends('layouts.app')

@section('page_title', 'souvenir')

@section('content')
<div class="bg-stone-100 min-h-screen font-sans p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">

    <!-- Start: Bagian Statistik -->
    <div class="bg-stone-800 text-white p-6 rounded-lg shadow-md text-center mb-8">
      <h2 class="text-5xl font-bold">0</h2>
      <p class="text-stone-300 text-lg uppercase tracking-wider mt-1">Souvenir Diambil</p>
    </div>
    <!-- End: Bagian Statistik -->

    <h1 class="text-2xl font-bold text-stone-800 mb-4">Penukaran Souvenir</h1>

    <!-- Start: Konten Utama -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <!-- Tombol Aksi -->
        <div class="flex items-center gap-3 mb-4">
          <button class="flex items-center gap-2 bg-stone-700 text-white py-2 px-4 rounded-lg hover:bg-stone-800 transition duration-300 text-sm">
            <!-- Ikon Export -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-2 0V4H4v2a1 1 0 11-2 0V3zm11.707 8.293a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L12.586 12H5a1 1 0 110-2h7.586l-1.293-1.293a1 1 0 011.414-1.414l3 3z" clip-rule="evenodd" />
            </svg>
            EXPORT
          </button>
          <button class="bg-stone-700 text-white py-2 px-4 rounded-lg hover:bg-stone-800 transition duration-300 text-sm">
            Scan Qr
          </button>
        </div>

        <hr class="border-stone-200">

        <!-- Area Data -->
        <div class="text-center py-20">
          <p class="text-red-500 font-bold text-lg">Tidak ada data!</p>
          {{-- Di sini Anda bisa looping data souvenir jika ada --}}
        </div>

      </div>

      <!-- Footer Kartu (Pagination) -->
      <div class="bg-stone-50 p-4 flex justify-center items-center">
        <nav class="flex items-center gap-2" aria-label="Pagination">
          <a href="#" class="p-2 rounded-md text-stone-600 bg-stone-200 hover:bg-stone-300">
            <span class="sr-only">Previous</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </a>
          <a href="#" aria-current="page" class="px-4 py-2 text-sm font-medium text-white bg-stone-700 rounded-md"> 1 </a>
          <a href="#" class="p-2 rounded-md text-stone-600 bg-stone-200 hover:bg-stone-300">
            <span class="sr-only">Next</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </a>
        </nav>
      </div>
    </div>
    <!-- End: Konten Utama -->

  </div>
</div>
@endsection
