@extends('admin.layouts.app')

@section('title', 'Kelola Desain Tersimpan')

@push('styles')
<style>
    .design-card-preview {
        background-size: cover;
        background-position: center;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6 pb-4 border-b">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pustaka Desain</h1>
                <p class="text-sm text-gray-500">Pilih template siap pakai atau kelola desain kustom Anda.</p>
            </div>
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.design.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="design_file" class="cursor-pointer bg-white border border-gray-300 text-gray-800 font-bold py-2 px-5 rounded-lg shadow-sm hover:bg-gray-100 transition-colors flex items-center gap-2">
                        <i class="fas fa-upload"></i>
                        <span>Impor Desain</span>
                    </label>
                    <input type="file" name="design_file" id="design_file" class="hidden" onchange="this.form.submit()" accept=".json">
                </form>
                <a href="{{ route('admin.design.index') }}" class="bg-orange-500 text-white font-bold py-2 px-5 rounded-lg shadow-md hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Buat Desain Baru</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->has('design_file'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p>{{ $errors->first('design_file') }}</p>
            </div>
        @endif
        
        @if($designs->isEmpty())
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-folder-open text-5xl mb-4"></i>
                <p class="text-lg font-semibold">Belum Ada Desain</p>
                <p>Klik tombol "Buat Desain Baru" atau "Impor Desain" untuk memulai.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($designs as $design)
                    <div class="border rounded-lg shadow-sm overflow-hidden group flex flex-col">
                        @php
                            $previewStyle = '';
                            if (isset($design->is_file_template) && $design->is_file_template) {
                                $previewStyle = "background-image: url('{$design->preview_image}');";
                            } else {
                                $firstComponent = $design->structure[0] ?? null;
                                if ($firstComponent) {
                                    $bgColor = $firstComponent['styles']['backgroundColor'] ?? '#f3f4f6';
                                    $bgImage = $firstComponent['styles']['backgroundImage'] ?? null;
                                    $previewStyle = "background-color: {$bgColor};";
                                    if ($bgImage) {
                                        $previewStyle .= "background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{$bgImage}');";
                                    }
                                }
                            }
                        @endphp
                        <div class="design-card-preview h-40 w-full transition-transform group-hover:scale-105" style="{{ $previewStyle }}"></div>
                        <div class="p-4 bg-white flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 truncate">{{ $design->name }}</h3>
                                <p class="text-xs text-gray-500">
                                    @if(isset($design->is_file_template) && $design->is_file_template)
                                        Template Siap Pakai
                                    @else
                                        Dibuat: {{ $design->created_at->isoFormat('D MMM YYYY') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-4">
                                @if(isset($design->is_file_template) && $design->is_file_template)
                                    <a href="{{ route('order.start', ['template_id' => $design->id]) }}" class="w-full text-center bg-orange-500 text-white text-sm font-semibold py-2 px-3 rounded-md hover:bg-orange-600 transition-colors">Gunakan Template</a>
                                @else
                                    <div class="flex-grow flex items-center gap-2">
                                        <a href="{{ route('admin.design.show_preview', ['design' => $design->id]) }}" target="_blank" class="flex-1 text-center bg-orange-500 text-white text-sm font-semibold py-2 px-3 rounded-md hover:bg-orange-600 transition-colors">Lihat</a>
                                        <a href="{{ route('admin.design.edit', ['design' => $design->id]) }}" class="flex-1 text-center bg-gray-100 text-gray-800 text-sm font-semibold py-2 px-3 rounded-md hover:bg-gray-200 transition-colors">Edit</a>
                                    </div>
                                    <div class="flex items-center flex-shrink-0">
                                        <a href="{{ route('admin.design.export', ['design' => $design->id]) }}" class="text-gray-400 hover:text-green-600 p-2" title="Ekspor">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.design.destroy', ['design' => $design->id]) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus desain ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 p-2" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

