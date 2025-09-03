@extends('user.layouts.app') 

@section('content')
<div class="flex h-screen bg-gray-100">
    <aside class="w-72 bg-white p-4 shadow-md">
        <h3 class="font-bold text-lg mb-4">Komponen</h3>
        <div id="palette" class="space-y-3">
            <div class="p-4 border rounded-lg bg-gray-50 cursor-move" data-type="headline">
                <h1 class="text-2xl font-bold pointer-events-none">Judul Utama</h1>
            </div>
            <div class="p-4 border rounded-lg bg-gray-50 cursor-move" data-type="paragraph">
                <p class="pointer-events-none">Tulis paragraf Anda di sini. Deskripsi acara atau detail lainnya.</p>
            </div>
            <div class="p-4 border rounded-lg bg-gray-50 cursor-move" data-type="image">
                <div class="w-full h-24 bg-gray-300 rounded pointer-events-none flex items-center justify-center">
                    <span class="text-gray-500">Gambar</span>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 p-6">
        <div id="canvas" class="bg-white h-full w-full max-w-4xl mx-auto p-8 border rounded-lg shadow-inner overflow-y-auto">
            @if ($event->content)
                @foreach ($invitation->content as $element)
                    {{-- Logika untuk render elemen yang sudah ada --}}
                @endforeach
            @endif
        </div>
    </main>
</div>

<div class="fixed bottom-5 right-5">
    <button id="saveButton" data-url="{{ route('events.saveDesign', $event) }}">
    Simpan Perubahan
</button>
</div>
@endsection