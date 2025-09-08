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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Plus Jakarta Sans', 'sans-serif'],
              serif: ['Cormorant Garamond', 'serif'],
            },
            colors: {
              'brand-brown-dark': '#3A2E26',
              'brand-brown': '#A98F71',
              'brand-bg': '#D2B48C',
              'brand-bg-light': '#F5EFE6', // BARU: Warna background lebih terang
            }
          }
        }
      }
    </script>

    <style>
    /* UPDATE: Background gradasi halus */
    body {
        background-color: #D2B48C;
        background-image: linear-gradient(180deg, #F5EFE6, #D2B48C);
        color: #3A2E26;
        opacity: 0;
        transition: opacity 1.5s ease-in-out;
        font-family: 'Plus Jakarta Sans', 'sans-serif';
        padding-bottom: 80px;
    }
    #main-content { display: none; }
    .font-title { font-family: 'Cormorant Garamond', serif; }
    
    .bottom-nav a.active {
        color: #A98F71; /* brand-brown */
        transform: scale(1.1);
    }

    /* Section Mempelai dengan Video Background */
    #mempelai {
        position: relative;
        overflow: hidden;
        background-color: #D2B48C;
    }

    #mempelai-video-background {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: 1;
        transform: translateX(-50%) translateY(-50%);
        object-fit: cover;
        opacity: 0.15;
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(210, 180, 140, 0.7); /* brand-bg dengan transparansi */
        z-index: 2;
    }
    
    #mempelai .content-wrapper {
        position: relative;
        z-index: 3;
    }

    /* Gaya untuk RSVP button yang aktif */
    .rsvp-btn.active {
        background-color: #A98F71;
        color: white;
        border-color: #A98F71;
    }
    
    /* UPDATE: Gaya untuk ornamen menggunakan SVG yang disematkan langsung */
    .ornament {
        position: absolute;
        width: 120px; /* Ukuran bisa disesuaikan */
        height: 120px;
        /* GAMBAR ORNAMEN DIGANTI DENGAN KODE SVG LANGSUNG (DIJAMIN TAMPIL) */
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%233A2E26" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.4a9.4 9.4 0 1 0 0-18.8 9.4 9.4 0 0 0 0 18.8z"/><path d="M12 12a3.1 3.1 0 1 0 0-6.2 3.1 3.1 0 0 0 0 6.2z"/><path d="M12 2v2.5M12 22v-2.5M22 12h-2.5M4.5 12H2M19.4 4.6l-1.8 1.8M6.4 17.6l-1.8 1.8M19.4 19.4l-1.8-1.8M6.4 6.4L4.6 4.6"/></svg>');
        background-repeat: no-repeat;
        background-size: contain;
        opacity: 0.15; /* Opacity bisa dinaikkan jika kurang terlihat, misal 0.2 */
        z-index: 5;
    }
    .ornament-tl { top: 1rem; left: 1rem; }
    .ornament-tr { top: 1rem; right: 1rem; transform: scaleX(-1); }
    .ornament-bl { bottom: 1rem; left: 1rem; transform: scaleY(-1); }
    .ornament-br { bottom: 1rem; right: 1rem; transform: scale(-1, -1); }
</style>
</head>
<body class="antialiased overflow-x-hidden">
    
    <audio id="background-music" loop> <source src="{{ $event->music_url ?? asset('music/musik1.mp3') }}" type="audio/mpeg"> </source> </audio>
    <div id="music-control" class="fixed bottom-24 right-5 z-[999] cursor-pointer p-3 bg-white/50 backdrop-blur-md rounded-full shadow-lg hidden">
        <i id="music-icon" class="fa-solid fa-volume-high text-brand-brown-dark text-xl w-6 h-6 text-center"></i>
    </div>

    <div id="cover" class="h-screen w-full flex flex-col justify-center items-center text-center p-4 bg-brand-bg-light relative overflow-hidden">
        <div class="relative z-20">
            <p class="text-sm tracking-widest" data-aos="fade-down" data-aos-delay="200">THE WEDDING OF</p>
            <div class="my-6 relative w-48 h-48 mx-auto rounded-full overflow-hidden shadow-lg p-2 bg-white/50 ring-2 ring-brand-brown/50" data-aos="zoom-in" data-aos-delay="400">
                <img src="{{ $event->photo_url ? (Str::startsWith($event->photo_url, 'http') ? $event->photo_url : asset('storage/' . $event->photo_url)) : 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg' }}" class="w-full h-full object-cover rounded-full">
            </div>
            <h1 class="font-title text-5xl text-brand-brown-dark" data-aos="fade-up" data-aos-delay="600">{{ $event->bride_name ?? 'Putri' }} & {{ $event->groom_name ?? 'Putra' }}</h1>
            <p class="mt-4 font-semibold" data-aos="fade-up" data-aos-delay="700">{{ \Carbon\Carbon::parse($event->date ?? now())->isoFormat('dddd, D MMMM YYYY') }}</p>

            <div id="countdown" class="grid grid-cols-4 gap-2 mt-8 text-center max-w-xs mx-auto" data-aos="fade-up" data-aos-delay="800">
                <div class="p-2 bg-brand-brown-dark text-white rounded-lg"><p id="days" class="text-2xl font-bold">00</p><p class="text-xs">Hari</p></div>
                <div class="p-2 bg-brand-brown-dark text-white rounded-lg"><p id="hours" class="text-2xl font-bold">00</p><p class="text-xs">Jam</p></div>
                <div class="p-2 bg-brand-brown-dark text-white rounded-lg"><p id="minutes" class="text-2xl font-bold">00</p><p class="text-xs">Menit</p></div>
                <div class="p-2 bg-brand-brown-dark text-white rounded-lg"><p id="seconds" class="text-2xl font-bold">00</p><p class="text-xs">Detik</p></div>
            </div>

            <div class="mt-8 text-center" data-aos="fade-up" data-aos-delay="900">
                <p class="text-sm">Kepada Yth.</p>
                <p class="font-title text-3xl mt-1 text-brand-brown">{{ $guest->name ?? 'Tamu Undangan' }}</p>
            </div>

            <button id="open-invitation" class="mt-12 text-brand-brown-dark font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-2 mx-auto bg-white/50 backdrop-blur-sm" data-aos="fade-up" data-aos-delay="1000">
                <i class="fa-regular fa-envelope-open"></i>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <main id="main-content" class="relative">
    <div class="max-w-3xl mx-auto bg-brand-bg-light shadow-2xl relative">
        
        <section id="mempelai" class="py-20 px-6 text-center" data-aos="zoom-in-up">
            <video autoplay muted loop playsinline id="mempelai-video-background">
                <source src="{{ asset('bg-animasi/video percobaan.gif') }}" type="video/mp4">
                Browser Anda tidak mendukung tag video.
            </video>
            <div class="video-overlay"></div>

            <div class="content-wrapper">
                <p data-aos="fade-up" data-aos-delay="100">Assalamu’alaikum Warahmatullahi Wabarakatuh</p>
                <p class="mt-4 max-w-lg mx-auto" data-aos="fade-up" data-aos-delay="200">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami.</p>
                
                <div class="mt-12 flex flex-col items-center gap-8">
                    <div class="text-center" data-aos="fade-right" data-aos-delay="300">
                        <img src="{{ $event->bride_photo ? (Str::startsWith($event->bride_photo, 'http') ? $event->bride_photo : asset('storage/' . $event->bride_photo)) : 'https://i.ibb.co/Jqj3Gfg/bride-61-F436-B5.jpg' }}" class="w-40 h-52 object-cover rounded-[50%] mx-auto shadow-lg transition-transform duration-300 hover:scale-105" data-aos="zoom-in" data-aos-delay="400">
                        <h3 class="font-title text-4xl mt-4 text-brand-brown-dark" data-aos="fade-right" data-aos-delay="500">{{ $event->bride_name }}</h3>
                        <p class="mt-2 font-semibold" data-aos="fade-right" data-aos-delay="600">Putri dari</p>
                        <p data-aos="fade-right" data-aos-delay="700">{{ $event->bride_parents }}</p>
                        @if(!empty($event->bride_instagram))
                            <a href="https://instagram.com/{{ $event->bride_instagram }}" target="_blank" class="inline-block mt-2 text-sm text-brand-brown-dark/80 hover:underline" data-aos="fade-right" data-aos-delay="800"><i class="fa-brands fa-instagram"></i> {{ '@' . $event->bride_instagram }}</a>
                        @endif
                    </div>
                    
                    <div class="font-title text-6xl text-brand-brown-dark/80 my-[-2rem]" data-aos="zoom-in" data-aos-delay="900">&</div>
                    
                    <div class="text-center" data-aos="fade-left" data-aos-delay="300">
                        <img src="{{ $event->groom_photo ? (Str::startsWith($event->groom_photo, 'http') ? $event->groom_photo : asset('storage/' . $event->groom_photo)) : 'https://i.ibb.co/zZfT4b2/groom-61-F436-B5.jpg' }}" class="w-40 h-52 object-cover rounded-[50%] mx-auto shadow-lg transition-transform duration-300 hover:scale-105" data-aos="zoom-in" data-aos-delay="400">
                        <h3 class="font-title text-4xl mt-4 text-brand-brown-dark" data-aos="fade-left" data-aos-delay="500">{{ $event->groom_name }}</h3>
                        <p class="mt-2 font-semibold" data-aos="fade-left" data-aos-delay="600">Putra dari</p>
                        <p data-aos="fade-left" data-aos-delay="700">{{ $event->groom_parents }}</p>
                        @if(!empty($event->groom_instagram))
                            <a href="https://instagram.com/{{ $event->groom_instagram }}" target="_blank" class="inline-block mt-2 text-sm text-brand-brown-dark/80 hover:underline" data-aos="fade-left" data-aos-delay="800"><i class="fa-brands fa-instagram"></i> {{ '@' . $event->groom_instagram }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 px-6 text-center bg-brand-brown-dark text-white" data-aos="fade-up">
            <p class="italic leading-relaxed max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="100">"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."</p>
            <p class="font-semibold mt-4" data-aos="fade-up" data-aos-delay="200">(Q.S Ar-Rum : 21)</p>
        </section>
        
        <section id="acara" class="py-20 px-6 text-center relative overflow-hidden">
            <div class="ornament ornament-tl"></div>
            <div class="ornament ornament-br"></div>
            
            <div data-aos="zoom-in">
                <h2 class="font-title text-6xl text-brand-brown-dark" data-aos="fade-up">Save The Date</h2>
                <p class="mt-2 text-brand-brown-dark/80" data-aos="fade-up" data-aos-delay="100">Kami mengundang Anda untuk hadir di hari bahagia kami.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8 mt-12 max-w-4xl mx-auto">
                <div class="flex-1 bg-white/30 backdrop-blur-sm p-8 rounded-xl shadow-lg" data-aos="fade-right" data-aos-delay="200">
                    <h3 class="font-title text-5xl" data-aos="fade-right" data-aos-delay="300">Akad Nikah</h3>
                    <p class="mt-4 font-semibold text-xl" data-aos="fade-right" data-aos-delay="400">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p class="mt-4" data-aos="fade-right" data-aos-delay="500"><i class="fa-regular fa-clock mr-2"></i> {{ $event->akad_time }}</p>
                    <div class="mt-4">
                        <p class="font-semibold" data-aos="fade-right" data-aos-delay="600">Lokasi Acara</p>
                        <p class="mt-1" data-aos="fade-right" data-aos-delay="700">{{ $event->akad_location }}</p>
                    </div>
                    @if(!empty($event->akad_maps_url))
                        <a href="{{ $event->akad_maps_url }}" target="_blank" class="mt-6 inline-flex items-center gap-2 bg-brand-brown-dark text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105" data-aos="zoom-in" data-aos-delay="800"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                    @endif
                </div>
                
                <div class="flex-1 bg-white/30 backdrop-blur-sm p-8 rounded-xl shadow-lg" data-aos="fade-left" data-aos-delay="200">
                    <h3 class="font-title text-5xl" data-aos="fade-left" data-aos-delay="300">Resepsi</h3>
                    <p class="mt-4 font-semibold text-xl" data-aos="fade-left" data-aos-delay="400">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p class="mt-4" data-aos="fade-left" data-aos-delay="500"><i class="fa-regular fa-clock mr-2"></i> {{ $event->resepsi_time }}</p>
                    <div class="mt-4">
                        <p class="font-semibold" data-aos="fade-left" data-aos-delay="600">Lokasi Acara</p>
                        <p class="mt-1" data-aos="fade-left" data-aos-delay="700">{{ $event->resepsi_location }}</p>
                    </div>
                     @if(!empty($event->resepsi_maps_url))
                        <a href="{{ $event->resepsi_maps_url }}" target="_blank" class="mt-6 inline-flex items-center gap-2 bg-brand-brown-dark text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105" data-aos="zoom-in" data-aos-delay="800"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                    @endif
                </div>
            </div>
        </section>

        @if(!empty($photos) && $photos->isNotEmpty())
        <section id="galeri" class="py-20 px-6 text-center">
            <h2 class="font-title text-6xl" data-aos="fade-up">Our Moments</h2>
            <div class="mt-12 max-w-xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($photos as $photo)
                    <a href="{{ asset('storage/' . $photo->path) }}" data-fslightbox="gallery" class="overflow-hidden rounded-lg shadow-lg block group" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover aspect-square group-hover:scale-110 transition-transform duration-500">
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        
        @if(isset($guest->uuid) && $guest->uuid !== 'umum')
        <section id="qr-code" class="py-20 px-6 text-center">
            <div class="max-w-md mx-auto" data-aos="zoom-in">
                <h2 class="font-title text-5xl" data-aos="fade-up">QR Code Kehadiran</h2>
                <p class="mt-4" data-aos="fade-up" data-aos-delay="100">Silakan tunjukkan QR Code ini kepada penerima tamu untuk konfirmasi kehadiran.</p>
                <div class="mt-6 bg-white p-4 inline-block rounded-lg shadow-lg" data-aos="zoom-in" data-aos-delay="200">
                    {!! QrCode::size(200)->generate(route('undangan.show', ['event' => $event->uuid, 'guest' => $guest->uuid])) !!}
                </div>
                <p class="mt-2 font-bold text-lg" data-aos="fade-up" data-aos-delay="300">{{ $guest->name }}</p>
            </div>
        </section>
        @endif
        
        @if(!empty($event->gifts) && $event->gifts->isNotEmpty())
        <section class="py-20 px-6 text-center bg-brand-brown-dark text-white relative" data-aos="fade-up">
            <h2 class="font-title text-5xl" data-aos="fade-up">Wedding Gift</h2>
            <p class="mt-4 max-w-md mx-auto" data-aos="fade-up" data-aos-delay="100">Doa Restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi melalui tautan di bawah ini.</p>
            <button id="gift-button" class="mt-6 inline-flex items-center gap-2 bg-white text-brand-brown-dark font-semibold py-2 px-6 rounded-full shadow-lg hover:scale-105 transition-transform" data-aos="zoom-in" data-aos-delay="200">
                <i class="fa-solid fa-gift"></i> Kirim Hadiah
            </button>
        </section>
        @endif

        <section id="ucapan" class="py-20 px-6">
            <div class="text-center" data-aos="zoom-in">
                <h2 class="font-title text-6xl" data-aos="fade-up">Ucapan & RSVP</h2>
                <p class="mt-2" data-aos="fade-up" data-aos-delay="100">Berikan doa dan ucapan terbaik untuk kami.</p>
            </div>
            <div class="mt-8 max-w-md mx-auto p-6 bg-white/20 backdrop-blur-sm rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert" data-aos="fade-up">
                        <p class="font-bold">Terima kasih!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('rsvp.store', $event->uuid) }}" method="POST">
                    @csrf
                    <div class="mb-4" data-aos="fade-right" data-aos-delay="300">
                        <input type="text" id="name" name="name" class="block w-full border-brand-brown/50 rounded-md shadow-sm bg-white/50 p-3 focus:ring-2 focus:ring-brand-brown transition @if(isset($guest->uuid) && $guest->uuid !== 'umum') bg-gray-100 text-gray-500 cursor-not-allowed @endif" value="{{ $guest->name ?? old('name') }}" required @if(isset($guest->uuid) && $guest->uuid !== 'umum') readonly @endif placeholder="Nama Anda">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4" data-aos="fade-left" data-aos-delay="400">
                        <textarea id="message" name="message" rows="4" class="block w-full border-brand-brown/50 rounded-md shadow-sm bg-white/50 p-3 focus:ring-2 focus:ring-brand-brown transition" placeholder="Tulis ucapan..." required>{{ old('message') }}</textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4" data-aos="fade-up" data-aos-delay="500">
                        <label class="block text-sm font-medium mb-2">Konfirmasi Kehadiran</label>
                        <div class="flex gap-4">
                           <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-brown rounded-md hover:bg-brand-brown/50 transition-colors duration-300 flex items-center justify-center gap-2" data-value="Hadir"><i class="fa-solid fa-check"></i> Hadir</button>
                           <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-brown rounded-md hover:bg-brand-brown/50 transition-colors duration-300 flex items-center justify-center gap-2" data-value="Tidak Hadir"><i class="fa-solid fa-xmark"></i> Tidak Hadir</button>
                        </div>
                        <input type="hidden" name="status" id="status-input" value="{{ old('status') }}" required>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-center mt-6" data-aos="zoom-in" data-aos-delay="600">
                        <button type="submit" class="bg-brand-brown-dark text-white font-semibold py-2 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Kirim</button>
                    </div>
                </form>
            </div>
            
            <div class="mt-12 space-y-4 max-w-md mx-auto">
                @if(!empty($rsvps) && $rsvps->isNotEmpty())
                    @foreach($rsvps as $rsvp)
                        <div class="bg-white/20 p-4 rounded-lg shadow-sm" data-aos="{{ $loop->iteration % 2 == 0 ? 'fade-left' : 'fade-right' }}">
                            <div class="flex justify-between items-center">
                                <p class="font-semibold font-title text-lg">{{ $rsvp->name }}</p>
                                @if($rsvp->status == 'Hadir')
                                    <span class="text-xs bg-green-200 text-green-800 font-medium px-2.5 py-0.5 rounded-full">Hadir</span>
                                @endif
                            </div>
                            <p class="text-sm mt-1 italic">"{{ $rsvp->message }}"</p>
                            <p class="text-xs text-brand-brown-dark/50 text-right mt-2">{{ $rsvp->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                    <div class="pt-4" data-aos="fade-up">
                        {{ $rsvps->links() }}
                    </div>
                @else
                     <p class="text-center text-brand-brown-dark/60" data-aos="fade-up">Jadilah yang pertama mengirim ucapan.</p>
                @endif
            </div>
        </section>
        
        <footer class="py-20 px-6 text-center">
            <div class="mt-4" data-aos="fade-up">
                <img src="{{ $event->photo_url ? (Str::startsWith($event->photo_url, 'http') ? $event->photo_url : asset('storage/' . $event->photo_url)) : 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg' }}" class="w-40 h-40 rounded-full object-cover mx-auto shadow-lg mb-6" data-aos="zoom-in" data-aos-delay="100">
                <h2 class="font-title text-5xl" data-aos="fade-up" data-aos-delay="200">Terima Kasih</h2>
                <p class="mt-4 max-w-md mx-auto" data-aos="fade-up" data-aos-delay="300">Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i, berkenan hadir dan memberikan do'a restu kepada kami.</p>
                <p class="mt-6" data-aos="fade-up" data-aos-delay="400">Wassalamu’alaikum warahmatullahi wabarakatuh</p>
                <p class="mt-8" data-aos="fade-up" data-aos-delay="500">Kami Yang Berbahagia</p>
                <p class="font-title text-4xl mt-2" data-aos="fade-up" data-aos-delay="600">{{ $event->bride_name ?? 'Putri' }} & {{ $event->groom_name ?? 'Putra' }}</p>
            </div>
        </footer>
    </div>
</main>

    @if(!empty($event->gifts) && $event->gifts->isNotEmpty())
    <div id="gift-modal" class="fixed inset-0 bg-black/60 z-[1000] flex justify-center items-center p-4 hidden">
        <div id="gift-modal-content" class="bg-brand-bg-light rounded-lg p-6 w-full max-w-sm text-center relative transform scale-95 transition-transform duration-300">
            <button id="close-modal-btn" class="absolute top-2 right-3 text-2xl text-brand-brown-dark/50 hover:text-brand-brown-dark">&times;</button>
            <h3 class="font-title text-4xl mb-4">Kirim Hadiah</h3>
            <div class="space-y-4 text-left">
                @foreach($event->gifts as $gift)
                <div class="bg-white/30 p-4 rounded-lg shadow-sm" data-account="{{ $gift->account_number }}">
                    <p class="font-bold">{{ $gift->bank_name }}</p>
                    <p>{{ $gift->account_number }}</p>
                    <p>a.n {{ $gift->account_name }}</p>
                    <button class="copy-btn text-sm mt-2 bg-brand-brown-dark/80 text-white px-3 py-1 rounded-full hover:bg-brand-brown-dark transition-colors">Salin Rekening</button>
                </div>
                @endforeach
            </div>
             <p id="copy-feedback" class="text-sm mt-4 text-green-700 opacity-0 transition-opacity">Nomor rekening disalin!</p>
        </div>
    </div>
    @endif

    <nav class="bottom-nav fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-md shadow-[0_-2px_10px_rgba(0,0,0,0.1)] z-[500] hidden">
        <div class="max-w-3xl mx-auto flex justify-around text-brand-brown-dark/80 text-center py-3">
            <a href="#mempelai" class="flex-1 transition-transform duration-300">
                <i class="fa-solid fa-user-group text-xl"></i>
                <span class="block text-xs mt-1">Mempelai</span>
            </a>
            <a href="#acara" class="flex-1 transition-transform duration-300">
                <i class="fa-solid fa-calendar-check text-xl"></i>
                <span class="block text-xs mt-1">Acara</span>
            </a>
            <a href="#galeri" class="flex-1 transition-transform duration-300">
                <i class="fa-solid fa-images text-xl"></i>
                <span class="block text-xs mt-1">Galeri</span>
            </a>
            <a href="#ucapan" class="flex-1 transition-transform duration-300">
                <i class="fa-solid fa-comments text-xl"></i>
                <span class="block text-xs mt-1">Ucapan</span>
            </a>
        </div>
    </nav>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/fslightbox.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 1000, once: true, offset: 50 }); // UPDATE: durasi animasi diperpanjang

        const cover = document.getElementById('cover');
        const mainContent = document.getElementById('main-content');
        const openButton = document.getElementById('open-invitation');
        const audio = document.getElementById('background-music');
        const musicControl = document.getElementById('music-control');
        const musicIcon = document.getElementById('music-icon');
        const bottomNav = document.querySelector('.bottom-nav');

        document.body.style.opacity = 1;

        openButton.addEventListener('click', function() {
            audio.play().catch(error => console.log("Autoplay dicegah oleh browser."));
            musicControl.classList.remove('hidden');
            if (bottomNav) bottomNav.classList.remove('hidden');

            // UPDATE: Transisi cover lebih halus
            cover.style.transition = 'opacity 1s ease-out, transform 1s cubic-bezier(0.77, 0, 0.175, 1)';
            cover.style.opacity = 0;
            cover.style.transform = 'translateY(-100%)';
            
            setTimeout(() => {
                cover.style.display = 'none';
                mainContent.style.display = 'block';
                document.body.style.overflowY = 'auto'; 
                // re-initialize AOS untuk elemen di main-content
                AOS.refresh();
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
                document.getElementById('days').innerText = String(Math.floor(distance/(1000*60*60*24))).padStart(2,'0');
                document.getElementById('hours').innerText = String(Math.floor((distance%(1000*60*60*24))/(1000*60*60))).padStart(2,'0');
                document.getElementById('minutes').innerText = String(Math.floor((distance%(1000*60*60))/(1000*60))).padStart(2,'0');
                document.getElementById('seconds').innerText = String(Math.floor((distance%(1000*60))/1000)).padStart(2,'0');
            }, 1000);
        }

        const rsvpButtons = document.querySelectorAll('.rsvp-btn');
        const statusInput = document.getElementById('status-input');
        if (statusInput && statusInput.value) {
            document.querySelector(`.rsvp-btn[data-value="${statusInput.value}"]`)?.classList.add('active');
        }
        rsvpButtons.forEach(button => {
            button.addEventListener('click', () => {
                statusInput.value = button.dataset.value;
                rsvpButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });

        const giftModal = document.getElementById('gift-modal');
        if(giftModal) {
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
            giftModal.addEventListener('click', (e) => {
                if (e.target === giftModal) closeModal();
            });

            const copyFeedback = document.getElementById('copy-feedback');
            document.querySelectorAll('.copy-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const parent = e.target.closest('div[data-account]');
                    const accountNumber = parent.dataset.account;
                    navigator.clipboard.writeText(accountNumber).then(() => {
                        copyFeedback.classList.remove('opacity-0');
                        setTimeout(() => copyFeedback.classList.add('opacity-0'), 2000);
                    });
                });
            });
        }
        
        const sections = document.querySelectorAll('main section[id]');
        const navLinks = document.querySelectorAll('.bottom-nav a');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });
    });
    </script>
</body>
</html>