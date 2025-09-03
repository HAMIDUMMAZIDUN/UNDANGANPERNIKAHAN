@extends('layouts.public')

@section('content')
<div class="max-w-3xl mx-auto p-8">
    <h1 class="text-center text-4xl font-bold mb-8">{{ $invitation->title }}</h1>

    @if($invitation->content)
        @foreach($invitation->content as $element)
            @switch($element['type'])
                @case('headline')
                    <h2 class="text-3xl font-bold my-6">{{ $element['content']['text'] }}</h2>
                    @break
                @case('paragraph')
                    <p class="text-lg text-gray-700 my-4 leading-relaxed">{{ $element['content']['text'] }}</p>
                    @break
                @case('image')
                    <img src="{{ $element['content']['src'] }}" alt="{{ $element['content']['alt'] }}" class="w-full rounded-lg shadow-md my-6">
                    @break
            @endswitch
        @endforeach
    @else
        <p class="text-center text-gray-500">Konten belum tersedia.</p>
    @endif
</div>
@endsection