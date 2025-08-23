@extends('layouts.app')

@section('page_title', 'Edit Event')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Event: {{ $event->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui detail event Anda pada form di bawah ini.</p>
            </div>
            <a href="{{ route('setting.index') }}" class="text-sm font-medium text-slate-600 hover:text-amber-600">
                &larr; Kembali ke Setting
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
            {{-- PERBAIKAN: Menggunakan nama route yang benar 'setting.events.update' --}}
            <form action="{{ route('setting.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    
                    {{-- Nama Event --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Nama Event</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('name') border-red-500 @enderror" required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Event --}}
                    <div>
                        <label for="date" class="block text-sm font-medium text-slate-700">Tanggal Event</label>
                        <div class="mt-1">
                            <input type="date" name="date" id="date" value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('date') border-red-500 @enderror" required>
                        </div>
                        @error('date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Foto Utama --}}
                    <div>
                        <label for="photo_url" class="block text-sm font-medium text-slate-700">Ganti Foto Utama (Opsional)</label>
                        <div class="mt-2 flex items-center gap-4">
                            {{-- PERBAIKAN: Menampilkan foto dengan path storage yang benar --}}
                            <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/100x100/f59e0b/334155?text=Event' }}" alt="Foto Event Saat Ini" class="h-20 w-20 rounded-md object-cover">
                            <input type="file" name="photo_url" id="photo_url" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700
                                hover:file:bg-amber-100 cursor-pointer"/>
                        </div>
                         @error('photo_url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="pt-8 flex justify-end gap-3">
                    <a href="{{ route('setting.index') }}" class="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-900 bg-amber-500 hover:bg-amber-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
