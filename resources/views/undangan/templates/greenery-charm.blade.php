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
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Poppins', 'sans-serif'],
              serif: ['Cormorant Garamond', 'serif'],
              script: ['Great Vibes', 'cursive'],
            },
            colors: {
              'brand-dark': '#5D483C',
              'brand-brown': '#8D7B68',
              'brand-bg': '#FBF9F6',
              'brand-light': '#F5F1E9'
            }
          }
        }
      }
    </script>

    <style>
        body {
            background-color: #FBF9F6;
            color: #5D483C;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            font-family: 'Poppins', 'sans-serif';
            padding-bottom: 80px; /* Ruang untuk bottom nav */
        }
        #main-content { display: none; }
        .bg-watercolor {
            background-image: url('https://i.ibb.co/qY742S5/watercolor-bg.png');
            background-size: cover;
            background-position: center;
        }
        .bottom-nav a.active {
            color: #8D7B68; /* brand-brown */
            transform: scale(1.1);
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    
    <audio id="background-music" loop> <source src="{{ $event->music_url ?? asset('music/musik1.mp3') }}" type="audio/mpeg"> </audio>
    <div id="music-control" class="fixed bottom-24 right-5 z-[999] cursor-pointer p-3 bg-white/50 backdrop-blur-md rounded-full shadow-lg hidden">
        <i id="music-icon" class="fa-solid fa-volume-high text-brand-dark text-xl w-6 h-6 text-center"></i>
    </div>

    <div id="cover" class="h-screen w-full flex flex-col justify-center items-center text-center p-4 bg-watercolor relative overflow-hidden">
        <div class="relative z-20" data-aos="zoom-in">
            <p class="text-sm tracking-widest uppercase">The Wedding Of</p>
            <div class="my-6 relative w-48 h-48 mx-auto p-2 bg-white/50 shadow-lg rounded-full">
                 <img src="{{ $event->photo_url ? (Str::startsWith($event->photo_url, 'http') ? $event->photo_url : asset('storage/' . $event->photo_url)) : 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg' }}" class="w-full h-full object-cover rounded-full">
            </div>
            <h1 class="font-script text-6xl text-brand-dark">{{ $event->bride_name ?? 'Putri' }} & {{ $event->groom_name ?? 'Putra' }}</h1>
            <p class="mt-4 font-serif font-semibold text-lg">{{ \Carbon\Carbon::parse($event->date ?? now())->isoFormat('dddd, D MMMM YYYY') }}</p>

            <div id="countdown" class="grid grid-cols-4 gap-2 mt-8 text-center max-w-xs mx-auto">
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="days" class="text-2xl font-bold">00</p><p class="text-xs">Hari</p></div>
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="hours" class="text-2xl font-bold">00</p><p class="text-xs">Jam</p></div>
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="minutes" class="text-2xl font-bold">00</p><p class="text-xs">Menit</p></div>
                <div class="p-2 bg-brand-brown text-white rounded-lg"><p id="seconds" class="text-2xl font-bold">00</p><p class="text-xs">Detik</p></div>
            </div>

            <div class="mt-8">
                <p class="text-sm">Kepada Yth.</p>
                <p class="font-serif text-2xl mt-1">{{ $guest->name ?? 'Tamu Undangan' }}</p>
            </div>
            
            <button id="open-invitation" class="mt-8 bg-brand-dark text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-all duration-300 hover:scale-105 flex items-center gap-2 mx-auto">
                <i class="fa-regular fa-envelope-open"></i>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <main id="main-content" class="relative">
        <div class="max-w-3xl mx-auto bg-brand-bg shadow-2xl relative">
            <div class="absolute inset-0 bg-watercolor opacity-50 z-0"></div>
            <div class="relative z-10">
                <section id="mempelai" class="py-20 px-6 text-center" data-aos="fade-up">
                    <p>Assalamu’alaikum Warahmatullahi Wabarakatuh</p>
                    <p class="mt-4 max-w-lg mx-auto font-serif">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami.</p>
                    
                    <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-8">
                        <div class="text-center">
                            <img src="{{ $event->bride_photo ? (Str::startsWith($event->bride_photo, 'http') ? $event->bride_photo : asset('storage/' . $event->bride_photo)) : 'https://i.ibb.co/Jqj3Gfg/bride-61-F436-B5.jpg' }}" class="w-48 h-48 object-cover rounded-full mx-auto shadow-lg">
                            <h3 class="font-script text-5xl mt-4 text-brand-dark">{{ $event->bride_name }}</h3>
                            <p class="mt-2 font-serif font-semibold">Putri dari</p>
                            <p class="text-sm">{{ $event->bride_parents }}</p>
                            @if(!empty($event->bride_instagram))
                                <a href="https://instagram.com/{{ $event->bride_instagram }}" target="_blank" class="inline-block mt-2 text-sm bg-brand-brown text-white px-3 py-1 rounded-full"><i class="fa-brands fa-instagram"></i> {{ $event->bride_instagram }}</a>
                            @endif
                        </div>
                        
                        <p class="font-script text-5xl my-4 md:my-0">&</p>
                        
                        <div class="text-center">
                            <img src="{{ $event->groom_photo ? (Str::startsWith($event->groom_photo, 'http') ? $event->groom_photo : asset('storage/' . $event->groom_photo)) : 'https://i.ibb.co/zZfT4b2/groom-61-F436-B5.jpg' }}" class="w-48 h-48 object-cover rounded-full mx-auto shadow-lg">
                            <h3 class="font-script text-5xl mt-4 text-brand-dark">{{ $event->groom_name }}</h3>
                            <p class="mt-2 font-serif font-semibold">Putra dari</p>
                            <p class="text-sm">{{ $event->groom_parents }}</p>
                            @if(!empty($event->groom_instagram))
                                <a href="https://instagram.com/{{ $event->groom_instagram }}" target="_blank" class="inline-block mt-2 text-sm bg-brand-brown text-white px-3 py-1 rounded-full"><i class="fa-brands fa-instagram"></i> {{ $event->groom_instagram }}</a>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="py-20 px-6 text-center bg-brand-brown text-white" data-aos="fade-up">
                    <p class="italic leading-relaxed max-w-xl mx-auto font-serif text-lg">"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."</p>
                    <p class="font-semibold mt-4">(Q.S Ar-Rum : 21)</p>
                </section>
                
                <section id="acara" class="py-20 px-6 text-center">
                    <div class="flex flex-col gap-8 max-w-md mx-auto">
                        <div class="bg-white p-6 rounded-2xl shadow-lg relative" data-aos="fade-up">
                            <h2 class="font-script text-5xl">Akad Nikah</h2>
                            <p class="mt-4 font-semibold text-xl font-serif">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                            <p class="mt-4"><i class="fa-regular fa-clock"></i> {{ $event->akad_time }}</p>
                            <div class="mt-4">
                                <p class="font-semibold">Lokasi Acara</p>
                                <p>{{ $event->akad_location }}</p>
                            </div>
                            @if(!empty($event->akad_maps_url))
                                <a href="{{ $event->akad_maps_url }}" target="_blank" class="mt-6 inline-block bg-brand-brown text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                            @endif
                        </div>
                        
                        <div class="bg-white p-6 rounded-2xl shadow-lg relative" data-aos="fade-up">
                            <h2 class="font-script text-5xl">Resepsi</h2>
                            <p class="mt-4 font-semibold text-xl font-serif">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                            <p class="mt-4"><i class="fa-regular fa-clock"></i> {{ $event->resepsi_time }}</p>
                            <div class="mt-4">
                                <p class="font-semibold">Lokasi Acara</p>
                                <p>{{ $event->resepsi_location }}</p>
                            </div>
                             @if(!empty($event->resepsi_maps_url))
                                <a href="{{ $event->resepsi_maps_url }}" target="_blank" class="mt-6 inline-block bg-brand-brown text-white font-semibold py-2 px-6 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a>
                            @endif
                        </div>
                    </div>
                </section>

                @if(!empty($photos) && $photos->isNotEmpty())
                <section id="galeri" class="py-20 px-6 text-center">
                    <h2 class="font-script text-5xl" data-aos="fade-up">Our Moments</h2>
                    <div class="mt-12 max-w-xl mx-auto">
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
                
                @if(isset($guest->uuid) && $guest->uuid !== 'umum')
                <section id="qr-code" class="py-20 px-6 text-center">
                    <div class="max-w-md mx-auto" data-aos="fade-up">
                        <h2 class="font-script text-5xl">QR Code Kehadiran</h2>
                        <p class="mt-4">Silakan tunjukkan QR Code ini kepada penerima tamu untuk konfirmasi kehadiran.</p>
                        <div class="mt-6 bg-white p-4 inline-block rounded-lg shadow-lg">
                            {!! QrCode::size(200)->generate(route('undangan.show', ['event' => $event->uuid, 'guest' => $guest->uuid])) !!}
                        </div>
                        <p class="mt-2 font-bold text-lg">{{ $guest->name }}</p>
                    </div>
                </section>
                @endif
                
                @if(!empty($event->gifts) && $event->gifts->isNotEmpty())
                <section class="py-20 px-6 text-center bg-brand-brown text-white" data-aos="fade-up">
                    <h2 class="font-script text-5xl">Wedding Gift</h2>
                    <p class="mt-4 max-w-md mx-auto font-serif">Doa Restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi melalui tautan di bawah ini.</p>
                    <button id="gift-button" class="mt-6 inline-flex items-center gap-2 bg-white text-brand-dark font-semibold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-transform">
                        <i class="fa-solid fa-gift"></i> Kirim Hadiah
                    </button>
                </section>
                @endif

                <section id="ucapan" class="py-20 px-6">
                    <div class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow-lg">
                        <div class="text-center" data-aos="fade-up">
                            <h2 class="font-script text-5xl">Ucapan & RSVP</h2>
                            <p class="mt-2 font-serif">Berikan doa dan ucapan terbaik untuk kami.</p>
                        </div>
                        <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
                            @if(session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong class="font-bold">Terima kasih!</strong>
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif

                            <form action="{{ route('rsvp.store', $event->uuid) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <input type="text" id="name" name="name" class="block w-full border-brand-brown/50 rounded-md shadow-sm bg-brand-bg p-3 @if(isset($guest->uuid) && $guest->uuid !== 'umum') bg-gray-100 text-gray-500 @endif" value="{{ $guest->name ?? old('name') }}" required @if(isset($guest->uuid) && $guest->uuid !== 'umum') readonly @endif placeholder="Nama Anda">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <textarea id="message" name="message" rows="4" class="block w-full border-brand-brown/50 rounded-md shadow-sm bg-brand-bg p-3 focus:ring-brand-dark focus:border-brand-dark" placeholder="Tulis ucapan..." required>{{ old('message') }}</textarea>
                                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Konfirmasi Kehadiran</label>
                                    <div class="flex gap-4">
                                       <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-brown/50 rounded-md hover:bg-brand-brown/10 transition-colors" data-value="Hadir"><i class="fa-solid fa-check"></i> Hadir</button>
                                       <button type="button" class="rsvp-btn flex-1 p-2 border border-brand-brown/50 rounded-md hover:bg-brand-brown/10 transition-colors" data-value="Tidak Hadir"><i class="fa-solid fa-xmark"></i> Tidak Hadir</button>
                                    </div>
                                    <input type="hidden" name="status" id="status-input" value="{{ old('status') }}" required>
                                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="text-center mt-6">
                                    <button type="submit" class="bg-brand-brown text-white font-semibold py-3 px-10 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                
                    <div class="mt-12 space-y-4 max-w-md mx-auto">
                        @if(!empty($rsvps))
                            @forelse($rsvps as $rsvp)
                                <div class="bg-white p-4 rounded-lg shadow-sm" data-aos="fade-up">
                                    <div class="flex justify-between items-center">
                                       <p class="font-semibold font-serif text-lg">{{ $rsvp->name }}</p>
                                        @if($rsvp->status == 'Hadir')
                                            <span class="text-xs bg-green-100 text-green-800 font-medium px-2.5 py-0.5 rounded-full">Hadir</span>
                                        @endif
                                    </div>
                                    <p class="text-sm mt-1">{{ $rsvp->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">{{ $rsvp->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Jadilah yang pertama mengirim ucapan.</p>
                            @endforelse
                            <div class="pt-4">
                                {{ $rsvps->links() }}
                            </div>
                        @else
                             <p class="text-center text-gray-500">Jadilah yang pertama mengirim ucapan.</p>
                        @endif
                    </div>
                </section>
                
                <footer class="py-20 px-6 text-center">
                    <img src="https://i.ibb.co/6y1v61p/tassel-border.png" alt="ornamen" class="w-full opacity-80">
                    <div data-aos="fade-up" class="mt-4">
                        <img src="{{ $event->photo_url ? (Str::startsWith($event->photo_url, 'http') ? $event->photo_url : asset('storage/' . $event->photo_url)) : 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg' }}" class="w-40 h-40 rounded-full object-cover mx-auto shadow-lg mb-6">
                        <h2 class="font-script text-5xl">Terima Kasih</h2>
                        <p class="mt-4 max-w-md mx-auto font-serif">Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i, berkenan hadir dan memberikan do'a restu kepada kami.</p>
                        <p class="mt-6">Wassalamu’alaikum warahmatullahi wabarakatuh</p>
                        <p class="mt-8">Kami Yang Berbahagia</p>
                        <p class="font-script text-4xl mt-2">{{ $event->bride_name ?? 'Putri' }} & {{ $event->groom_name ?? 'Putra' }}</p>
                    </div>
                     <img src="https://i.ibb.co/6y1v61p/tassel-border.png" alt="ornamen" class="w-full opacity-80 rotate-180 mt-4">
                </footer>
            </div>
        </div>
    </main>

    @if(!empty($event->gifts) && $event->gifts->isNotEmpty())
    <div id="gift-modal" class="fixed inset-0 bg-black/60 z-[1000] flex justify-center items-center p-4 hidden">
        <div id="gift-modal-content" class="bg-brand-bg rounded-lg p-6 w-full max-w-sm text-center relative transform scale-95 transition-transform duration-300">
            <button id="close-modal-btn" class="absolute top-2 right-3 text-2xl text-brand-dark/50 hover:text-brand-dark">&times;</button>
            <h3 class="font-script text-4xl mb-6">Wedding Gift</h3>
            <div class="space-y-4 text-left">
                @foreach($event->gifts as $gift)
                <div class="p-4 rounded-lg bg-cover bg-center text-left text-brand-dark shadow-lg" data-account="{{ $gift->account_number }}" style="background-image: url('https://i.ibb.co/k2Wz2qN/card-bg.png')">
                    <p class="font-bold">{{ $gift->bank_name }}</p>
                    <p class="text-sm">No Rekening</p>
                    <p class="font-semibold text-lg">{{ $gift->account_number }}</p>
                    <p class="text-sm mt-1">Atas Nama</p>
                    <p class="font-semibold">{{ $gift->account_name }}</p>
                    <button class="copy-btn w-full text-center text-sm mt-3 bg-brand-dark/80 hover:bg-brand-dark text-white px-3 py-2 rounded-lg"><i class="fa-regular fa-copy"></i> Salin</button>
                </div>
                @endforeach
            </div>
             <p id="copy-feedback" class="text-sm mt-4 text-green-700 opacity-0 transition-opacity">Berhasil disalin!</p>
        </div>
    </div>
    @endif

    <nav class="bottom-nav fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-md shadow-[0_-2px_10px_rgba(0,0,0,0.1)] z-[500] hidden">
        <div class="max-w-3xl mx-auto flex justify-around text-brand-dark/80 text-center py-3">
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
        AOS.init({ duration: 800, once: true, offset: 50 });

        const cover = document.getElementById('cover');
        const mainContent = document.getElementById('main-content');
        const openButton = document.getElementById('open-invitation');
        const audio = document.getElementById('background-music');
        const musicControl = document.getElementById('music-control');
        const musicIcon = document.getElementById('music-icon');
        const bottomNav = document.querySelector('.bottom-nav');

        document.body.style.opacity = 1;

        openButton.addEventListener('click', function() {
            audio.play().catch(error => console.log("Autoplay dicegah."));
            musicControl.classList.remove('hidden');
            if (bottomNav) bottomNav.classList.remove('hidden');

            cover.style.transition = 'opacity 1s ease-out, transform 1s ease-out';
            cover.style.opacity = 0;
            cover.style.transform = 'translateY(-100%)';
            
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
                document.getElementById('days').innerText = String(Math.floor(distance/(1000*60*60*24))).padStart(2,'0');
                document.getElementById('hours').innerText = String(Math.floor((distance%(1000*60*60*24))/(1000*60*60))).padStart(2,'0');
                document.getElementById('minutes').innerText = String(Math.floor((distance%(1000*60*60))/(1000*60))).padStart(2,'0');
                document.getElementById('seconds').innerText = String(Math.floor((distance%(1000*60))/1000)).padStart(2,'0');
            }, 1000);
        }

        const rsvpButtons = document.querySelectorAll('.rsvp-btn');
        const statusInput = document.getElementById('status-input');
        if (statusInput && statusInput.value) {
            document.querySelector(`.rsvp-btn[data-value="${statusInput.value}"]`)?.classList.add('bg-brand-brown', 'text-white');
        }
        rsvpButtons.forEach(button => {
            button.addEventListener('click', () => {
                statusInput.value = button.dataset.value;
                rsvpButtons.forEach(btn => btn.classList.remove('bg-brand-brown', 'text-white'));
                button.classList.add('bg-brand-brown', 'text-white');
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