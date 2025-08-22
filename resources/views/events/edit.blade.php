@extends('layouts.app')

@section('page_title', 'Edit Event')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Event</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui detail event Anda di bawah ini.</p>
        </div>
        <a href="{{ route('setting.index') }}" class="text-sm font-medium text-slate-600 hover:text-amber-600">
            &larr; Kembali ke Setting
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Event</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md" required>
                    </div>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-slate-700">Tanggal Event</label>
                    <div class="mt-1">
                        <input type="date" name="date" id="date" value="{{ old('date', $event->date) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md" required>
                    </div>
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-slate-700">Foto Header Event</label>
                    @if($event->photo_url)
                        <img src="{{ asset($event->photo_url) }}" alt="Foto saat ini" class="mt-2 rounded-md h-32 object-cover">
                    @endif
                    <div class="mt-1">
                        <input type="file" name="photo_url" id="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah foto.</p>
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
@endsection
