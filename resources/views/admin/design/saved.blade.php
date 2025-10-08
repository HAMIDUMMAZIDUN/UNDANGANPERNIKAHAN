@extends('admin.layouts.app')

@section('title', 'Saved Designs')

@push('styles')
<style>
    .design-card {
        transition: all 0.2s ease-in-out;
    }
    .design-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .action-button {
        @apply bg-white text-gray-800 p-2 rounded-full transition-all duration-200;
    }
    .action-button:hover {
        @apply transform scale-110;
    }
    .action-button.edit:hover {
        @apply bg-orange-500 text-white;
    }
    .action-button.delete:hover {
        @apply bg-red-500 text-white;
    }
    .action-button.preview:hover {
        @apply bg-blue-500 text-white;
    }
</style>
@endpush

@section('content')
<div x-data="designGallery()" class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Saved Designs</h1>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" 
                       x-model="searchQuery"
                       @input.debounce.300ms="filterDesigns()"
                       placeholder="Search designs..."
                       class="px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.design.create') }}" 
               class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Design</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($paginator as $design)
        <div class="design-card bg-white rounded-lg shadow-md overflow-hidden" 
             x-show="isVisible('{{ $design->name }}')">
            <div class="aspect-[4/3] bg-gray-100 relative group">
                @if($design->thumbnail)
                <img src="{{ $design->thumbnail }}" 
                     alt="{{ $design->name }}" 
                     class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <i class="fas fa-image text-4xl"></i>
                </div>
                @endif
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.design.preview', $design->id) }}" 
                           class="action-button preview" 
                           title="Preview Design">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.design.editor', $design->id) }}" 
                           class="action-button edit"
                           title="Edit Design">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" 
                                @click="confirmDelete($event, '{{ $design->id }}')"
                                class="action-button delete"
                                title="Delete Design">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg mb-1">{{ $design->name }}</h3>
                <p class="text-sm text-gray-500">
                    Last modified: {{ $design->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-folder-open text-6xl"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">No Designs Yet</h3>
            <p class="text-gray-500 mb-4">Start creating your first design template</p>
            <a href="{{ route('admin.design.create') }}" 
               class="inline-flex items-center gap-2 bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Create New Design</span>
            </a>
        </div>
        @endforelse
    </div>

    @if($paginator->hasPages())
    <div class="mt-6">
        {{ $paginator->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function designGallery() {
    return {
        searchQuery: '',
        designs: [],

        init() {
            this.designs = Array.from(document.querySelectorAll('.design-card'))
                               .map(card => card.querySelector('h3').textContent.toLowerCase());
        },

        isVisible(designName) {
            if (!this.searchQuery) return true;
            return designName.toLowerCase().includes(this.searchQuery.toLowerCase());
        },

        async confirmDelete(event, designId) {
            event.preventDefault();
            
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/design/${designId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
</script>
@endpush
@endsection

