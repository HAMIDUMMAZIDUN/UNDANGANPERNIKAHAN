@extends('layouts.app')

@section('page_title', 'rsvp')

@section('content')
<div class="bg-stone-100 min-h-screen font-sans p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-stone-800 mb-6">Ucapan & Doa</h1>

    <!-- Start: Bagian Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
      <div class="bg-stone-700 text-white p-4 rounded-lg shadow-md text-center">
        <h2 class="text-3xl font-bold">10</h2>
        <p class="text-stone-300 text-sm uppercase tracking-wider">Komentar</p>
      </div>
      <div class="bg-stone-700 text-white p-4 rounded-lg shadow-md text-center">
        <h2 class="text-3xl font-bold">8</h2>
        <p class="text-stone-300 text-sm uppercase tracking-wider">Hadir</p>
      </div>
      <div class="bg-stone-700 text-white p-4 rounded-lg shadow-md text-center">
        <h2 class="text-3xl font-bold">1</h2>
        <p class="text-stone-300 text-sm uppercase tracking-wider">Tidak Hadir</p>
      </div>
      <div class="bg-stone-700 text-white p-4 rounded-lg shadow-md text-center">
        <h2 class="text-3xl font-bold">1</h2>
        <p class="text-stone-300 text-sm uppercase tracking-wider">Masih Ragu</p>
      </div>
    </div>
    <!-- End: Bagian Statistik -->

    <!-- Start: Filter dan Aksi -->
    <div class="flex justify-between items-center mb-6">
      <div class="relative">
        <select class="appearance-none bg-white border border-stone-300 text-stone-700 py-2 pl-4 pr-8 rounded-lg focus:outline-none focus:bg-white focus:border-stone-500">
          <option>Semua Ucapan</option>
          <option>Hadir</option>
          <option>Tidak Hadir</option>
          <option>Masih Ragu</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-stone-700">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>
      <button class="bg-stone-700 text-white py-2 px-6 rounded-lg shadow-md hover:bg-stone-800 transition duration-300">
        Export
      </button>
    </div>
    <!-- End: Filter dan Aksi -->

    <!-- Start: Daftar Komentar -->
    <div class="space-y-4">
      {{-- Contoh Data Komentar 1 --}}
      <div class="bg-white p-4 rounded-lg shadow-sm flex items-start gap-4">
        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-xl">G</div>
        <div class="flex-grow">
          <div class="flex justify-between items-center">
            <div>
              <span class="font-semibold text-stone-800">Gest</span>
              <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-2">Hadir</span>
            </div>
            <div class="flex items-center gap-3 text-stone-400">
              <span class="text-xs">21 Agustus 2025 11:40:38</span>
              <button class="hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
              </button>
            </div>
          </div>
          <p class="text-stone-600 mt-1 text-sm">kelas humem</p>
        </div>
      </div>

      {{-- Contoh Data Komentar 2 --}}
      <div class="bg-white p-4 rounded-lg shadow-sm flex items-start gap-4">
        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl">I</div>
        <div class="flex-grow">
          <div class="flex justify-between items-center">
            <div>
              <span class="font-semibold text-stone-800">Ibenk</span>
              <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-2">Hadir</span>
            </div>
            <div class="flex items-center gap-3 text-stone-400">
              <span class="text-xs">21 Agustus 2025 07:42:11</span>
              <button class="hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
              </button>
            </div>
          </div>
          <p class="text-stone-600 mt-1 text-sm">Selamat teteh.</p>
        </div>
      </div>

       {{-- Contoh Data Komentar 3 --}}
      <div class="bg-white p-4 rounded-lg shadow-sm flex items-start gap-4">
        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-500 flex items-center justify-center text-white font-bold text-xl">L</div>
        <div class="flex-grow">
          <div class="flex justify-between items-center">
            <div>
              <span class="font-semibold text-stone-800">leslie</span>
              <span class="text-xs font-medium bg-red-100 text-red-800 px-2 py-0.5 rounded-full ml-2">Tidak hadir</span>
            </div>
            <div class="flex items-center gap-3 text-stone-400">
              <span class="text-xs">20 Agustus 2025 17:29:33</span>
              <button class="hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
              </button>
            </div>
          </div>
          <p class="text-stone-600 mt-1 text-sm">sebagai salah satu yg mengamati kehidupan aprilla dari kejauhan (makna sesungguhnya), couldn't be more happier for you, prill 😊</p>
        </div>
      </div>

       {{-- Contoh Data Komentar 4 --}}
      <div class="bg-white p-4 rounded-lg shadow-sm flex items-start gap-4">
        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold text-xl">C</div>
        <div class="flex-grow">
          <div class="flex justify-between items-center">
            <div>
              <span class="font-semibold text-stone-800">Cindymutiaf</span>
              <span class="text-xs font-medium bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full ml-2">Masih Ragu</span>
            </div>
            <div class="flex items-center gap-3 text-stone-400">
              <span class="text-xs">20 Agustus 2025 10:15:24</span>
              <button class="hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
              </button>
            </div>
          </div>
          <p class="text-stone-600 mt-1 text-sm">Wuaa dah mau nikah aja temen kuliahku 😍 Selamat ya prilll smoga lancar smpe hari H dan jadi keluarga yg samawa nantinya aamiin 🤗 Btw lucu2 amat foto2nya 😍 cantiiikkk bgt cantin nya 🤗🤗</p>
        </div>
      </div>

    </div>
    <!-- End: Daftar Komentar -->
  </div>
</div>
@endsection
