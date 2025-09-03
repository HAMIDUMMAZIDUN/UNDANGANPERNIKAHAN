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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Dancing+Script:wght@700&family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Montserrat', 'sans-serif'],
              serif: ['Cormorant Garamond', 'serif'],
              script: ['Dancing Script', 'cursive']
            },
            colors: {
              'brand-beige': '#F5F1E9',
              'brand-green': '#58705c',
              'brand-dark': '#4a4a4a',
              'brand-brown': '#a38a6a'
            }
          }
        }
      }
    </script>
    <style>
        body { opacity: 0; transition: opacity 1.5s ease-in-out; background-color: #F5F1E9; }
        #main-content { display: none; }
        .bg-texture { background-image: url('https://www.transparenttextures.com/patterns/lined-paper.png'); }
    </style>
</head>
<body class="text-brand-dark antialiased overflow-x-hidden font-sans bg-texture">

    <audio id="background-music" loop> <source src="{{ $event->music_url }}" type="audio/mpeg"> </audio>
    <div id="music-control" class="fixed bottom-5 right-5 z-[999] cursor-pointer p-3 bg-white/80 backdrop-blur-md rounded-full shadow-lg hidden">
      <svg id="icon-pause" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      <svg id="icon-play" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-green hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    </div>
    
    <div class="fixed top-0 left-0 -translate-x-1/4 -translate-y-1/4 z-10 pointer-events-none opacity-40">
        <img src="https://i.ibb.co/k2h0sB4/eucalyptus-1.png" alt="Daun" class="w-64 h-auto transform -rotate-45">
    </div>
    <div class="fixed bottom-0 right-0 translate-x-1/4 translate-y-1/4 z-10 pointer-events-none opacity-40">
        <img src="https://i.ibb.co/k2h0sB4/eucalyptus-1.png" alt="Daun" class="w-64 h-auto transform rotate-[135deg]">
    </div>

    <div id="cover" class="h-screen w-full flex flex-col justify-center items-center text-center p-4 bg-cover bg-center" style="background-image: url('{{ $event->photo_url }}');">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-20 text-white" data-aos="fade-up">
            <p class="font-serif tracking-widest">THE WEDDING OF</p>
            <h1 class="font-script text-7xl md:text-9xl my-4">{{ $event->groom_name }} & {{ $event->bride_name }}</h1>
            <p class="font-semibold">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
            <div class="mt-8 border-t border-white/30 pt-6 max-w-sm mx-auto">
                <p class="text-sm">Kepada Yth.</p>
                <p class="font-serif text-2xl mt-1 font-semibold">{{ $guest->name ?? 'Tamu Undangan' }}</p>
            </div>
            <button id="open-invitation" class="mt-8 bg-brand-green text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-opacity-80 transition-all duration-300">
                Buka Undangan
            </button>
        </div>
    </div>

    <main id="main-content" class="relative z-20 bg-brand-beige">
        <section class="py-20 px-4 text-center">
            <div data-aos="fade-up">
                <h2 class="font-script text-6xl text-brand-green">Hello!</h2>
                <p class="font-serif text-2xl mt-4">Kami Mengundang Anda ke Pernikahan Kami</p>
            </div>
            <div class="mt-16 max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <img src="{{ $event->groom_photo }}" class="w-48 h-48 rounded-full object-cover mx-auto shadow-lg border-4 border-white">
                    <h3 class="font-script text-5xl mt-6 text-brand-dark">{{ $event->groom_name }}</h3>
                    <p class="mt-2 font-serif text-lg">Putra dari {{ $event->groom_parents }}</p>
                </div>
                <div data-aos="fade-left">
                    <img src="{{ $event->bride_photo }}" class="w-48 h-48 rounded-full object-cover mx-auto shadow-lg border-4 border-white">
                    <h3 class="font-script text-5xl mt-6 text-brand-dark">{{ $event->bride_name }}</h3>
                    <p class="mt-2 font-serif text-lg">Putri dari {{ $event->bride_parents }}</p>
                </div>
            </div>
        </section>

        <section id="acara" class="py-20 px-4 text-center bg-texture">
            <h2 class="font-script text-6xl text-brand-green" data-aos="fade-up">Save The Date</h2>
            <div class="max-w-4xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-2 gap-8 font-serif">
                <div class="border-2 border-brand-brown p-8" data-aos="fade-up">
                    <h3 class="text-3xl font-bold">Akad Nikah</h3>
                    <p class="text-lg my-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p>Pukul {{ $event->akad_time ?? '09:00 WIB' }}</p>
                    <p class="mt-2">{{ $event->akad_location }}</p>
                </div>
                <div class="border-2 border-brand-brown p-8" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-3xl font-bold">Resepsi</h3>
                    <p class="text-lg my-4">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p>Pukul {{ $event->resepsi_time ?? '11:00 - 14:00 WIB' }}</p>
                    <p class="mt-2">{{ $event->resepsi_location }}</p>
                </div>
            </div>
        </section>

        <section class="py-20 px-4 text-center">
            @if($photos->isNotEmpty())
                <h2 class="font-script text-6xl text-brand-green" data-aos="fade-up">Momen Bahagia</h2>
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-2 max-w-5xl mx-auto" data-aos="fade-up">
                    @foreach($photos as $photo)
                        <a href="{{ $photo->path }}" data-fslightbox="gallery"><img src="{{ $photo->path }}" class="w-full h-full object-cover aspect-square"></a>
                    @endforeach
                </div>
            @endif

            <div class="mt-20" data-aos="fade-up">
                <h2 class="font-script text-6xl text-brand-green">Kehadiran Anda</h2>
                <p class="mt-4 max-w-lg mx-auto font-serif">Mohon tunjukkan QR Code ini kepada penerima tamu sebagai konfirmasi kehadiran.</p>
                <div class="mt-8 bg-white p-6 inline-block shadow-lg">
                    {!! QrCode::size(200)->generate("https://example.com/check-in/demo-event-uuid/demo-guest-uuid") !!}
                </div>
                <p class="mt-4 font-serif text-xl font-bold">{{ $guest->name }}</p>
            </div>
            
            <div class="mt-20 max-w-2xl mx-auto text-left" data-aos="fade-up">
                <h2 class="font-script text-6xl text-brand-green text-center">Kirim Ucapan</h2>
                <div class="mt-8 bg-white p-8 shadow-lg">
                    <form onsubmit="alert('Ini adalah mode demo. Form tidak dapat dikirim.'); return false;">
                        <div class="mb-4">
                            <label for="name" class="block font-serif">Nama</label>
                            <input type="text" id="name" class="mt-1 block w-full border-gray-300 bg-gray-100" value="{{ $guest->name ?? 'Tamu' }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block font-serif">Pesan Anda</label>
                            <textarea id="message" rows="4" class="mt-1 block w-full border-gray-300" required></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="bg-brand-green text-white font-bold py-3 px-8 transition">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <footer class="py-12 text-center text-brand-dark font-serif">
            <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir untuk memberikan doa restu.</p>
            <p class="font-script text-5xl my-4 text-brand-green">{{ $event->groom_name }} & {{ $event->bride_name }}</p>
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
                cover.style.transition = 'opacity 1s ease-out';
                cover.style.opacity = 0;
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