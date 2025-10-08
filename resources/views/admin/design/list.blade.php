@extends('admin.layouts.app')
@section('title', 'Manajemen Desain')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .design-card:hover .design-actions {
        opacity: 1;
    }
    .design-preview {
        transition: transform 0.3s ease;
    }
    .design-card:hover .design-preview {
        transform: scale(1.03);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Desain</h1>
            <p class="text-gray-600 mt-1">Kelola, edit, atau buat template undangan digital baru.</p>
        </div>
        <a href="{{ route('admin.design.create') }}" 
           class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2 shadow-sm hover:shadow-md">
            <i class="fas fa-plus"></i>
            <span>Buat Desain Baru</span>
        </a>
    </div>

    @if($designs->isEmpty())
        <div class="text-center py-16 bg-gray-50 rounded-lg border border-dashed">
            <i class="fas fa-pencil-ruler text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700">Belum Ada Desain</h3>
            <p class="text-gray-500 mt-2">Mulai dengan membuat desain undangan pertama Anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($designs as $design)
            <div class="design-card group bg-white rounded-xl shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-xl">
                <div class="relative">
                    {{-- Preview Image --}}
                    <div class="aspect-[4/3] bg-gray-100 design-preview overflow-hidden">
                        @if($design->preview_image)
                            <img src="{{ asset($design->preview_image) }}" alt="{{ $design->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                <i class="fas fa-image text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Action Overlay --}}
                    <div class="design-actions absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-300 flex items-center justify-center gap-4">
                        <a href="{{ route('admin.design.editor', $design->id) }}" 
                           class="bg-white/90 text-gray-800 h-12 w-12 flex items-center justify-center rounded-full hover:bg-orange-500 hover:text-white transition-all transform hover:scale-110"
                           title="Edit Desain">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('admin.design.preview', $design->id) }}" 
                           target="_blank"
                           class="bg-white/90 text-gray-800 h-12 w-12 flex items-center justify-center rounded-full hover:bg-orange-500 hover:text-white transition-all transform hover:scale-110"
                           title="Preview">
                            <i class="fas fa-eye"></i>
                        </a>
                        {{-- Form untuk Hapus --}}
                        <form id="delete-form-{{ $design->id }}" action="{{ route('admin.design.destroy', $design->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete({{ $design->id }})"
                                    class="bg-white/90 text-gray-800 h-12 w-12 flex items-center justify-center rounded-full hover:bg-red-500 hover:text-white transition-all transform hover:scale-110"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Design Info --}}
                <div class="p-5">
                    <h3 class="font-semibold text-gray-800 truncate">{{ $design->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Dibuat: {{ $design->created_at->isoFormat('D MMMM YYYY') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $designs->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(designId) {
    Swal.fire({
        title: 'Anda yakin ingin menghapus?',
        text: "Desain yang sudah dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus Saja!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, submit form yang sesuai
            document.getElementById(`delete-form-${designId}`).submit();
        }
    })
}
</script>
@endpush