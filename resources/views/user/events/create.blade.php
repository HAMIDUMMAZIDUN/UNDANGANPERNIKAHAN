@extends('user.layouts.app')

@section('page_title', 'Buat Event Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

   
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

@if (session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p>{{ session('info') }}</p>
    </div>
@endif
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Event Pertama Anda</h1>
            <p class="text-sm text-slate-500 mt-1">Isi detail event untuk melanjutkan ke dashboard.</p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Event</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('name') border-red-500 @enderror" placeholder="Contoh: Pernikahan Budi & Ani" required>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-slate-700">Tanggal Event</label>
                    <div class="mt-1">
                        <input type="date" name="date" id="date" value="{{ old('date') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('date') border-red-500 @enderror" required>
                    </div>
                     @error('date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700">Lokasi (Opsional)</label>
                    <div class="mt-1">
                        <input type="text" name="location" id="location" value="{{ old('location') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md" placeholder="Contoh: Gedung Serbaguna, Jakarta">
                    </div>
                </div>

            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-900 bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    Buat Event dan Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
