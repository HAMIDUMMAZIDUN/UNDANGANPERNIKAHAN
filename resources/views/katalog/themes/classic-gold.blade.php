@use(SimpleSoftwareIO\QrCode\Facades\QrCode)
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of {{ $event->groom_name }} & {{ $event->bride_name }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ['Montserrat', 'sans-serif'], serif: ['Playfair Display', 'serif'] },
            colors: { 'brand-gold': '#a88c5a', 'brand-cream': '#F8F7F4', 'brand-dark': '#3a3a3a' }
          }
        }
      }
    </script>

    <style>
        body {
            font-family: 'Montserrat', 'sans-serif';
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-color: #F8F7F4;
        }
        .font-playfair { font-family: 'Playfair Display', 'serif'; }
        #main-content { display: none; }
        .section-bg-pattern { background-image: url('https://www.toptal.com/designers/subtlepatterns/uploads/fancy-deboss.png'); }
        @keyframes gentle-sway {
            0%, 100% { transform: rotate(-2deg) scale(1); }
            50% { transform: rotate(2deg) scale(1.02); }
        }
        .animate-sway {
            animation: gentle-sway 12s ease-in-out infinite;
        }
    </style>
</head>
<body class="text-brand-dark antialiased overflow-x-hidden">

    <div class="flower-decoration fixed top-0 left-0 -translate-x-1/4 -translate-y-1/4 z-[1] pointer-events-none opacity-0 scale-75 transition-all duration-1000 ease-in-out">
        <img src="https://i.ibb.co/680y9bJ/flower-corner.png" alt="Hiasan Bunga" class="w-64 sm:w-80 h-auto animate-sway">
    </div>
    <div class="flower-decoration fixed bottom-0 right-0 translate-x-1/4 translate-y-1/4 z-[1] pointer-events-none opacity-0 scale-75 transition-all duration-1000 ease-in-out">
        <img src="https://i.ibb.co/680y9bJ/flower-corner.png" alt="Hiasan Bunga" class="w-64 sm:w-80 h-auto animate-sway" style="transform: scaleX(-1) scaleY(-1);">
    </div>

    <audio id="background-music" loop> <source src="{{ $event->music_url }}" type="audio/mpeg"> </audio>
    <div id="music-control" class="fixed bottom-5 right-5 z-[999] cursor-pointer p-3 bg-white/80 backdrop-blur-md rounded-full shadow-lg hidden">
        <svg id="icon-pause" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <svg id="icon-play" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-dark hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    </div>

    <div id="cover" class="h-screen w-full flex flex-col justify-center items-center text-center p-4 bg-cover bg-center" style="background-image: url('{{ $event->photo_url }}');">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 w-full max-w-sm sm:max-w-md bg-white/10 backdrop-blur-sm text-white p-8 sm:p-12 rounded-t-[100px] rounded-b-[40px] shadow-2xl border-4 border-white/50" data-aos="fade-up">
            <p class="text-sm sm:text-base tracking-[0.2em] uppercase font-light">The Wedding Of</p>
            @php
                $groomInitial = !empty($event->groom_name) ? strtoupper(substr($event->groom_name, 0, 1)) : 'G';
                $brideInitial = !empty($event->bride_name) ? strtoupper(substr($event->bride_name, 0, 1)) : 'B';
            @endphp
            <div class="font-playfair text-8xl sm:text-9xl font-bold my-4 text-white/90">
                <span>{{ $groomInitial }}</span>
                <span class="text-4xl mx-1 font-sans">&</span>
                <span>{{ $brideInitial }}</span>
            </div>
            <h1 class="font-playfair text-4xl sm:text-5xl font-bold">{{ $event->groom_name ?? 'Groom' }} & {{ $event->bride_name ?? 'Bride' }}</h1>
            <p class="text-md font-semibold mt-4 tracking-widest uppercase">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
            
            <div class="mt-10 border-t border-white/30 pt-6">
                <p class="text-sm">Kepada Yth.</p>
                <p class="font-semibold text-2xl mt-1">{{ $guest->name ?? 'Tamu Undangan' }}</p>
            </div>
            
            <button id="open-invitation" class="mt-8 bg-white text-brand-dark font-bold py-3 px-8 rounded-full shadow-lg hover:bg-slate-200 transition-all duration-300 hover:scale-105 flex items-center gap-2 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 2l7.997 3.884A2 2 0 0019 7.616l-7.5 3.64-7.5-3.64A2 2 0 002.003 5.884z" /><path d="M19 9.616l-7.5 3.639L4 9.616v4.764A2 2 0 006 16h8a2 2 0 002-1.62V9.616z" /></svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <main id="main-content" class="relative z-10 bg-brand-cream">
        <section class="py-24 px-4 text-center section-bg-pattern">
            <div class="max-w-4xl mx-auto" data-aos="fade-up">
                <p class="text-lg italic text-slate-600 leading-relaxed">
                    “Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang. Sesungguhnya pada yang demikian itu benar-benar terdapat tanda-tanda bagi kaum yang berfikir.”
                </p>
                <p class="font-semibold mt-4 text-brand-dark">(QS. Ar-Rum: 21)</p>
            </div>
        </section>

        <section class="py-24 px-4 text-center">
              <div class="max-w-5xl mx-auto">
                <h2 class="font-playfair text-5xl font-bold text-brand-dark" data-aos="fade-up">Dengan Penuh Syukur</h2>
                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div data-aos="fade-right" data-aos-delay="200">
                        <img src="{{ $event->groom_photo }}" class="w-48 h-48 rounded-full object-cover mx-auto border-4 border-brand-gold shadow-lg">
                        <h3 class="font-playfair text-4xl font-bold mt-6 text-brand-dark">{{ $event->groom_name ?? 'Groom' }}</h3>
                        <p class="mt-2 text-slate-600">Putra dari</p>
                        <p class="font-semibold text-brand-dark">{{ $event->groom_parents ?? '-' }}</p>
                        @if($event->groom_instagram)
                            <a href="https://instagram.com/{{ $event->groom_instagram }}" target="_blank" class="inline-block mt-2 text-sm text-brand-gold hover:underline">{{ '@' . $event->groom_instagram }}</a>
                        @endif
                    </div>
                    <div data-aos="fade-left" data-aos-delay="400">
                         <img src="{{ $event->bride_photo }}" class="w-48 h-48 rounded-full object-cover mx-auto border-4 border-brand-gold shadow-lg">
                        <h3 class="font-playfair text-4xl font-bold mt-6 text-brand-dark">{{ $event->bride_name ?? 'Bride' }}</h3>
                        <p class="mt-2 text-slate-600">Putri dari</p>
                        <p class="font-semibold text-brand-dark">{{ $event->bride_parents ?? '-' }}</p>
                        @if($event->bride_instagram)
                            <a href="https://instagram.com/{{ $event->bride_instagram }}" target="_blank" class="inline-block mt-2 text-sm text-brand-gold hover:underline">{{ '@' . $event->bride_instagram }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        
        <section class="py-24 px-4 text-center bg-brand-dark text-white relative bg-cover bg-center bg-fixed" style="background-image: url('{{ $event->photo_url }}');">
            <div class="absolute inset-0 bg-brand-dark/80"></div>
            <div class="relative z-10 max-w-4xl mx-auto" data-aos="fade-up">
                <h2 class="font-playfair text-5xl font-bold">Simpan Tanggalnya</h2>
                <div id="countdown" class="grid grid-cols-4 gap-4 mt-10 text-center max-w-lg mx-auto">
                    <div><p id="days" class="font-playfair text-5xl font-bold">00</p><p class="text-sm tracking-widest mt-2">HARI</p></div>
                    <div><p id="hours" class="font-playfair text-5xl font-bold">00</p><p class="text-sm tracking-widest mt-2">JAM</p></div>
                    <div><p id="minutes" class="font-playfair text-5xl font-bold">00</p><p class="text-sm tracking-widest mt-2">MENIT</p></div>
                    <div><p id="seconds" class="font-playfair text-5xl font-bold">00</p><p class="text-sm tracking-widest mt-2">DETIK</p></div>
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
                    $gcal_details = urlencode("Anda diundang ke acara pernikahan " . ($event->groom_name ?? 'Groom') . " & " . ($event->bride_name ?? 'Bride') . ".");
                    $gcal_location = urlencode($event->resepsi_location ?? $event->akad_location ?? '');
                    $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$gcal_title}&dates={$gcal_start}/{$gcal_end}&details={$gcal_details}&location={$gcal_location}";
                @endphp
                <a href="{{ $googleCalendarUrl }}" target="_blank" class="mt-10 inline-block bg-white text-brand-dark font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-slate-200 transition-transform hover:scale-105">
                    Tambahkan ke Kalender
                </a>
            </div>
        </section>

        <section id="acara" class="py-24 px-4 text-center section-bg-pattern">
              <h2 class="font-playfair text-5xl font-bold text-brand-dark" data-aos="fade-up">Acara Pernikahan</h2>
            <div class="max-w-5xl mx-auto mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="font-playfair text-4xl font-semibold text-brand-dark">Akad Nikah</h3>
                    <p class="font-semibold text-slate-600 my-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p class="mt-2 text-brand-dark">Pukul {{ $event->akad_time ?? '09:00 WIB' }}</p>
                    <p class="mt-4 font-semibold text-slate-700 leading-relaxed">{{ $event->akad_location ?? 'Kediaman Mempelai' }}</p>
                    @if($event->akad_maps_url)
                        <a href="{{ $event->akad_maps_url }}" target="_blank" class="mt-6 inline-block bg-brand-gold text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Lihat Lokasi</a>
                    @endif
                </div>
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="400">
                    <h3 class="font-playfair text-4xl font-semibold text-brand-dark">Resepsi</h3>
                    <p class="font-semibold text-slate-600 my-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p class="mt-2 text-brand-dark">Pukul {{ $event->resepsi_time ?? '11:00 - 14:00 WIB' }}</p>
                    <p class="mt-4 font-semibold text-slate-700 leading-relaxed">{{ $event->resepsi_location ?? 'Gedung Serbaguna' }}</p>
                    @if($event->resepsi_maps_url)
                          <a href="{{ $event->resepsi_maps_url }}" target="_blank" class="mt-6 inline-block bg-brand-gold text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Lihat Lokasi</a>
                    @endif
                </div>
            </div>
        </section>

        @if($photos->isNotEmpty())
        <section class="py-24 px-4 text-center bg-white">
            <h2 class="font-playfair text-5xl font-bold text-brand-dark" data-aos="fade-up">Galeri</h2>
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 max-w-6xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                @foreach($photos as $photo)
                    <a href="{{ $photo->path }}" data-fslightbox="gallery" class="overflow-hidden rounded-lg shadow-lg block">
                        <img src="{{ $photo->path }}" class="w-full h-full object-cover aspect-square hover:scale-110 transition-transform duration-500">
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <section class="py-24 px-4 text-center">
            <div class="max-w-md mx-auto" data-aos="fade-up">
                <h2 class="font-playfair text-5xl font-bold text-brand-dark">QR Code Kehadiran</h2>
                <p class="mt-4 text-slate-600">
                    Silakan tunjukkan QR Code ini kepada panitia di meja penerima tamu untuk melakukan konfirmasi kehadiran.
                </p>
                <div class="mt-8 bg-white p-6 inline-block rounded-2xl shadow-lg">
                    {!! QrCode::size(250)->generate("https://example.com/check-in/demo-event-uuid/demo-guest-uuid") !!}
                </div>
                <p class="mt-4 font-semibold text-brand-dark text-xl">{{ $guest->name }}</p>
            </div>
        </section>

        <section id="wedding-wishes" class="py-24 px-4 text-center section-bg-pattern">
            <div class="max-w-4xl mx-auto">
                <h2 class="font-playfair text-5xl font-bold text-brand-dark" data-aos="fade-up">Harapan Pernikahan</h2>
                <p class="mt-4 text-slate-600" data-aos="fade-up" data-aos-delay="100">Kirimkan doa dan ucapan restu Anda untuk kedua mempelai.</p>
                
                <div class="mt-8 bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-lg text-left" data-aos="fade-up" data-aos-delay="200">
                    <form onsubmit="alert('Ini adalah mode demo. Form tidak dapat dikirim.'); return false;">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700">Nama</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm bg-gray-100" value="{{ $guest->name ?? old('name') }}" required readonly>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-slate-700">Ucapan & Doa</label>
                            <textarea id="message" name="message" rows="4" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm" placeholder="Kirim ucapan Anda di sini..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700">Konfirmasi Kehadiran</label>
                            <div class="mt-2 space-y-2 sm:space-y-0 sm:space-x-4">
                                <label class="inline-flex items-center"><input type="radio" class="form-radio text-brand-gold" name="status" value="Hadir" required> <span class="ml-2">Hadir</span></label>
                                <label class="inline-flex items-center"><input type="radio" class="form-radio text-brand-gold" name="status" value="Tidak Hadir"> <span class="ml-2">Tidak Hadir</span></label>
                            </div>
                        </div>
                        <div class="text-right mt-6">
                            <button type="submit" class="bg-brand-gold text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-transform hover:scale-105">Kirim Ucapan</button>
                        </div>
                    </form>
                </div>

                <div class="mt-12 space-y-6 text-left">
                    @empty($rsvps)
                        <p class="text-center text-slate-500">Jadilah yang pertama mengirimkan ucapan.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <footer class="py-12 text-center bg-brand-dark text-white">
            <p>Terima kasih atas doa restu Anda.</p>
            <p class="font-playfair text-3xl my-4">{{ $event->groom_name ?? 'Groom' }} & {{ $event->bride_name ?? 'Bride' }}</p>
        </footer>
    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @if(file_exists(public_path('js/fslightbox.js')))
        <script src="{{ asset('js/fslightbox.js') }}"></script>
    @endif
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: true,
        });

        const cover = document.getElementById('cover');
        const mainContent = document.getElementById('main-content');
        const openButton = document.getElementById('open-invitation');
        const audio = document.getElementById('background-music');
        const musicControl = document.getElementById('music-control');
        const iconPlay = document.getElementById('icon-play');
        const iconPause = document.getElementById('icon-pause');
        const flowerDecorations = document.querySelectorAll('.flower-decoration');

        document.body.style.opacity = 1;

        openButton.addEventListener('click', function() {
            document.documentElement.style.scrollBehavior = 'smooth';
            audio.play().catch(error => console.log("Autoplay was prevented."));
            musicControl.classList.remove('hidden');

            cover.style.transition = 'opacity 1s ease-out, transform 1s ease-out';
            cover.style.opacity = 0;
            cover.style.transform = 'scale(1.1)';
            
            setTimeout(() => {
                cover.style.display = 'none';
                mainContent.style.display = 'block';
                flowerDecorations.forEach(el => {
                    el.classList.remove('opacity-0', 'scale-75');
                    el.classList.add('opacity-50'); 
                });    
            }, 1000);
        });

        musicControl.addEventListener('click', function() {
            if (audio.paused) {
                audio.play();
                iconPlay.classList.add('hidden');
                iconPause.classList.remove('hidden');
            } else {
                audio.pause();
                iconPause.classList.add('hidden');
                iconPlay.classList.remove('hidden');
            }
        });

        const countdownDate = new Date("{{ $event->date }}T{{ explode(':', $event->akad_time ?? '09:00')[0] }}:00:00").getTime();
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            const interval = setInterval(function() {
                const now = new Date().getTime();
                const distance = countdownDate - now;
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').innerText = String(days).padStart(2, '0');
                document.getElementById('hours').innerText = String(hours).padStart(2, '0');
                document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
                document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
                
                if (distance < 0) {
                    clearInterval(interval);
                    countdownElement.innerHTML = "<p class='col-span-4 text-2xl font-playfair'>Acara telah dimulai!</p>";
                }
            }, 1000);
        }
    });
    </script>
</body>
</html>