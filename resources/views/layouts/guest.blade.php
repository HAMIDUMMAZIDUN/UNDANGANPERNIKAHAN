<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        {{-- Wrapper utama SEKARANG menggunakan background image yang sama dengan halaman welcome --}}
        <div 
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center" 
            style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">
            
            <!-- Logo -->
            <div>
                <a href="/">
                    <x-application-logo class="w-24 h-24 fill-current text-white" style="filter: drop-shadow(0 2px 3px rgba(0,0,0,0.4));"/>
                </a>
            </div>

            <!-- Kartu form dengan efek glassmorphism (semi-transparan) yang akan terlihat bagus di atas gambar -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
