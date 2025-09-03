@use(SimpleSoftwareIO\QrCode\Facades\QrCode)
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->groom_name }} & {{ $event->bride_name }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Poppins', 'sans-serif'],
            },
            colors: {
              'brand-dark': '#1a1a1a',
              'brand-light': '#ffffff',
              'brand-accent': '#333333',
              'brand-gray': '#888888',
            }
          }
        }
      }
    </script>
    <style>
        body { opacity: 0; transition: opacity 1.5s ease-in-out; background-color: #ffffff; letter-spacing: 0.05em;}
        #main-content { display: none; }
    </style>
</head>
<body class="text-brand-dark antialiased overflow-x-hidden font-sans">
    <audio id="background-music" loop> <source src="{{ $event->music_url }}" type="audio/mpeg"> </audio>
    <div id="music-control" class="fixed bottom-5 right-5 z-[999] cursor-pointer p-3 bg-white/80 backdrop-blur-md rounded-full shadow-lg hidden">
      <svg id="icon-pause" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-dark" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 00-1 1v2a1 1 0 102 0V9a1 1 0 00-1-1zm6 0a1 1 0 00-1 1v2a1 1 0 102 0V9a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
      <svg id="icon-play" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-dark hidden" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8.068v3.864a1 1 0 001.555.832l3.197-1.932a1 1 0 000-1.664l-3.197-1.932z" clip-rule="evenodd" /></svg>
    </div>

    <div id="cover" class="h-screen w-full flex text-center bg-brand-light">
        <div class="w-1/2 bg-cover bg-center" style="background-image: url('{{ $event->photo_url }}');" data-aos="fade-right" data-aos-duration="1500"></div>
        <div class="w-1/2 flex flex-col justify-center items-center p-8" data-aos="fade-left" data-aos-duration="1500">
            <div class="text-left w-full max-w-md">
                <p class="text-brand-gray">WE ARE GETTING MARRIED</p>
                <h1 class="text-5xl md:text-7xl font-semibold my-4 leading-tight">{{ $event->groom_name }}<br>& {{ $event->bride_name }}</h1>
                <p class="text-lg font-light border-t border-brand-dark pt-4 mt-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('DD . MM . YYYY') }}</p>
                <div class="mt-8">
                    <p class="text-sm text-brand-gray">Dear,</p>
                    <p class="text-xl mt-1 font-semibold">{{ $guest->name ?? 'Invited Guest' }}</p>
                </div>
                <button id="open-invitation" class="mt-10 bg-brand-dark text-white font-semibold py-3 px-10 transition hover:bg-brand-accent">
                    Open Invitation
                </button>
            </div>
        </div>
    </div>

    <main id="main-content" class="bg-brand-light">
        <section class="py-24 px-4">
            <div class="container mx-auto max-w-5xl grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left" data-aos="fade-up">
                    <img src="{{ $event->groom_photo }}" class="w-full object-cover aspect-[4/5]">
                    <h3 class="text-4xl mt-6 font-semibold">{{ $event->groom_name }}</h3>
                    <p class="text-brand-gray mt-2">Son of {{ $event->groom_parents }}</p>
                </div>
                <div class="text-center md:text-left" data-aos="fade-up" data-aos-delay="200">
                    <img src="{{ $event->bride_photo }}" class="w-full object-cover aspect-[4/5]">
                    <h3 class="text-4xl mt-6 font-semibold">{{ $event->bride_name }}</h3>
                    <p class="text-brand-gray mt-2">Daughter of {{ $event->bride_parents }}</p>
                </div>
            </div>
        </section>

        <section id="acara" class="py-24 px-4 bg-brand-light border-t border-gray-200">
            <div class="container mx-auto max-w-5xl text-center">
                <h2 class="text-4xl font-semibold" data-aos="fade-up">The Wedding Event</h2>
                <div class="mt-16 grid md:grid-cols-2 gap-8 text-left">
                    <div class="border-l-4 border-brand-accent pl-8 py-4" data-aos="fade-up">
                        <h3 class="text-3xl font-semibold">The Ceremony</h3>
                        <p class="text-lg mt-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p class="text-brand-gray">{{ $event->akad_time ?? '09:00' }}</p>
                        <p class="text-brand-gray mt-2">{{ $event->akad_location }}</p>
                    </div>
                    <div class="border-l-4 border-brand-accent pl-8 py-4" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-3xl font-semibold">The Reception</h3>
                        <p class="text-lg mt-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p class="text-brand-gray">{{ $event->resepsi_time ?? '11:00 - 14:00' }}</p>
                        <p class="text-brand-gray mt-2">{{ $event->resepsi_location }}</p>
                    </div>
                </div>
            </div>
        </section>

        @if($photos->isNotEmpty())
        <section class="py-24 px-4">
            <div class="container mx-auto max-w-5xl text-center">
                <h2 class="text-4xl font-semibold" data-aos="fade-up">Our Moments</h2>
                <div class="mt-12 columns-2 md:columns-4 gap-4" data-aos="fade-up">
                    @foreach($photos as $photo)
                        <a href="{{ $photo->path }}" data-fslightbox="gallery" class="block mb-4 break-inside-avoid"><img src="{{ $photo->path }}" class="w-full"></a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        
        <section class="py-24 px-4 bg-brand-light border-t border-gray-200">
            <div class="container mx-auto max-w-5xl grid md:grid-cols-2 gap-16 items-start">
                <div data-aos="fade-up">
                    <h2 class="text-4xl font-semibold">Send Wishes</h2>
                    <form class="mt-8 space-y-4" onsubmit="alert('Ini adalah mode demo. Form tidak dapat dikirim.'); return false;">
                        <div>
                            <input type="text" class="w-full p-4 border border-gray-300 bg-gray-100" value="{{ $guest->name ?? 'Tamu' }}" readonly>
                        </div>
                        <div>
                            <textarea rows="5" class="w-full p-4 border border-gray-300" placeholder="Your message..." required></textarea>
                        </div>
                        <button type="submit" class="bg-brand-dark text-white font-semibold py-3 px-10 transition hover:bg-brand-accent">Send</button>
                    </form>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-4xl font-semibold">Your QR Code</h2>
                    <p class="mt-4 text-brand-gray">Please show this QR Code at the reception desk for check-in.</p>
                    <div class="mt-6 bg-white p-4 inline-block border">
                        {!! QrCode::size(180)->generate("https://example.com/check-in/demo-event-uuid/demo-guest-uuid") !!}
                    </div>
                    <p class="mt-2 font-semibold">{{ $guest->name }}</p>
                </div>
            </div>
        </section>
        
        <footer class="py-16 text-center bg-brand-dark text-white">
            <p class="text-3xl font-semibold">{{ $event->groom_name }} & {{ $event->bride_name }}</p>
            <p class="mt-2 text-brand-gray">{{ \Carbon\Carbon::parse($event->date)->isoFormat('DD . MM . YYYY') }}</p>
        </footer>
    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @if(file_exists(public_path('js/fslightbox.js')))<script src="{{ asset('js/fslightbox.js') }}"></script>@endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({ duration: 1000, once: true });
            document.body.style.opacity = 1;
            const openButton = document.getElementById('open-invitation');
            const cover = document.getElementById('cover');
            const mainContent = document.getElementById('main-content');
            const audio = document.getElementById('background-music');
            const musicControl = document.getElementById('music-control');
            openButton.addEventListener('click', () => {
                cover.style.transition = 'opacity 1s ease-out, transform 1s ease-out';
                cover.style.opacity = 0;
                cover.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    cover.style.display = 'none';
                    mainContent.style.display = 'block';
                    audio.play().catch(e => console.log("Autoplay blocked"));
                    musicControl.classList.remove('hidden');
                }, 1000);
            });
            musicControl.addEventListener('click', () => {
                const iconPlay = document.getElementById('icon-play');
                const iconPause = document.getElementById('icon-pause');
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
        });
    </script>
</body>
</html>