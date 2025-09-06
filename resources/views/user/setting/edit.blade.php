@extends('user.layouts.app')

@section('page_title', 'Edit Event')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Event: {{ $event->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui detail event Anda pada form di bawah ini.</p>
            </div>
            {{-- PERBAIKAN: Menggunakan nama rute yang benar --}}
            <a href="{{ route('user.setting.index') }}" class="text-sm font-medium text-slate-600 hover:text-amber-600">
                &larr; Kembali ke Setting
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
            {{-- PERBAIKAN: Menggunakan nama rute yang benar --}}
            <form action="{{ route('user.setting.events.update', $event) }}" method="POST" enctype="multipart/form-data">
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
                    {{-- Lokasi Event --}}
                    <div class="border-t border-slate-200 pt-6 mt-6">
                        <h2 class="text-xl font-bold text-slate-800">Detail Acara (Akad & Resepsi)</h2>

                        {{-- Detail Akad --}}
                        <div class="mt-6 space-y-4">
                            <h3 class="text-lg font-semibold text-slate-700">Akad Nikah</h3>
                            <div>
                                <label for="akad_location" class="block text-sm font-medium text-slate-700">Lokasi Akad</label>
                                <textarea name="akad_location" id="akad_location" rows="2" class="mt-1 shadow-sm block w-full sm:text-sm border-slate-300 rounded-md">{{ old('akad_location', $event->akad_location) }}</textarea>
                            </div>
                            <div>
                                <label for="akad_time" class="block text-sm font-medium text-slate-700">Waktu Akad</label>
                                <input type="text" name="akad_time" id="akad_time" value="{{ old('akad_time', $event->akad_time) }}" placeholder="Contoh: 09:00 WIB" class="mt-1 shadow-sm block w-full sm:text-sm border-slate-300 rounded-md">
                            </div>
                            <div>
                                <label for="akad_maps_url" class="block text-sm font-medium text-slate-700">Link Google Maps Akad (Opsional)</label>
                                <input type="url" name="akad_maps_url" id="akad_maps_url" value="{{ old('akad_maps_url', $event->akad_maps_url) }}" placeholder="https://maps.app.goo.gl/..." class="mt-1 shadow-sm block w-full sm:text-sm border-slate-300 rounded-md">
                            </div>
                        </div>

                        {{-- Detail Resepsi --}}
                        <div class="mt-6 space-y-4">
                            <h3 class="text-lg font-semibold text-slate-700">Resepsi</h3>
                            <div>
                                <label for="resepsi_location" class="block text-sm font-medium text-slate-700">Lokasi Resepsi</label>
                                <textarea name="resepsi_location" id="resepsi_location" rows="2" class="mt-1 shadow-sm block w-full sm:text-sm border-slate-300 rounded-md">{{ old('resepsi_location', $event->resepsi_location) }}</textarea>
                            </div>
                            <div>
                                <label for="resepsi_time" class="block text-sm font-medium text-slate-700">Waktu Resepsi</label>
                                <input type="text" name="resepsi_time" id="resepsi_time" value="{{ old('resepsi_time', $event->resepsi_time) }}" placeholder="Contoh: 11:00 - 14:00 WIB" class="mt-1 shadow-sm block w-full sm:text-sm border-slate-300 rounded-md">
                            </div>
                            <div>
                                <label for="resepsi_maps_url" class="block text-sm font-medium text-slate-700">Link Google Maps Resepsi (Opsional)</label>
                                <input type="url" name="resepsi_maps_url" id="resepsi_maps_url" value="{{ old('resepsi_maps_url', $event->resepsi_maps_url) }}" placeholder="https://maps.app.goo.gl/..." class="mt-1 shadow-sm block w-full sm:text-sm border-slate-300 rounded-md">
                            </div>
                        </div>
                    </div>
                    {{-- Upload Foto Utama Event --}}
                    <div>
                        <label for="photo_url" class="block text-sm font-medium text-slate-700">Ganti Foto Utama Event (Opsional)</label>
                        <div class="mt-2 flex items-center gap-4">
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

                    <h2 class="text-xl font-bold text-slate-800 pt-6">Detail Mempelai Pria</h2>

                    {{-- Nama Mempelai Pria --}}
                    <div>
                        <label for="groom_name" class="block text-sm font-medium text-slate-700">Nama Mempelai Pria</label>
                        <div class="mt-1">
                            <input type="text" name="groom_name" id="groom_name" value="{{ old('groom_name', $event->groom_name) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('groom_name') border-red-500 @enderror">
                        </div>
                        @error('groom_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Orang Tua Pria --}}
                    <div>
                        <label for="groom_parents" class="block text-sm font-medium text-slate-700">Nama Orang Tua Pria</label>
                        <div class="mt-1">
                            <input type="text" name="groom_parents" id="groom_parents" value="{{ old('groom_parents', $event->groom_parents) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('groom_parents') border-red-500 @enderror">
                        </div>
                        @error('groom_parents')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instagram Mempelai Pria --}}
                    <div>
                        <label for="groom_instagram" class="block text-sm font-medium text-slate-700">Instagram Mempelai Pria (opsional)</label>
                        <div class="mt-1">
                            <input type="text" name="groom_instagram" id="groom_instagram" value="{{ old('groom_instagram', $event->groom_instagram) }}" placeholder="@username" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('groom_instagram') border-red-500 @enderror">
                        </div>
                        @error('groom_instagram')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Upload Foto Mempelai Pria --}}
                    <div>
                        <label for="groom_photo" class="block text-sm font-medium text-slate-700">Ganti Foto Mempelai Pria (Opsional)</label>
                        <div class="mt-2 flex items-center gap-4">
                            <img src="{{ $event->groom_photo ? asset('storage/' . $event->groom_photo) : 'https://placehold.co/100x100/3b82f6/ffffff?text=Pria' }}" alt="Foto Mempelai Pria Saat Ini" class="h-20 w-20 rounded-full object-cover">
                            <input type="file" name="groom_photo" id="groom_photo" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 cursor-pointer"/>
                        </div>
                        @error('groom_photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 pt-6">Detail Mempelai Wanita</h2>

                    {{-- Nama Mempelai Wanita --}}
                    <div>
                        <label for="bride_name" class="block text-sm font-medium text-slate-700">Nama Mempelai Wanita</label>
                        <div class="mt-1">
                            <input type="text" name="bride_name" id="bride_name" value="{{ old('bride_name', $event->bride_name) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('bride_name') border-red-500 @enderror">
                        </div>
                        @error('bride_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Orang Tua Wanita --}}
                    <div>
                        <label for="bride_parents" class="block text-sm font-medium text-slate-700">Nama Orang Tua Wanita</label>
                        <div class="mt-1">
                            <input type="text" name="bride_parents" id="bride_parents" value="{{ old('bride_parents', $event->bride_parents) }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('bride_parents') border-red-500 @enderror">
                        </div>
                        @error('bride_parents')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instagram Mempelai Wanita --}}
                    <div>
                        <label for="bride_instagram" class="block text-sm font-medium text-slate-700">Instagram Mempelai Wanita (opsional)</label>
                        <div class="mt-1">
                            <input type="text" name="bride_instagram" id="bride_instagram" value="{{ old('bride_instagram', $event->bride_instagram) }}" placeholder="@username" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('bride_instagram') border-red-500 @enderror">
                        </div>
                        @error('bride_instagram')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Foto Mempelai Wanita --}}
                    <div>
                        <label for="bride_photo" class="block text-sm font-medium text-slate-700">Ganti Foto Mempelai Wanita (Opsional)</label>
                        <div class="mt-2 flex items-center gap-4">
                            <img src="{{ $event->bride_photo ? asset('storage/' . $event->bride_photo) : 'https://placehold.co/100x100/f472b6/ffffff?text=Wanita' }}" alt="Foto Mempelai Wanita Saat Ini" class="h-20 w-20 rounded-full object-cover">
                            <input type="file" name="bride_photo" id="bride_photo" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700
                                hover:file:bg-pink-100 cursor-pointer"/>
                        </div>
                        @error('bride_photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-8 flex justify-end gap-3">
                    {{-- PERBAIKAN: Menggunakan nama rute yang benar --}}
                    <a href="{{ route('user.setting.index') }}" class="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50">
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

