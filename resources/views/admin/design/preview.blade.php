<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Desain: {{ $design->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="max-w-3xl mx-auto bg-white shadow-lg">
        @foreach ($design->structure as $item)
            @php
                $styles = $item['styles'];
                $data = $item['data'];
                $styleString = "background-color: {$styles['backgroundColor']}; color: {$styles['color']}; padding: {$styles['padding']}px; text-align: center; background-size: cover; background-position: center;";
                if (!empty($styles['backgroundImage'])) {
                    $styleString .= "background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{$styles['backgroundImage']}'); color: #FFFFFF;";
                }
            @endphp

            <div style="{{ $styleString }}">
                @switch($item['type'])
                    @case('cover')
                        <div class="flex flex-col items-center">
                            <p class="text-sm tracking-widest uppercase">{{ $data['openingText'] ?? 'THE WEDDING OF' }}</p>
                            <h2 class="text-5xl font-serif my-6">{{ $data['brideName'] ?? $dummyEvent->bride_name }} & {{ $data['groomName'] ?? $dummyEvent->groom_name }}</h2>
                            <p class="font-semibold text-lg">{{ $data['date'] ?? $dummyEvent->date_formatted }}</p>
                        </div>
                        @break

                    @case('mempelai')
                        <div class="px-4">
                             <p class="text-sm mb-8 max-w-xl mx-auto">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:</p>
                             <div class="flex flex-col md:flex-row justify-center items-center gap-8 md:gap-16">
                                <div class="w-64">
                                    <img src="{{ $data['bridePhotoUrl'] ?? $dummyEvent->couple_photo_url }}" class="w-40 h-40 object-cover rounded-full mx-auto border-4 border-white/50 shadow-lg">
                                    <h3 class="text-3xl font-serif mt-4">{{ $data['brideName'] ?? $dummyEvent->bride_name }}</h3>
                                    <p class="text-sm mt-2">{{ $data['brideParents'] ?? $dummyEvent->bride_parents }}</p>
                                </div>
                                <span class="text-5xl font-serif">&</span>
                                <div class="w-64">
                                    <img src="{{ $data['groomPhotoUrl'] ?? $dummyEvent->video_placeholder_url }}" class="w-40 h-40 object-cover rounded-full mx-auto border-4 border-white/50 shadow-lg">
                                    <h3 class="text-3xl font-serif mt-4">{{ $data['groomName'] ?? $dummyEvent->groom_name }}</h3>
                                    <p class="text-sm mt-2">{{ $data['groomParents'] ?? $dummyEvent->groom_parents }}</p>
                                </div>
                             </div>
                        </div>
                        @break

                    @case('acara')
                         <div>
                            <h2 class="font-serif text-4xl mb-8">Save The Date</h2>
                            <div class="flex flex-col md:flex-row justify-center gap-8">
                                <div class="border-2 border-current/20 p-6 rounded-lg flex-1">
                                    <h3 class="font-serif text-3xl">Akad Nikah</h3>
                                    <p class="mt-4 font-semibold">{{ $data['akadDate'] ?? $dummyEvent->date_formatted }}</p>
                                    <p>{{ $data['akadTime'] ?? $dummyEvent->akad_time }}</p>
                                    <p class="mt-4 text-sm">{{ $data['akadLocation'] ?? $dummyEvent->akad_location }}</p>
                                </div>
                                <div class="border-2 border-current/20 p-6 rounded-lg flex-1">
                                    <h3 class="font-serif text-3xl">Resepsi</h3>
                                    <p class="mt-4 font-semibold">{{ $data['resepsiDate'] ?? $dummyEvent->date_formatted }}</p>
                                    <p>{{ $data['resepsiTime'] ?? $dummyEvent->resepsi_time }}</p>
                                    <p class="mt-4 text-sm">{{ $data['resepsiLocation'] ?? $dummyEvent->resepsi_location }}</p>
                                </div>
                            </div>
                         </div>
                        @break

                    @case('galeri')
                        @php
                            $photos = !empty($data['photoList']) ? explode("\n", $data['photoList']) : [];
                        @endphp
                        <h2 class="font-serif text-4xl mb-8">Our Moments</h2>
                        @if (!empty($photos))
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-4">
                                @foreach ($photos as $url)
                                    @if(trim($url))
                                        <div class="aspect-square bg-gray-200 rounded-lg bg-cover bg-center" style="background-image: url('{{ trim($url) }}')"></div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm italic text-current/70">Galeri foto kosong.</p>
                        @endif
                        @break

                    @case('video')
                        <div>
                            <h2 class="font-serif text-4xl mb-8">Our Love Story</h2>
                            <div class="aspect-video bg-gray-900 rounded-lg flex items-center justify-center text-white/50">
                                <i class="fas fa-play-circle text-6xl"></i>
                                <p class="ml-4">Placeholder Video</p>
                            </div>
                        </div>
                        @break
                    @case('ucapan')
                        <h2 class="font-serif text-4xl">Ucapan & RSVP</h2><p class="mt-4 text-sm">Formulir akan ditampilkan di sini.</p>
                        @break
                    @case('hadiah')
                        <h2 class="font-serif text-4xl">Wedding Gift</h2><p class="mt-4 text-sm">Informasi hadiah akan ditampilkan di sini.</p>
                        @break
                    @case('countdown')
                        <h2 class="font-serif text-4xl">Menuju Hari Bahagia</h2><p class="mt-4 text-3xl font-bold">00:00:00:00</p>
                        @break

                @endswitch
            </div>
        @endforeach
    </div>

</body>
</html>
