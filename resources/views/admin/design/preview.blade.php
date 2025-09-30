<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Desain: {{ $design->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Poppins', sans-serif; }
        /* Menambahkan sedikit animasi untuk elemen interaktif */
        .hover-effect {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="max-w-3xl mx-auto bg-white shadow-lg my-8">
        @foreach ($design->structure as $item)
            @php
                $styles = $item['styles'];
                $data = $item['data'];
                // Menggunakan padding internal untuk konten agar lebih rapi
                $paddingValue = $styles['padding'] ?? '48'; // Default padding 48px
                $containerStyleString = "background-color: {$styles['backgroundColor']}; color: {$styles['color']}; text-align: center; background-size: cover; background-position: center;";
                
                if (!empty($styles['backgroundImage'])) {
                    // Gradien ditambahkan agar teks lebih mudah dibaca di atas gambar
                    $containerStyleString .= "background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{$styles['backgroundImage']}'); color: #FFFFFF;";
                }
            @endphp

            <div style="{{ $containerStyleString }}" class="overflow-hidden">
                <div class="px-6 sm:px-8" style="padding-top: {{ $paddingValue }}px; padding-bottom: {{ $paddingValue }}px;">
                @switch($item['type'])
                    @case('cover')
                        {{-- Tampilan cover dibuat lebih menonjol dengan min-height --}}
                        <div class="min-h-[70vh] flex flex-col justify-center items-center">
                            <p class="text-sm tracking-widest uppercase">{{ $data['openingText'] ?? 'THE WEDDING OF' }}</p>
                            <h2 class="text-4xl md:text-6xl font-serif my-6">{{ $data['brideName'] ?? $dummyEvent->bride_name }} & {{ $data['groomName'] ?? $dummyEvent->groom_name }}</h2>
                            <p class="font-semibold text-lg">{{ $data['date'] ?? $dummyEvent->date_formatted }}</p>
                        </div>
                        @break

                    @case('mempelai')
                        <p class="text-base mb-10 max-w-xl mx-auto leading-relaxed">{{ $data['introText'] ?? 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:' }}</p>
                        <div class="flex flex-col md:flex-row justify-center items-center gap-8 md:gap-12">
                            {{-- Mempelai Wanita --}}
                            <div class="w-64">
                                <img src="{{ $data['bridePhotoUrl'] ?? $dummyEvent->couple_photo_url }}" class="w-48 h-48 object-cover rounded-full mx-auto border-4 border-white/70 shadow-lg">
                                <h3 class="text-3xl font-serif mt-6">{{ $data['brideName'] ?? $dummyEvent->bride_name }}</h3>
                                <p class="text-sm mt-2 font-medium">{{ $data['brideBio'] ?? 'Putri dari Pasangan' }}</p>
                                <p class="text-sm">{{ $data['brideParents'] ?? $dummyEvent->bride_parents }}</p>
                            </div>
                            
                            {{-- Pemisah --}}
                            <span class="text-6xl font-serif my-4 md:my-0">&</span>
                            
                            {{-- Mempelai Pria --}}
                            <div class="w-64">
                                <img src="{{ $data['groomPhotoUrl'] ?? $dummyEvent->video_placeholder_url }}" class="w-48 h-48 object-cover rounded-full mx-auto border-4 border-white/70 shadow-lg">
                                <h3 class="text-3xl font-serif mt-6">{{ $data['groomName'] ?? $dummyEvent->groom_name }}</h3>
                                <p class="text-sm mt-2 font-medium">{{ $data['groomBio'] ?? 'Putra dari Pasangan' }}</p>
                                <p class="text-sm">{{ $data['groomParents'] ?? $dummyEvent->groom_parents }}</p>
                            </div>
                        </div>
                        @break

                    @case('acara')
                        <h2 class="font-serif text-4xl mb-10">Save The Date</h2>
                        <div class="flex flex-col md:flex-row justify-center gap-8 text-left">
                            {{-- Card Akad --}}
                            <div class="border-2 border-current/20 p-6 rounded-lg flex-1 backdrop-blur-sm bg-white/10 hover-effect">
                                <h3 class="font-serif text-3xl">Akad Nikah</h3>
                                <div class="mt-4 space-y-2">
                                    <p><i class="fa-solid fa-calendar-alt w-6"></i><span class="font-semibold">{{ $data['akadDate'] ?? $dummyEvent->date_formatted }}</span></p>
                                    <p><i class="fa-solid fa-clock w-6"></i>{{ $data['akadTime'] ?? $dummyEvent->akad_time }}</p>
                                    <p class="mt-2"><i class="fa-solid fa-map-marker-alt w-6"></i>{{ $data['akadLocation'] ?? $dummyEvent->akad_location }}</p>
                                </div>
                            </div>
                            {{-- Card Resepsi --}}
                            <div class="border-2 border-current/20 p-6 rounded-lg flex-1 backdrop-blur-sm bg-white/10 hover-effect">
                                <h3 class="font-serif text-3xl">Resepsi</h3>
                                <div class="mt-4 space-y-2">
                                    <p><i class="fa-solid fa-calendar-alt w-6"></i><span class="font-semibold">{{ $data['resepsiDate'] ?? $dummyEvent->date_formatted }}</span></p>
                                    <p><i class="fa-solid fa-clock w-6"></i>{{ $data['resepsiTime'] ?? $dummyEvent->resepsi_time }}</p>
                                    <p class="mt-2"><i class="fa-solid fa-map-marker-alt w-6"></i>{{ $data['resepsiLocation'] ?? $dummyEvent->resepsi_location }}</p>
                                </div>
                            </div>
                        </div>
                        @break

                    @case('galeri')
                        @php
                            $photos = !empty($data['photoList']) ? explode("\n", $data['photoList']) : [];
                        @endphp
                        <h2 class="font-serif text-4xl mb-10">Our Moments</h2>
                        @if (!empty($photos))
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($photos as $url)
                                    @if(trim($url))
                                        <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                            <div class="w-full h-full bg-cover bg-center transition-transform duration-500 hover:scale-110" style="background-image: url('{{ trim($url) }}')"></div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            {{-- Placeholder jika galeri kosong --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @for ($i = 0; $i < 6; $i++)
                                <div class="aspect-square bg-gray-300/50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-current/30"></i>
                                </div>
                                @endfor
                            </div>
                            <p class="text-sm italic text-current/70 mt-4">Galeri foto akan ditampilkan di sini.</p>
                        @endif
                        @break

                    @case('video')
                        <h2 class="font-serif text-4xl mb-10">Our Love Story</h2>
                        {{-- Placeholder video dibuat lebih realistis --}}
                        <div class="aspect-video bg-gray-900 rounded-lg flex items-center justify-center text-white/50 relative overflow-hidden group">
                            <img src="{{ $dummyEvent->video_placeholder_url ?? 'https://images.unsplash.com/photo-1542042161-d19711681952' }}" class="absolute w-full h-full object-cover opacity-50 group-hover:opacity-40 transition-opacity">
                            <div class="z-10 text-center">
                                <i class="fas fa-play-circle text-6xl cursor-pointer group-hover:scale-110 transition-transform"></i>
                                <p class="mt-2 font-semibold">Tonton Video Kami</p>
                            </div>
                        </div>
                        @break

                    @case('ucapan')
                        <h2 class="font-serif text-4xl mb-10">Ucapan & RSVP</h2>
                        {{-- Placeholder form RSVP dibuat lebih visual --}}
                        <div class="max-w-md mx-auto text-left bg-white/10 backdrop-blur-sm p-6 rounded-lg">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Nama Anda</label>
                                <input type="text" placeholder="Masukkan nama Anda" class="w-full bg-white/20 border border-current/20 rounded px-3 py-2 placeholder-current/50 focus:outline-none focus:ring-2 focus:ring-current">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Ucapan</label>
                                <textarea rows="4" placeholder="Tulis ucapan Anda di sini..." class="w-full bg-white/20 border border-current/20 rounded px-3 py-2 placeholder-current/50 focus:outline-none focus:ring-2 focus:ring-current"></textarea>
                            </div>
                            <button type="button" class="w-full font-semibold py-2 px-4 rounded hover-effect" style="background-color: {{ $styles['color'] }}; color: {{ $styles['backgroundColor'] }};">Kirim Ucapan</button>
                        </div>
                        @break

                    @case('hadiah')
                        <h2 class="font-serif text-4xl mb-10">Wedding Gift</h2>
                        <p class="max-w-xl mx-auto mb-8 text-sm">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat memberi kado secara cashless.</p>
                        {{-- Placeholder untuk hadiah/amplop digital --}}
                        <div class="max-w-md mx-auto space-y-4">
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg text-left flex items-center gap-4">
                                <i class="fa-solid fa-wallet text-3xl w-12 text-center"></i>
                                <div>
                                    <p class="font-semibold">E-Wallet (Contoh)</p>
                                    <p class="text-sm">0812-3456-7890 a/n Mempelai</p>
                                </div>
                            </div>
                             <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg text-left flex items-center gap-4">
                                <i class="fa-solid fa-building-columns text-3xl w-12 text-center"></i>
                                <div>
                                    <p class="font-semibold">Bank Transfer (Contoh)</p>
                                    <p class="text-sm">123-456-7890 a/n Mempelai</p>
                                </div>
                            </div>
                        </div>
                        @break

                    @case('countdown')
                        <h2 class="font-serif text-4xl mb-6">Menuju Hari Bahagia</h2>
                        {{-- Placeholder countdown timer dibuat lebih menarik --}}
                        <div class="flex justify-center gap-4 md:gap-8">
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24">
                                <p class="text-4xl font-bold">00</p>
                                <p class="text-xs uppercase">Hari</p>
                            </div>
                             <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24">
                                <p class="text-4xl font-bold">00</p>
                                <p class="text-xs uppercase">Jam</p>
                            </div>
                             <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24">
                                <p class="text-4xl font-bold">00</p>
                                <p class="text-xs uppercase">Menit</p>
                            </div>
                             <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24">
                                <p class="text-4xl font-bold">00</p>
                                <p class="text-xs uppercase">Detik</p>
                            </div>
                        </div>
                        @break

                @endswitch
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>