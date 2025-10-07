@extends('admin.layouts.app')

@section('title', 'Saved Designs')

@push('styles')
<style>
    .design-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .design-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Saved Designs</h1>
        <a href="{{ route('admin.design.create') }}" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>New Design</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($designs as $design)
        <div class="design-card bg-white rounded-lg shadow-md overflow-hidden">
            <div class="aspect-[4/3] bg-gray-100 relative">
                @if($design->thumbnail)
                <img src="{{ $design->thumbnail }}" alt="{{ $design->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <i class="fas fa-image text-4xl"></i>
                </div>
                @endif
                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-50 transition-all flex items-center justify-center opacity-0 hover:opacity-100">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.design.preview', $design->id) }}" class="bg-white text-gray-800 p-2 rounded-full hover:bg-orange-500 hover:text-white transition-colors">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.design.editor', $design->id) }}" class="bg-white text-gray-800 p-2 rounded-full hover:bg-orange-500 hover:text-white transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.design.destroy', $design->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="bg-white text-gray-800 p-2 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg mb-1">{{ $design->name }}</h3>
                <p class="text-sm text-gray-500">Last modified: {{ $design->updated_at->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-folder-open text-6xl"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">No Designs Yet</h3>
            <p class="text-gray-500 mb-4">Start creating your first design template</p>
            <a href="{{ route('admin.design.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600">
                <i class="fas fa-plus"></i>
                <span>Create New Design</span>
            </a>
        </div>
        @endforelse
    </div>

    @if($designs->hasPages())
    <div class="mt-6">
        {{ $designs->links() }}
    </div>
    @endif
</div>
@endsection

