@extends('layouts.app')

@section('page_title', 'Data Tamu')

@section('content')
<div class="bg-stone-100 min-h-screen font-sans p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">

    <!-- Start: Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-4xl font-bold text-stone-800">Settings</h1>
      <a href="#" class="bg-stone-700 text-white font-semibold py-2 px-6 rounded-full shadow-md hover:bg-stone-800 transition duration-300">
        Akun Saya
      </a>
    </div>
    <!-- End: Header -->

    <!-- Start: Konten Utama -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <p class="text-stone-600 mb-4 font-medium">Jumlah Event: <span class="font-bold text-stone-800">1</span></p>
        
        <!-- Input Pencarian -->
        <div class="relative mb-6">
          <input type="text" placeholder="search event..." class="w-full pl-4 pr-10 py-3 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-500">
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <!-- Ikon Search -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-stone-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <!-- Start: Event Card -->
        <div class="bg-stone-50 p-4 rounded-lg border border-stone-200">
          <div class="flex flex-col sm:flex-row items-start gap-5">
            <!-- Gambar Event -->
            <div class="w-full sm:w-1/3 flex-shrink-0">
              <img src="https://placehold.co/400x400/e2e8f0/64748b?text=Foto+Event" alt="Foto Pernikahan Ages & April" class="rounded-lg object-cover w-full h-auto">
            </div>
            
            <!-- Detail Event -->
            <div class="flex-grow">
              <div class="flex justify-between items-start">
                <div>
                  <div class="flex items-center gap-2 mb-2">
                    <h3 class="text-2xl font-bold text-stone-900">Ages & April</h3>
                    <div class="bg-stone-800 text-white rounded-full flex items-center justify-center h-6 w-6">
                      <!-- Ikon Centang -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                  <p class="text-sm text-stone-600">User: <span class="font-semibold text-stone-800">April & Ages</span></p>
                  <p class="text-sm text-stone-600">Tanggal: <span class="font-semibold text-stone-800">21/09/2025</span></p>
                  <p class="text-sm text-stone-600">Undangan: <span class="font-semibold text-stone-800">580</span></p>
                </div>
                <button class="text-stone-500 hover:text-stone-800">
                  <!-- Ikon Settings -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>
              <div class="mt-4">
                <a href="https://menujuacara.id/april-ages/" target="_blank" class="text-sm text-blue-600 hover:underline break-all">
                  https://menujuacara.id/april-ages/
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
