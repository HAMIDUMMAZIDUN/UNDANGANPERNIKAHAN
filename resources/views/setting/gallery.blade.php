@extends('layouts.app')

@section('page_title', 'Galeri Foto')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Galeri Foto: {{ $event->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola semua foto untuk event Anda di sini.</p>
            </div>
            <a href="{{ route('setting.index') }}" class="text-sm font-medium text-slate-600 hover:text-amber-600">
                &larr; Kembali ke Setting
            </a>
        </div>

        <!-- Form Upload -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form action="{{ route('setting.events.gallery.upload', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="photo" class="block text-sm font-medium text-slate-700 mb-2">Unggah Foto Baru</label>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <input type="file" name="photo" id="photo" required class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                        file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700
                        hover:file:bg-amber-100 cursor-pointer"/>
                    <button type="submit" class="w-full sm:w-auto flex-shrink-0 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-900 bg-amber-500 hover:bg-amber-600">
                        Unggah
                    </button>
                </div>
                 @error('photo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <!-- Grid Galeri Foto -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse ($photos as $photo)
                <div class="relative group rounded-lg overflow-hidden shadow-md">
                    {{-- Gambar dengan efek zoom saat hover --}}
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Event" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                    
                    {{-- Overlay transparan yang muncul saat hover --}}
                    <div class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-0 transition-all duration-300 flex items-center justify-center">
                        
                        {{-- Form dan tombol hapus di tengah overlay --}}
                        <form action="{{ route('setting.gallery.delete', $photo) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white opacity-0 group-hover:opacity-100 transition-all duration-300 p-3 bg-red-600/80 rounded-full hover:bg-red-700 transform scale-75 group-hover:scale-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-lg shadow-md">
                    <p class="text-slate-500">Belum ada foto yang diunggah untuk event ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($photos->hasPages())
            <div class="mt-8">
                {{ $photos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
{{-- Pastikan Anda sudah punya SweetAlert di layouts/app.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus foto ini?',
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush
