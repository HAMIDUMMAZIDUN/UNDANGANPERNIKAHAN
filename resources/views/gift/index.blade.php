@extends('layouts.app')

@section('page_title', 'gift')

@section('content')
<div class="bg-stone-100 min-h-screen font-sans p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">

    <!-- Start: Bagian Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
      <!-- Card Data Pengirim -->
      <div class="bg-stone-700 text-white p-6 rounded-lg shadow-md text-center">
        <h2 class="text-2xl font-bold">Data Pengirim</h2>
        <p class="text-stone-300 uppercase tracking-wider">Kado Digital</p>
      </div>
      <!-- Card Jml Pengirim -->
      <div class="bg-stone-700 text-white p-6 rounded-lg shadow-md text-center">
        <h2 class="text-5xl font-bold">0</h2>
        <p class="text-stone-300 uppercase tracking-wider">Jml Pengirim</p>
      </div>
    </div>
    <!-- End: Bagian Statistik -->

    <!-- Start: Konten Utama -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
          <!-- Tombol Aksi -->
          <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 bg-stone-700 text-white py-2 px-4 rounded-lg hover:bg-stone-800 transition duration-300 text-sm">
              <!-- Ikon PDF -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6zm3 4a1 1 0 011 1v1h1a1 1 0 110 2H9v1a1 1 0 11-2 0V9a1 1 0 011-1zm5 2a1 1 0 100-2h-1a1 1 0 100 2h1z" clip-rule="evenodd" />
              </svg>
              Pdf
            </button>
          </div>
          <!-- Input Pencarian -->
          <div class="relative w-full sm:w-auto">
            <input type="text" placeholder="search..." class="w-full sm:w-64 pl-4 pr-10 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-500">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <!-- Ikon Search -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
        </div>

        <hr class="border-stone-200">

        <!-- Area Data -->
        <div class="text-center py-20">
          <p class="text-stone-500 text-lg">Tidak ada data!</p>
          {{-- Di sini Anda bisa looping data pengirim jika ada --}}
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
