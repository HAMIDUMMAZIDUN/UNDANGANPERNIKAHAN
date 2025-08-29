@extends('user.layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="bg-stone-100 min-h-screen flex items-center justify-center font-sans p-4">
    <div class="max-w-md mx-auto text-center bg-white p-8 rounded-xl shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-stone-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h1 class="text-2xl font-bold text-stone-800 mb-2">Belum Ada Event</h1>
        <p class="text-stone-600 mb-6">
            Anda belum membuat event sama sekali. Silakan buat event baru untuk memulai.
        </p>
        <a href="#" class="bg-stone-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-stone-800 transition duration-300">
            + Buat Event Baru
        </a>
    </div>
</div>
@endsection
