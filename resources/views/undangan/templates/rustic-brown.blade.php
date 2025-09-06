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
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Poppins', 'sans-serif'],
              serif: ['Lora', 'serif'],
            },
            colors: {
              'brand-dark': '#5D483C',
              'brand-brown': '#866552',
              'brand-card': '#A18262',
              'brand-bg': '#F5EFE6',
            }
          }
        }
      }
    </script>

    <style>
        body {
            background-color: #F5EFE6;
            color: #5D483C;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            font-family: 'Poppins', 'sans-serif';
        }
        #main-content { display: none; }
        .font-title { font-family: 'Lora', serif; }
        .bg-texture {
            background-image: url('https://i.ibb.co/VtB0s2W/bg-texture.png');
            background-size: cover;
            background-position: center;
        }
        .arch-frame {
            border-top-left-radius: 150px;
            border-top-right-radius: 150px;
            overflow: hidden;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-texture">
    
    <audio id="background-music" loop> <source src="{{ $event->music_url ? asset('storage/' . $event->music_url) : asset('music/default.mp3') }}" type="audio/mpeg"> </audio>
    <div id="music-control" class="fixed bottom-5 right-5 z-[999] cursor-pointer p-3 bg-white/50 backdrop-blur-md rounded-full shadow-lg hidden">
        <i id="music-icon" class="fa-solid fa-volume-high text-brand-dark text-xl w-6 h-6 text-center"></i>
    </div>

    <div id="cover" class="h-screen w-full flex flex-col justify-center items-center text-center p-4 relative overflow-hidden">
        <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute top-0 left-0 w-48 md:w-72 opacity-80">
        <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute top-0 right-0 w-48 md:w-72 opacity-80 -scale-x-100">

        <div class="relative z-20" data-aos="zoom-in">
            <p class="text-sm tracking-widest">THE WEDDING OF</p>
            <div class="my-6 relative w-48 h-64 mx-auto">
                <div class="arch-frame w-full h-full">
               </div>
                <img src="https://i.ibb.co/b3Xh4t2/pampas-ornament.png" alt="pampas" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[150%] max-w-none">
            </div>
            <h1 class="font-title text-5xl text-brand-dark">{{ $event->bride_name ?? 'Mempelai Wanita' }} & {{ $event->groom_name ?? 'Mempelai Pria' }}</h1>
            <p class="mt-4 font-semibold">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>

            <div id="countdown" class="grid grid-cols-4 gap-2 mt-8 text-center max-w-xs mx-auto">
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="days" class="text-2xl font-bold">00</p><p class="text-xs">Hari</p></div>
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="hours" class="text-2xl font-bold">00</p><p class="text-xs">Jam</p></div>
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="minutes" class="text-2xl font-bold">00</p><p class="text-xs">Menit</p></div>
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="seconds" class="text-2xl font-bold">00</p><p class="text-xs">Detik</p></div>
            </div>

            @php
                 $timezone = 'Asia/Jakarta';
                 $startTime = \Carbon\Carbon::parse($event->date . ' ' . ($event->akad_time ?? '09:00'), $timezone);
                 $resepsiTimeParts = explode('-', $event->resepsi_time ?? '11:00 - 14:00');
                 $endTimeString = trim(end($resepsiTimeParts));
                 $endTime = \Carbon\Carbon::parse($event->date . ' ' . $endTimeString, $timezone);
                 $gcal_start = $startTime->copy()->utc()->format('Ymd\THis\Z');
                 $gcal_end = $endTime->copy()->utc()->format('Ymd\THis\Z');
                 $gcal_title = urlencode('Pernikahan ' . $event->groom_name . ' & ' . $event->bride_name);
                 $gcal_details = urlencode("Anda diundang ke acara pernikahan " . $event->groom_name . " & " . $event->bride_name);
                 $gcal_location = urlencode($event->resepsi_location ?? $event->akad_location);
                 $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$gcal_title}&dates={$gcal_start}/{$gcal_end}&details={$gcal_details}&location={$gcal_location}";
            @endphp
            <a href="{{ $googleCalendarUrl }}" target="_blank" class="mt-6 inline-block bg-brand-brown text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">
                <i class="fa-regular fa-calendar-check"></i> Simpan Tanggal
            </a>

            <div class="mt-8">
                <p class="text-sm">Kepada Yth.</p>
                <p class="font-title text-2xl mt-1">{{ $guest->name ?? 'Tamu Undangan' }}</p>
            </div>
            
            <button id="open-invitation" class="mt-8 bg-brand-dark text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-all duration-300 hover:scale-105 flex items-center gap-2 mx-auto">
                <i class="fa-regular fa-envelope-open"></i>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <main id="main-content" class="relative">
        <div class="max-w-3xl mx-auto bg-brand-bg shadow-2xl relative">
            
            <section class="py-20 px-6 text-center relative" data-aos="fade-up">
                <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute top-0 left-0 w-48 opacity-80">
                <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute top-0 right-0 w-48 opacity-80 -scale-x-100">

                <p>Assalamu’alaikum Warahmatullahi Wabarakatuh</p>
                <p class="mt-4 max-w-lg mx-auto">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami.</p>
                
                <div class="mt-12 flex flex-col items-center gap-8">
                    <div class="text-center">
                        <div class="arch-frame w-48 h-64 mx-auto shadow-lg">
                             <img src="{{ $event->bride_photo ? asset('storage/' . $event->bride_photo) : 'https://placehold.co/400x560/F5EFE6/5D483C?text=Wanita' }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-title text-4xl mt-4 text-brand-dark">{{ $event->bride_name }}</h3>
                        <p class="mt-2 font-semibold">Putri dari</p>
                        <p>{{ $event->bride_parents }}</p>
                        @if($event->bride_instagram)
                            <a href="https://instagram.com/{{ str_replace('@', '', $event->bride_instagram) }}" target="_blank" class="inline-block mt-2 text-sm bg-brand-dark text-white px-3 py-1 rounded-full"><i class="fa-brands fa-instagram"></i> {{ $event->bride_instagram }}</a>
                        @endif
                    </div>
                    
                    <p class="font-title text-5xl">&</p>
                    
                    <div class="text-center">
                         <div class="arch-frame w-48 h-64 mx-auto shadow-lg">
                            <img src="{{ $event->groom_photo ? asset('storage/' . $event->groom_photo) : 'https://placehold.co/400x560/F5EFE6/5D483C?text=Pria' }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-title text-4xl mt-4 text-brand-dark">{{ $event->groom_name }}</h3>
                        <p class="mt-2 font-semibold">Putra dari</p>
                        <p>{{ $event->groom_parents }}</p>
                        @if($event->groom_instagram)
                            <a href="https://instagram.com/{{ str_replace('@', '', $event->groom_instagram) }}" target="_blank" class="inline-block mt-2 text-sm bg-brand-dark text-white px-3 py-1 rounded-full"><i class="fa-brands fa-instagram"></i> {{ $event->groom_instagram }}</a>
                        @endif
                    </div>
                </div>
            </section>

            <section class="py-20 px-6 text-center bg-brand-brown text-white" data-aos="fade-up">
                <p class="italic leading-relaxed max-w-xl mx-auto font-serif">
                    "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                </p>
                <p class="font-semibold mt-4">(Q.S Ar-Rum : 21)</p>
            </section>
            
            <section id="acara" class="py-20 px-6 text-center bg-cover bg-center" style="background-image: url('https://i.ibb.co/xL1cmgR/venue-bg.png');">
                <div class="flex flex-col gap-8 max-w-md mx-auto">
                    <div class="bg-brand-brown/90 text-white p-6 rounded-2xl shadow-lg" data-aos="fade-up">
                        <h2 class="font-title text-5xl">Akad Nikah</h2>
                        <p class="mt-6 font-semibold text-xl">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p class="mt-4"><i class="fa-regular fa-clock"></i> {{ $event->akad_time }}</p>
                        <div class="mt-4">
                            <p class="font-semibold">Lokasi Acara</p>
                            <p>{{ $event->akad_location }}</p>
                        </div>
                        @if($event->akad_maps_url)
                            <a href="{{ $event->akad_maps_url }}" target="_blank" class="mt-6 inline-block bg-white text-brand-dark font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                        @endif
                    </div>
                    
                    <div class="bg-brand-brown/90 text-white p-6 rounded-2xl shadow-lg" data-aos="fade-up">
                        <h2 class="font-title text-5xl">Resepsi</h2>
                        <p class="mt-6 font-semibold text-xl">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p class="mt-4"><i class="fa-regular fa-clock"></i> {{ $event->resepsi_time }}</p>
                        <div class="mt-4">
                            <p class="font-semibold">Lokasi Acara</p>
                            <p>{{ $event->resepsi_location }}</p>
                        </div>
                         @if($event->resepsi_maps_url)
                            <a href="{{ $event->resepsi_maps_url }}" target="_blank" class="mt-6 inline-block bg-white text-brand-dark font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                        @endif
                    </div>

                    @if($event->streaming_url)
                    <div class="bg-brand-brown/90 text-white p-6 rounded-2xl shadow-lg" data-aos="fade-up">
                         <h2 class="font-title text-5xl">Live Streaming</h2>
                         <p class="mt-4 max-w-md mx-auto">Saksikan momen bahagia kami secara virtual melalui tautan di bawah ini.</p>
                         <a href="{{ $event->streaming_url }}" target="_blank" class="mt-6 inline-block bg-white text-brand-dark font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">
                            <i class="fa-solid fa-video"></i> Lihat Live Streaming
                         </a>
                    </div>
                    @endif
                </div>
            </section>

             {{-- Bagian Love Story Dinamis --}}
             @if($event->story_1_title)
             <section class="py-20 px-6">
                <h2 class="font-title text-5xl text-center" data-aos="fade-up">Love Story</h2>
                <div class="mt-12 max-w-md mx-auto relative border-l-2 border-brand-brown/50 pl-8 space-y-16">
                    <div class="relative" data-aos="fade-left">
                        <div class="absolute -left-[44px] top-0 w-6 h-6 bg-brand-brown rounded-full border-4 border-brand-bg flex items-center justify-center"><i class="fa-solid fa-heart text-xs text-white"></i></div>
                        @if($event->story_1_image)
                        <img src="{{ asset('storage/' . $event->story_1_image) }}" class="rounded-lg shadow-lg mb-4" alt="{{ $event->story_1_title }}">
                        @endif
                        <p class="font-semibold font-title text-xl">{{ \Carbon\Carbon::parse($event->story_1_date)->isoFormat('D MMMM YYYY') }}</p>
                        <p class="font-bold text-lg">{{ $event->story_1_title }}</p>
                        <p class="text-sm">{{ $event->story_1_description }}</p>
                    </div>
                    @if($event->story_2_title)
                     <div class="relative" data-aos="fade-left">
                        <div class="absolute -left-[44px] top-0 w-6 h-6 bg-brand-brown rounded-full border-4 border-brand-bg flex items-center justify-center"><i class="fa-solid fa-heart text-xs text-white"></i></div>
                        @if($event->story_2_image)
                        <img src="{{ asset('storage/' . $event->story_2_image) }}" class="rounded-lg shadow-lg mb-4" alt="{{ $event->story_2_title }}">
                        @endif
                        <p class="font-semibold font-title text-xl">{{ \Carbon\Carbon::parse($event->story_2_date)->isoFormat('D MMMM YYYY') }}</p>
                        <p class="font-bold text-lg">{{ $event->story_2_title }}</p>
                        <p class="text-sm">{{ $event->story_2_description }}</p>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            @if($photos->isNotEmpty())
            <section class="py-20 px-6 text-center">
                <h2 class="font-title text-5xl" data-aos="fade-up">Our Moments</h2>
                <div class="mt-12 max-w-xl mx-auto">
                    @if($event->video_url)
                        <div class="aspect-video mb-8" data-aos="fade-up">
                            <iframe class="w-full h-full rounded-lg shadow-lg" src="{{ $event->video_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2" data-aos="fade-up" data-aos-delay="200">
                        @foreach($photos as $photo)
                        <a href="{{ asset('storage/' . $photo->path) }}" data-fslightbox="gallery" class="overflow-hidden rounded-lg shadow-lg block">
                            <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover aspect-square hover:scale-110 transition-transform duration-500">
                        </a>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            
            {{-- Bagian Wedding Gift Dinamis --}}
            @if($event->gift_bank_1_name || $event->gift_address)
            <section class="py-20 px-6 text-center" data-aos="fade-up">
                <h2 class="font-title text-5xl">Wedding Gift</h2>
                <p class="mt-4 max-w-md mx-auto">Kehadiran dan doa restu Anda adalah hadiah terindah bagi kami. Namun, jika Anda ingin memberikan tanda kasih, kami telah menyediakannya di bawah ini.</p>
                <button id="gift-button" class="mt-6 inline-flex items-center gap-2 bg-brand-dark text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-transform">
                    <i class="fa-solid fa-gift"></i> Buka Hadiah
                </button>
            </section>
            @endif

            <section id="wedding-wishes" class="py-20 px-6">
                <div class="max-w-md mx-auto p-6 border-2 border-brand-brown/50 rounded-2xl">
                    <div class="text-center" data-aos="fade-up">
                        <h2 class="font-title text-5xl">Ucapan & RSVP</h2>
                        <p class="mt-2">Berikan doa dan ucapan terbaik untuk kami.</p>
                    </div>
                    <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
                        <form action="{{ route('rsvp.store', $event) }}" method="POST">
                             @csrf
                            @if(isset($guest) && $guest->uuid)
                            <input type="hidden" name="guest_uuid" value="{{ $guest->uuid }}">
                            @endif
                            <div class="mb-4">
                                <input type="text" id="name" name="name" class="block w-full border-brand-brown/50 rounded-md shadow-sm bg-white/50 p-3" value="{{ $guest->name ?? old('name') }}" required placeholder="Nama">
                            </div>
                            <div class="mb-4">
                                <textarea id="message" name="message" rows="4" class="block w-full border-brand-brown/50 rounded-md shadow-sm bg-white/50 p-3 focus:ring-brand-dark focus:border-brand-dark" placeholder="Tulis ucapan..." required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Konfirmasi Kehadiran</label>
                                <div class="flex gap-4">
                                   <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-brown rounded-md hover:bg-brand-brown/50 transition-colors" data-value="Hadir"><i class="fa-solid fa-check"></i> Hadir</button>
                                   <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-brown rounded-md hover:bg-brand-brown/50 transition-colors" data-value="Tidak Hadir"><i class="fa-solid fa-xmark"></i> Tidak Hadir</button>
                                </div>
                                <input type="hidden" name="status" id="status-input" required>
                            </div>
                            <div class="text-center mt-6">
                                <button type="submit" class="bg-brand-dark text-white font-semibold py-3 px-10 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mt-12 space-y-4 max-w-md mx-auto">
                     @forelse($rsvps as $rsvp)
                        <div class="bg-white/20 p-4 rounded-lg shadow-sm">
                            <p class="font-semibold font-title text-lg">{{ $rsvp->name }}</p>
                            <p class="text-sm mt-1">{{ $rsvp->message }}</p>
                        </div>
                     @empty
                        <div class="bg-white/20 p-4 rounded-lg shadow-sm text-center">
                            <p class="text-sm">Jadilah yang pertama mengirimkan ucapan.</p>
                        </div>
                     @endforelse
                </div>
            </section>
            
            <footer class="py-20 px-6 text-center">
                <div data-aos="fade-up" class="max-w-md mx-auto p-6 border-2 border-brand-brown/50 rounded-2xl relative">
                     <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute -top-4 -left-4 w-24 opacity-80">
                     <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute -top-4 -right-4 w-24 opacity-80 -scale-x-100">
                     <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute -bottom-4 -left-4 w-24 opacity-80 -scale-y-100">
                     <img src="https://i.ibb.co/hH2V4Xq/corner-ornament.png" alt="ornamen" class="absolute -bottom-4 -right-4 w-24 opacity-80 -scale-x-100 -scale-y-100">

                    <div class="arch-frame w-48 h-64 mx-auto shadow-lg mb-6">
                        <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/400x560/F5EFE6/5D483C?text=Photo' }}" class="w-full h-full object-cover">
                    </div>
                    <h2 class="font-title text-5xl">Terima Kasih</h2>
                    <p class="mt-4 max-w-md mx-auto">Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i, berkenan hadir dan memberikan do'a restu kepada kami.</p>
                    <p class="mt-6">Wassalamu’alaikum warahmatullahi wabarakatuh</p>
                    <p class="mt-8">Kami Yang Berbahagia</p>
                    <p class="font-title text-4xl mt-2">{{ $event->bride_name }} & {{ $event->groom_name }}</p>
                </div>
            </footer>
        </div>
    </main>

    {{-- Modal Wedding Gift Dinamis --}}
    @if($event->gift_bank_1_name || $event->gift_address)
    <div id="gift-modal" class="fixed inset-0 bg-black/60 z-[1000] flex justify-center items-center p-4 hidden">
        <div id="gift-modal-content" class="bg-brand-dark bg-cover text-white rounded-lg p-6 w-full max-w-sm text-center relative transform scale-95 transition-transform duration-300" style="background-image: url('https://i.ibb.co/hH2V4Xq/corner-ornament.png')">
            <button id="close-modal-btn" class="absolute top-2 right-3 text-2xl text-white/50 hover:text-white">&times;</button>
            <h3 class="font-title text-4xl mb-6">Wedding Gift</h3>
            <div class="space-y-4 text-left">
                @if($event->gift_bank_1_name)
                <div class="bg-white/10 p-4 rounded-lg" data-copy-content="{{ $event->gift_bank_1_account }}">
                    <p class="font-bold">{{ $event->gift_bank_1_name }}</p>
                    <p class="font-semibold text-lg">{{ $event->gift_bank_1_account }}</p>
                    <p class="text-sm mt-1">Atas Nama</p>
                    <p class="font-semibold">{{ $event->gift_bank_1_holder }}</p>
                    <button class="copy-btn w-full text-center text-sm mt-3 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg"><i class="fa-regular fa-copy"></i> Salin</button>
                </div>
                @endif
                @if($event->gift_bank_2_name)
                 <div class="bg-white/10 p-4 rounded-lg" data-copy-content="{{ $event->gift_bank_2_account }}">
                    <p class="font-bold">{{ $event->gift_bank_2_name }}</p>
                    <p class="font-semibold text-lg">{{ $event->gift_bank_2_account }}</p>
                    <p class="text-sm mt-1">Atas Nama</p>
                    <p class="font-semibold">{{ $event->gift_bank_2_holder }}</p>
                    <button class="copy-btn w-full text-center text-sm mt-3 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg"><i class="fa-regular fa-copy"></i> Salin</button>
                </div>
                @endif
                @if($event->gift_address)
                <div class="bg-white/10 p-4 rounded-lg" data-copy-content="{{ $event->gift_address }}">
                    <p class="font-bold text-lg mb-2">Kirim Kado</p>
                    <p class="font-semibold">{{ $event->gift_address }}</p>
                     <button class="copy-btn w-full text-center text-sm mt-3 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg"><i class="fa-regular fa-copy"></i> Salin Alamat</button>
                </div>
                @endif
            </div>
             <p id="copy-feedback" class="text-sm mt-4 text-white opacity-0 transition-opacity">Berhasil disalin!</p>
        </div>
    </div>
    @endif

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

        const countdownDate = new Date("{{ $event->date }}T{{ explode(':', ($event->akad_time ?? '08:00'))[0] }}:00:00").getTime();
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

        const rsvpButtons = document.querySelectorAll('.rsvp-btn');
        const statusInput = document.getElementById('status-input');
        if(statusInput) {
            rsvpButtons.forEach(button => {
                button.addEventListener('click', () => {
                    statusInput.value = button.dataset.value;
                    rsvpButtons.forEach(btn => btn.classList.remove('bg-brand-brown', 'text-white'));
                    button.classList.add('bg-brand-brown', 'text-white');
                });
            });
        }

        const giftModal = document.getElementById('gift-modal');
        if (giftModal) {
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
        }
    });
    </script>
</body>
</html>

