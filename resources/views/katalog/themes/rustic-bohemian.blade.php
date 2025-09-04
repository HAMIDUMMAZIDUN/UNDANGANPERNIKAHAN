@use(SimpleSoftwareIO\QrCode\Facades\QrCode)
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of {{ $event->groom_name ?? 'Putra' }} & {{ $event->bride_name ?? 'Putri' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Mengganti font sesuai desain baru --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Montserrat', 'sans-serif'],
              serif: ['Cormorant Garamond', 'serif'],
            },
            colors: {
              'brand-green': '#1E453E',
              'brand-gold': '#BFAA8F',
              'brand-bg': '#FBFBFA',
              'brand-light': '#F5F1E9'
            }
          }
        }
      }
    </script>

    <style>
        body {
            background-color: #F5F1E9;
            color: #1E453E;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            font-family: 'Montserrat', 'sans-serif';
        }
        #main-content { display: none; }
        .font-title { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    
    <audio id="background-music" loop> <source src="{{ $event->music_url ?? 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3' }}" type="audio/mpeg"> </audio>
    <div id="music-control" class="fixed bottom-5 right-5 z-[999] cursor-pointer p-3 bg-white/50 backdrop-blur-md rounded-full shadow-lg hidden">
        <i id="music-icon" class="fa-solid fa-volume-high text-brand-green text-xl w-6 h-6 text-center"></i>
    </div>

    <div id="cover" class="h-screen w-full flex flex-col justify-center items-center text-center p-4 bg-brand-light relative overflow-hidden">
        {{-- Ornamen --}}
        <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute -top-10 -left-10 w-48 md:w-80 opacity-90">
        <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute -bottom-10 -right-10 w-48 md:w-80 opacity-90 rotate-180">
        <img src="https://i.ibb.co/fMLm82Q/rose-line-art.png" alt="ornamen" class="absolute bottom-10 left-10 w-24 opacity-20">

        <div class="relative z-20" data-aos="zoom-in">
            <p class="text-sm tracking-widest uppercase">The Wedding Of</p>
            <div class="my-6 relative w-48 h-48 mx-auto p-2 bg-white/50 shadow-lg rounded-md">
            </div>
            <h1 class="font-title text-6xl text-brand-gold">{{ $event->bride_name ?? 'Putri' }} & {{ $event->groom_name ?? 'Putra' }}</h1>
            <p class="mt-4 font-semibold">{{ \Carbon\Carbon::parse($event->date ?? '2025-12-28')->isoFormat('dddd, D MMMM YYYY') }}</p>

            <div id="countdown" class="grid grid-cols-4 gap-2 mt-8 text-center max-w-xs mx-auto">
                <div class="p-2 bg-brand-green text-white rounded-lg"><p id="days" class="text-2xl font-bold">00</p><p class="text-xs">Hari</p></div>
                <div class="p-2 bg-brand-green text-white rounded-lg"><p id="hours" class="text-2xl font-bold">00</p><p class="text-xs">Jam</p></div>
                <div class="p-2 bg-brand-green text-white rounded-lg"><p id="minutes" class="text-2xl font-bold">00</p><p class="text-xs">Menit</p></div>
                <div class="p-2 bg-brand-green text-white rounded-lg"><p id="seconds" class="text-2xl font-bold">00</p><p class="text-xs">Detik</p></div>
            </div>

             @php
                $timezone = 'Asia/Jakarta';
                $startTime = \Carbon\Carbon::parse(($event->date ?? '2025-12-28') . ' ' . ($event->akad_time ?? '09:00'), $timezone);
                $resepsiTimeParts = explode('-', $event->resepsi_time ?? '11:00 - 14:00');
                $endTimeString = trim(end($resepsiTimeParts));
                $endTime = \Carbon\Carbon::parse(($event->date ?? '2025-12-28') . ' ' . $endTimeString, $timezone);
                $gcal_start = $startTime->copy()->utc()->format('Ymd\THis\Z');
                $gcal_end = $endTime->copy()->utc()->format('Ymd\THis\Z');
                $gcal_title = urlencode('Pernikahan ' . ($event->groom_name ?? 'Putra') . ' & ' . ($event->bride_name ?? 'Putri'));
                $gcal_details = urlencode("Anda diundang ke acara pernikahan " . ($event->groom_name ?? 'Putra') . " & " . ($event->bride_name ?? 'Putri') . ".");
                $gcal_location = urlencode($event->resepsi_location ?? $event->akad_location ?? '');
                $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$gcal_title}&dates={$gcal_start}/{$gcal_end}&details={$gcal_details}&location={$gcal_location}";
            @endphp
            <a href="{{ $googleCalendarUrl }}" target="_blank" class="mt-6 inline-block bg-brand-green text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">
                <i class="fa-regular fa-calendar-check"></i> Simpan Tanggal
            </a>

            <div class="mt-8">
                <p class="text-sm">Kepada Yth.</p>
                <p class="font-title text-2xl mt-1 text-brand-gold">{{ $guest->name ?? 'Tamu Undangan' }}</p>
            </div>
            
            <button id="open-invitation" class="mt-8 bg-brand-gold text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-all duration-300 hover:scale-105 flex items-center gap-2 mx-auto">
                <i class="fa-regular fa-envelope-open"></i>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <main id="main-content" class="relative">
        <div class="max-w-3xl mx-auto bg-brand-bg shadow-2xl relative">
            
            <section class="py-20 px-6 text-center relative" data-aos="fade-up">
                 <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute top-0 left-0 w-48 opacity-90 z-0">
                 <img src="https://i.ibb.co/fMLm82Q/rose-line-art.png" alt="ornamen" class="absolute bottom-0 right-0 w-48 opacity-20">

                <div class="relative z-10">
                    <p>Assalamu’alaikum Warahmatullahi Wabarakatuh</p>
                    <p class="mt-4 max-w-lg mx-auto">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami.</p>
                    
                    <div class="mt-12 flex flex-col items-center gap-8">
                        <div class="text-center">
                            <div class="w-48 h-64 mx-auto shadow-lg rounded-md overflow-hidden">
                                 <img src="{{ $event->bride_photo ?? 'https://i.ibb.co/Jqj3Gfg/bride-61-F436-B5.jpg' }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="font-title text-4xl mt-4 text-brand-gold">{{ $event->bride_name ?? 'Putri Cantika Sari' }}</h3>
                            <p class="mt-2 font-semibold">Putri Pertama dari</p>
                            <p class="text-sm">{{ $event->bride_parents ?? 'Bapak Abdul Rozak dan Ibu Adelia Marni' }}</p>
                            @if(!empty($event->bride_instagram))
                                <a href="https://instagram.com/{{ $event->bride_instagram }}" target="_blank" class="inline-block mt-2 text-sm bg-brand-green text-white px-3 py-1 rounded-full"><i class="fa-brands fa-instagram"></i> {{ $event->bride_instagram }}</a>
                            @endif
                        </div>
                        
                        <p class="font-title text-5xl text-brand-gold">&</p>
                        
                        <div class="text-center">
                            <div class="w-48 h-64 mx-auto shadow-lg rounded-md overflow-hidden">
                                <img src="{{ $event->groom_photo ?? 'https://i.ibb.co/zZfT4b2/groom-61-F436-B5.jpg' }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="font-title text-4xl mt-4 text-brand-gold">{{ $event->groom_name ?? 'Putra Andika Pratama' }}</h3>
                            <p class="mt-2 font-semibold">Putra Pertama dari</p>
                            <p class="text-sm">{{ $event->groom_parents ?? 'Bapak Deni Bastian dan Ibu Aisha Dania' }}</p>
                            @if(!empty($event->groom_instagram))
                                <a href="https://instagram.com/{{ $event->groom_instagram }}" target="_blank" class="inline-block mt-2 text-sm bg-brand-green text-white px-3 py-1 rounded-full"><i class="fa-brands fa-instagram"></i> {{ $event->groom_instagram }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-20 px-6 text-center bg-brand-green text-white" data-aos="fade-up">
                <p class="italic leading-relaxed max-w-xl mx-auto font-serif text-lg">
                    "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                </p>
                <p class="font-semibold mt-4">(Q.S Ar-Rum : 21)</p>
            </section>
            
            <section id="acara" class="py-20 px-6 text-center">
                <div class="flex flex-col gap-8 max-w-md mx-auto">
                    <div class="bg-brand-green text-white p-6 rounded-2xl shadow-lg relative overflow-hidden" data-aos="fade-up">
                        <img src="https://i.ibb.co/fMLm82Q/rose-line-art.png" alt="ornamen" class="absolute top-0 left-0 w-full h-full object-cover opacity-10">
                        <div class="relative">
                            <h2 class="font-title text-5xl">Akad Nikah</h2>
                            <div class="mt-6 flex justify-center items-center gap-4 font-semibold text-xl">
                                <span>Minggu</span>
                                <div class="text-center leading-none">
                                    <span class="font-title text-4xl">{{ \Carbon\Carbon::parse($event->date ?? '2025-12-28')->format('d') }}</span>
                                    <span class="block text-sm">{{ \Carbon\Carbon::parse($event->date ?? '2025-12-28')->format('Y') }}</span>
                                </div>
                                <span>Desember</span>
                            </div>
                            <p class="mt-4"><i class="fa-regular fa-clock"></i> {{ $event->akad_time ?? '08:00 WIB' }}</p>
                            <div class="mt-4">
                                <p class="font-semibold">Lokasi Acara</p>
                                <p>{{ $event->akad_location ?? 'Menara 165, JL. TB Simatupang Jakarta Selatan' }}</p>
                            </div>
                            @if(!empty($event->akad_maps_url))
                                <a href="{{ $event->akad_maps_url }}" target="_blank" class="mt-6 inline-block bg-white/20 text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-white/30 transition-transform hover:scale-105"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-brand-green text-white p-6 rounded-2xl shadow-lg relative overflow-hidden" data-aos="fade-up">
                         <img src="https://i.ibb.co/fMLm82Q/rose-line-art.png" alt="ornamen" class="absolute top-0 left-0 w-full h-full object-cover opacity-10">
                        <div class="relative">
                            <h2 class="font-title text-5xl">Resepsi</h2>
                            <div class="mt-6 flex justify-center items-center gap-4 font-semibold text-xl">
                                <span>Minggu</span>
                                <div class="text-center leading-none">
                                    <span class="font-title text-4xl">{{ \Carbon\Carbon::parse($event->date ?? '2025-12-28')->format('d') }}</span>
                                    <span class="block text-sm">{{ \Carbon\Carbon::parse($event->date ?? '2025-12-28')->format('Y') }}</span>
                                </div>
                                <span>Desember</span>
                            </div>
                            <p class="mt-4"><i class="fa-regular fa-clock"></i> {{ $event->resepsi_time ?? '09:00 - 13:00 WIB' }}</p>
                            <div class="mt-4">
                                <p class="font-semibold">Lokasi Acara</p>
                                <p>{{ $event->resepsi_location ?? 'Menara 165, JL. TB Simatupang Jakarta Selatan' }}</p>
                            </div>
                             @if(!empty($event->resepsi_maps_url))
                                <a href="{{ $event->resepsi_maps_url }}" target="_blank" class="mt-6 inline-block bg-white/20 text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-white/30 transition-transform hover:scale-105"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                            @endif
                        </div>
                    </div>

                    @if(!empty($event->streaming_url))
                    <div class="bg-brand-green text-white p-6 rounded-2xl shadow-lg relative overflow-hidden" data-aos="fade-up">
                        <img src="https://i.ibb.co/fMLm82Q/rose-line-art.png" alt="ornamen" class="absolute top-0 left-0 w-full h-full object-cover opacity-10">
                        <div class="relative">
                             <h2 class="font-title text-5xl">Live Streaming</h2>
                             <p class="mt-4 max-w-md mx-auto">Saksikan momen bahagia kami secara virtual melalui tautan di bawah ini.</p>
                             <a href="{{ $event->streaming_url }}" target="_blank" class="mt-6 inline-block bg-white/20 text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-white/30 transition-transform hover:scale-105">
                                <i class="fa-solid fa-video"></i> Lihat Live Streaming
                             </a>
                         </div>
                    </div>
                    @endif
                </div>
            </section>

             <section class="py-20 px-6">
                <h2 class="font-title text-5xl text-center text-brand-gold" data-aos="fade-up">Love Story</h2>
                <div class="mt-12 max-w-md mx-auto relative border-l-2 border-brand-green/50 pl-8 space-y-16">
                    <div class="relative" data-aos="fade-left">
                        <div class="absolute -left-[44px] top-0 w-6 h-6 bg-brand-green rounded-full border-4 border-brand-bg flex items-center justify-center"><i class="fa-solid fa-heart text-xs text-white"></i></div>
                        <img src="https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg" class="rounded-lg shadow-lg mb-4" alt="First Meet">
                        <p class="font-semibold font-title text-xl text-brand-gold">25 Agustus 2023</p>
                        <p class="text-sm">Berawal dari tempat pekerjaan Cianjur 2023, kami mengenal satu sama lain dan belum ada benih cinta kala itu, hanya sebatas teman kerja.</p>
                    </div>
                     <div class="relative" data-aos="fade-left">
                        <div class="absolute -left-[44px] top-0 w-6 h-6 bg-brand-green rounded-full border-4 border-brand-bg flex items-center justify-center"><i class="fa-solid fa-heart text-xs text-white"></i></div>
                        <img src="https://i.ibb.co/zZfT4b2/groom-61-F436-B5.jpg" class="rounded-lg shadow-lg mb-4" alt="Getting Serious">
                        <p class="font-semibold font-title text-xl text-brand-gold">03 Juni 2024</p>
                        <p class="text-sm">Setelah cukup mengenal satu sama lain, satu tahun kurang lebih nya kami menjalin hubungan. Akhirnya kita memutuskan untuk melanjutkan ke Hubungan yang lebih serius mempertemukan kedua keluarga.</p>
                    </div>
                     <div class="relative" data-aos="fade-left">
                        <div class="absolute -left-[44px] top-0 w-6 h-6 bg-brand-green rounded-full border-4 border-brand-bg flex items-center justify-center"><i class="fa-solid fa-heart text-xs text-white"></i></div>
                        <img src="https://i.ibb.co/k2v0N06/couple-162300-FF.jpg" class="rounded-lg shadow-lg mb-4" alt="Proposal">
                        <p class="font-semibold font-title text-xl text-brand-gold">29 Desember 2025</p>
                        <p class="text-sm">Sampai tanggal ini kami melaksanakan akad terlebih dahulu dan akhirnya kami mengubah status menjadi suami istri.</p>
                    </div>
                </div>
            </section>

            <section class="py-20 px-6 text-center">
                <h2 class="font-title text-5xl text-brand-gold" data-aos="fade-up">Our Moments</h2>
                <div class="mt-12 max-w-xl mx-auto">
                    @if(!empty($event->video_url))
                        <div class="aspect-video mb-8" data-aos="fade-up">
                            <iframe class="w-full h-full rounded-lg shadow-lg" src="{{ $event->video_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2" data-aos="fade-up" data-aos-delay="200">
                         @php
                            $galleryPhotos = $photos->isNotEmpty() ? $photos : collect([
                                (object)['path' => 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg'],
                                (object)['path' => 'https://i.ibb.co/k2v0N06/couple-162300-FF.jpg'],
                                (object)['path' => 'https://i.ibb.co/Jqj3Gfg/bride-61-F436-B5.jpg'],
                                (object)['path' => 'https://i.ibb.co/zZfT4b2/groom-61-F436-B5.jpg'],
                                (object)['path' => 'https://i.ibb.co/dKqT7Jv/gallery-5.jpg'],
                                (object)['path' => 'https://i.ibb.co/6gGk9sv/gallery-6.jpg'],
                            ]);
                        @endphp
                        @foreach($galleryPhotos as $photo)
                        <a href="{{ $photo->path }}" data-fslightbox="gallery" class="overflow-hidden rounded-lg shadow-lg block">
                            <img src="{{ $photo->path }}" class="w-full h-full object-cover aspect-square hover:scale-110 transition-transform duration-500">
                        </a>
                        @endforeach
                    </div>
                </div>
            </section>
            
            <section class="py-20 px-6 relative">
                 <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute -bottom-10 -left-10 w-48 md:w-80 opacity-90 -scale-y-100 z-0">
                 <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute -bottom-10 -right-10 w-48 md:w-80 opacity-90 rotate-180 -scale-y-100 z-0">

                <div class="relative z-10">
                    <div class="bg-brand-green text-white text-center py-12 px-6 rounded-2xl shadow-lg" data-aos="fade-up">
                        <h2 class="font-title text-5xl">Wedding Gift</h2>
                        <p class="mt-4 max-w-md mx-auto">Doa Restu Anda merupakan karunia yang sangat berarti bagi kami.</p>
                        <p class="mt-2 max-w-md mx-auto">Dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi melalui dibawah ini.</p>
                        <button id="gift-button" class="mt-6 inline-flex items-center gap-2 bg-brand-gold text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-transform">
                            <i class="fa-solid fa-gift"></i> Klik di Sini
                        </button>
                    </div>

                    <div class="max-w-md mx-auto p-6 bg-brand-light -mt-10 relative rounded-2xl shadow-lg">
                        <div class="text-center" data-aos="fade-up">
                            <h2 class="font-title text-5xl text-brand-gold">Ucapan & RSVP</h2>
                            <p class="mt-2">Berikan doa dan ucapan terbaik untuk kami.</p>
                        </div>
                        <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
                            <form onsubmit="alert('Ini adalah mode demo. Form tidak dapat dikirim.'); return false;">
                                <div class="mb-4">
                                    <input type="text" id="name" name="name" class="block w-full border-brand-gold/50 rounded-md shadow-sm bg-white/50 p-3" value="{{ $guest->name ?? old('name') }}" required readonly placeholder="Nama">
                                </div>
                                <div class="mb-4">
                                    <textarea id="message" name="message" rows="4" class="block w-full border-brand-gold/50 rounded-md shadow-sm bg-white/50 p-3" placeholder="Tulis ucapan..." required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Konfirmasi Kehadiran</label>
                                    <div class="flex gap-4">
                                       <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-green/50 rounded-md hover:bg-brand-green/10 transition-colors" data-value="Hadir"><i class="fa-solid fa-check"></i> Hadir</button>
                                       <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-green/50 rounded-md hover:bg-brand-green/10 transition-colors" data-value="Tidak Hadir"><i class="fa-solid fa-xmark"></i> Tidak Hadir</button>
                                    </div>
                                    <input type="hidden" name="status" id="status-input" required>
                                </div>
                                <div class="text-center mt-6">
                                    <button type="submit" class="bg-brand-green text-white font-semibold py-3 px-10 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                
                    <div class="mt-12 space-y-4 max-w-md mx-auto">
                         @forelse($rsvps as $rsvp)
                            <div class="bg-white/50 p-4 rounded-lg shadow-sm">
                                <p class="font-semibold font-title text-lg text-brand-gold">{{ $rsvp->name }}</p>
                                <p class="text-sm mt-1">{{ $rsvp->message }}</p>
                            </div>
                         @empty
                            <div class="bg-white/50 p-4 rounded-lg shadow-sm">
                                <p class="font-semibold font-title text-lg text-brand-gold">Nadya</p>
                                <p class="text-sm mt-1">Semoga lancar</p>
                            </div>
                            <div class="bg-white/50 p-4 rounded-lg shadow-sm">
                                <p class="font-semibold font-title text-lg text-brand-gold">Oskar</p>
                                <p class="text-sm mt-1">Congrats</p>
                            </div>
                         @endforelse
                    </div>
                </div>
            </section>
            
            <footer class="py-20 px-6 text-center relative">
                 <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute -top-10 -left-10 w-48 md:w-80 opacity-90 z-0">
                 <img src="https://i.ibb.co/L6V2yJg/green-gold-corner.png" alt="ornamen" class="absolute -bottom-10 -right-10 w-48 md:w-80 opacity-90 rotate-180 z-0">

                <div data-aos="fade-up" class="max-w-md mx-auto p-6 bg-brand-light/50 backdrop-blur-sm rounded-2xl shadow-lg">
                    <div class="w-48 h-48 mx-auto shadow-lg rounded-md overflow-hidden mb-6 p-2 bg-white/50">
                        <img src="{{ $event->photo_url ?? 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg' }}" class="w-full h-full object-cover rounded-sm">
                    </div>
                    <h2 class="font-title text-5xl text-brand-gold">Terima Kasih</h2>
                    <p class="mt-4 max-w-md mx-auto">Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i, berkenan hadir dan memberikan do'a restu kepada kami.</p>
                    <p class="mt-6">Wassalamu’alaikum warahmatullahi wabarakatuh</p>
                    <p class="mt-8">Kami Yang Berbahagia</p>
                    <p class="font-title text-4xl mt-2 text-brand-gold">{{ $event->bride_name ?? 'Putri' }} & {{ $event->groom_name ?? 'Putra' }}</p>
                </div>
            </footer>
        </div>
    </main>

    <div id="gift-modal" class="fixed inset-0 bg-black/60 z-[1000] flex justify-center items-center p-4 hidden">
        <div id="gift-modal-content" class="bg-brand-light rounded-lg p-6 w-full max-w-sm text-center relative transform scale-95 transition-transform duration-300">
            <button id="close-modal-btn" class="absolute top-2 right-3 text-2xl text-brand-green/50 hover:text-brand-green">&times;</button>
            <h3 class="font-title text-4xl text-brand-gold mb-4">Kirim Hadiah</h3>
            <div class="space-y-4 text-left">
                <div class="bg-white/50 p-4 rounded-lg" data-copy-content="1234567890">
                    <p class="font-bold">BCA</p>
                    <p>1234567890 (a.n Putra Andika Pratama)</p>
                    <button class="copy-btn text-sm mt-2 bg-brand-green/80 text-white px-3 py-1 rounded-full hover:bg-brand-green">Salin Rekening</button>
                </div>
                 <div class="bg-white/50 p-4 rounded-lg" data-copy-content="0987654321">
                    <p class="font-bold">Mandiri</p>
                    <p>0987654321 (a.n Putri Cantika Sari)</p>
                    <button class="copy-btn text-sm mt-2 bg-brand-green/80 text-white px-3 py-1 rounded-full hover:bg-brand-green">Salin Rekening</button>
                </div>
            </div>
             <p id="copy-feedback" class="text-sm mt-4 text-green-700 opacity-0 transition-opacity">Berhasil disalin!</p>
        </div>
    </div>


    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @if(file_exists(public_path('js/fslightbox.js')))
        <script src="{{ asset('js/fslightbox.js') }}"></script>
    @else
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/fslightbox.min.js"></script>
    @endif
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 800, once: true, offset: 50 });

        const cover = document.getElementById('cover');
        const mainContent = document.getElementById('main-content');
        const openButton = document.getElementById('open-invitation');
        const audio = document.getElementById('background-music');
        const musicControl = document.getElementById('music-control');
        const musicIcon = document.getElementById('music-icon');

        document.body.style.opacity = 1;

        openButton.addEventListener('click', function() {
            document.documentElement.style.scrollBehavior = 'smooth';
            audio.play().catch(error => console.log("Autoplay was prevented."));
            musicControl.classList.remove('hidden');
            cover.style.transition = 'opacity 1s ease-out';
            cover.style.opacity = 0;
            setTimeout(() => {
                cover.style.display = 'none';
                mainContent.style.display = 'block';
                document.body.style.overflowY = 'auto'; 
            }, 1000);
        });

        musicControl.addEventListener('click', function() {
            if (audio.paused) {
                audio.play();
                musicIcon.classList.remove('fa-volume-xmark');
                musicIcon.classList.add('fa-volume-high');
            } else {
                audio.pause();
                musicIcon.classList.remove('fa-volume-high');
                musicIcon.classList.add('fa-volume-xmark');
            }
        });

        const countdownDate = new Date("{{ $event->date ?? '2025-12-28' }}T{{ explode(':', ($event->akad_time ?? '08:00'))[0] }}:00:00").getTime();
        if (document.getElementById('countdown')) {
            const interval = setInterval(function() {
                const now = new Date().getTime();
                const distance = countdownDate - now;
                if (distance < 0) {
                    clearInterval(interval);
                    document.getElementById('countdown').innerHTML = "<p class='col-span-4 text-lg'>Acara telah berlangsung!</p>";
                    return;
                }
                document.getElementById('days').innerText = String(Math.floor(distance / (1000*60*60*24))).padStart(2, '0');
                document.getElementById('hours').innerText = String(Math.floor((distance % (1000*60*60*24))/(1000*60*60))).padStart(2, '0');
                document.getElementById('minutes').innerText = String(Math.floor((distance % (1000*60*60))/(1000*60))).padStart(2, '0');
                document.getElementById('seconds').innerText = String(Math.floor((distance % (1000*60))/1000)).padStart(2, '0');
            }, 1000);
        }

        // RSVP Buttons
        const rsvpButtons = document.querySelectorAll('.rsvp-btn');
        const statusInput = document.getElementById('status-input');
        rsvpButtons.forEach(button => {
            button.addEventListener('click', () => {
                statusInput.value = button.dataset.value;
                rsvpButtons.forEach(btn => btn.classList.remove('bg-brand-green', 'text-white'));
                button.classList.add('bg-brand-green', 'text-white');
            });
        });

        // Gift Modal
        const giftModal = document.getElementById('gift-modal');
        const giftModalContent = document.getElementById('gift-modal-content');
        const openModalBtn = document.getElementById('gift-button');
        const closeModalBtn = document.getElementById('close-modal-btn');

        const openModal = () => {
            giftModal.classList.remove('hidden');
            setTimeout(() => giftModalContent.classList.add('scale-100'), 10);
        };
        const closeModal = () => {
            giftModalContent.classList.remove('scale-100');
            setTimeout(() => giftModal.classList.add('hidden'), 300);
        };

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        giftModal.addEventListener('click', (e) => (e.target === giftModal) && closeModal());

        // Copy gift info
        const copyFeedback = document.getElementById('copy-feedback');
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const parent = e.target.closest('div[data-copy-content]');
                const contentToCopy = parent.dataset.copyContent;
                navigator.clipboard.writeText(contentToCopy).then(() => {
                    copyFeedback.classList.remove('opacity-0');
                    setTimeout(() => copyFeedback.classList.add('opacity-0'), 2000);
                });
            });
        });
    });
    </script>
</body>
</html>