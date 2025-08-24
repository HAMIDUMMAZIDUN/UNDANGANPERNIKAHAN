@extends('layouts.app')

@section('page_title', 'Tambah Tamu Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Tamu Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Isi detail tamu pada form di bawah ini.</p>
        </div>
        <a href="{{ route('events.tamu.index', $event) }}"class="text-sm font-medium text-slate-600 hover:text-amber-600">
            &larr; Kembali ke Daftar Tamu
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
        <form action="{{ route('events.tamu.store', $event) }}" method="POST">
            @csrf
            <div class="space-y-6">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Tamu</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('name') border-red-500 @enderror" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="affiliation" class="block text-sm font-medium text-slate-700">Kategori</label>
                    <div class="mt-1">
                        <input type="text" name="affiliation" id="affiliation" value="{{ old('affiliation') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('affiliation') border-red-500 @enderror" placeholder="Contoh: Keluarga Mempelai Pria" required>
                    </div>
                     @error('affiliation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700">Alamat (Opsional)</label>
                    <div class="mt-1">
                        <textarea name="address" id="address" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-slate-700">Nomor Telepon (Opsional)</label>
                    <div class="mt-1">
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md" placeholder="081234567890">
                    </div>
                </div>

            </div>

            <div class="pt-8 flex justify-end gap-3">
                <a href="{{ route('events.tamu.index', $event) }}" class="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-900 bg-amber-500 hover:bg-amber-600">
                    Simpan Tamu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
