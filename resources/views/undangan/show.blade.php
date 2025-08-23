<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of {{ $event->groom_name }} & {{ $event->bride_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-color: #F8F7F4; /* A soft off-white background */
        }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .cover-section {
            background-size: cover;
            background-position: center;
        }
        #main-content {
            display: none;
            scroll-behavior: smooth;
        }
        .section-bg {
            background-image: url('https://www.toptal.com/designers/subtlepatterns/uploads/fancy-deboss.png');
        }
    </style>
</head>
<body class="text-slate-700">

    <div id="cover" class="cover-section h-screen w-full flex flex-col justify-center items-center text-center p-4" 
        style="background-image: url('{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/1080x1920/e2e8f0/64748b?text=Wedding+Cover' }}');">
        
        <div class="absolute inset-0 bg-white/30 backdrop-blur-sm"></div>
        <div class="relative z-10 bg-white/60 backdrop-blur-md p-6 sm:p-8 rounded-2xl shadow-xl max-w-md">
            <p class="text-lg">The Wedding of</p>
            <h1 class="font-playfair text-4xl sm:text-5xl font-bold my-4">{{ $event->groom_name }} & {{ $event->bride_name }}</h1>
            <p class="text-lg">{{ \Carbon\Carbon::parse($event->date)->isoFormat('DD.MM.YYYY') }}</p>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <p class="font-semibold text-xl text-slate-800 mt-1">{{ $guest->name }}</p>
            </div>

            <button id="open-invitation" class="mt-8 bg-slate-800 text-white py-3 px-8 rounded-full shadow-lg hover:bg-slate-700 transition-transform hover:scale-105 flex items-center gap-2 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 2l7.997 3.884A2 2 0 0019 7.616l-7.5 3.64-7.5-3.64A2 2 0 002.003 5.884z" /><path d="M19 9.616l-7.5 3.639L4 9.616v4.764A2 2 0 006 16h8a2 2 0 002-1.62V9.616z" /></svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <div id="main-content">
        
        <section class="h-screen bg-slate-800 text-white flex flex-col justify-center items-center text-center p-8 section-bg">
            <div class="relative z-10">
                <p class="text-lg">The Wedding of</p>
                <img src="{{ $event->photo_url ? asset('storage/' . $event->photo_url) : 'https://placehold.co/300x300/e2e8f0/64748b?text=Photo' }}" class="w-48 h-48 rounded-full object-cover mx-auto my-6 border-4 border-white shadow-lg">
                <h1 class="font-playfair text-6xl md:text-8xl font-bold my-4">{{ $event->groom_name }} & {{ $event->bride_name }}</h1>
                <p class="text-xl">{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </section>

        <section class="py-20 px-4 text-center section-bg">
            <div class="max-w-4xl mx-auto bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-2xl shadow-lg">
                <h3 class="font-playfair text-4xl font-bold mt-4">{{ $event->groom_name }}</h3>
                <p class="mt-2 text-slate-600">Putra dari</p>
                <p class="font-semibold">{{ $event->groom_parents }}</p>
                @if($event->groom_instagram)
                    <a href="https://instagram.com/{{ $event->groom_instagram }}" class="inline-block mt-2 text-sm text-amber-600">{{ '@' . $event->groom_instagram }}</a>
                @endif
                
                <p class="font-playfair text-5xl my-8">&</p>

                <h3 class="font-playfair text-4xl font-bold mt-4">{{ $event->bride_name }}</h3>
                <p class="mt-2 text-slate-600">Putri dari</p>
                <p class="font-semibold">{{ $event->bride_parents }}</p>
                @if($event->bride_instagram)
                    <a href="https://instagram.com/{{ $event->bride_instagram }}" class="inline-block mt-2 text-sm text-amber-600">{{ '@' . $event->bride_instagram }}</a>
                @endif
            </div>
        </section>

        <section class="py-20 px-4 text-center bg-slate-800 text-white" style="background-image: url('{{ $event->photo_url ? asset('storage/' . $event->photo_url) : '' }}'); background-size: cover; background-position: center; background-attachment: fixed;">
            <div class="absolute inset-0 bg-slate-800/80"></div>
            <div class="relative z-10 max-w-4xl mx-auto">
                <h2 class="font-playfair text-4xl font-bold">Save The Date</h2>
                <div id="countdown" class="grid grid-cols-4 gap-4 mt-8 text-center max-w-lg mx-auto">
                    <div><p id="days" class="text-4xl font-bold">00</p><p class="text-sm">Days</p></div>
                    <div><p id="hours" class="text-4xl font-bold">00</p><p class="text-sm">Hours</p></div>
                    <div><p id="minutes" class="text-4xl font-bold">00</p><p class="text-sm">Minutes</p></div>
                    <div><p id="seconds" class="text-4xl font-bold">00</p><p class="text-sm">Seconds</p></div>
                </div>
                <a href="#" class="mt-8 inline-block bg-white text-slate-800 font-semibold py-3 px-6 rounded-full shadow-lg hover:bg-slate-200 transition">Add to Calendar</a>
            </div>
        </section>

        <section class="py-20 px-4 text-center section-bg">
            <div class="max-w-md mx-auto bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-2xl shadow-lg">
                <h3 class="font-playfair text-3xl font-semibold text-slate-700">Akad Nikah</h3>
                <p class="font-playfair text-5xl my-4">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</p>
                <p class="text-xl">{{ \Carbon\Carbon::parse($event->date)->isoFormat('MMMM YYYY') }}</p>
                <p class="mt-2 text-slate-500">Pukul 09:00 WIB</p>
                <hr class="my-6 border-slate-300">
                <h3 class="font-playfair text-3xl font-semibold text-slate-700">Resepsi</h3>
                <p class="mt-4 text-xl">{{ \Carbon\Carbon::parse($event->date)->isoFormat('MMMM YYYY') }}</p>
                <p class="mt-2 text-slate-500">Pukul 11:00 - 14:00 WIB</p>
                <p class="mt-6 font-semibold text-slate-600">{{ $event->location }}</p>
                <a href="#" class="mt-4 inline-block bg-slate-800 text-white font-semibold py-3 px-6 rounded-full shadow-lg hover:bg-slate-700 transition">Lihat Lokasi</a>
            </div>
        </section>

        @if($photos->isNotEmpty())
        <section class="py-20 px-4 text-center bg-white">
            <h2 class="font-playfair text-4xl font-bold text-slate-800">Our Gallery</h2>
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
                @foreach($photos as $photo)
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover aspect-square hover:scale-110 transition-transform duration-500">
                </div>
                @endforeach
            </div>
        </section>
        @endif
        
        <section class="py-20 px-4 text-center section-bg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-lg">
                    <h3 class="font-playfair text-3xl font-semibold text-slate-700">Wedding Gift</h3>
                    <p class="mt-4 text-slate-600">Doa restu Anda adalah karunia terindah. Jika ingin memberikan tanda kasih, Anda dapat melakukannya secara cashless.</p>
                    <button class="mt-6 bg-slate-800 text-white font-semibold py-3 px-6 rounded-full shadow-lg hover:bg-slate-700 transition">Amplop Online</button>
                </div>
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-lg">
                    <h3 class="font-playfair text-3xl font-semibold text-slate-700">Konfirmasi Kehadiran</h3>
                    <p class="mt-4 text-slate-600">Mohon konfirmasi kehadiran Anda untuk membantu kami mempersiapkan acara.</p>
                    <button class="mt-6 bg-slate-800 text-white font-semibold py-3 px-6 rounded-full shadow-lg hover:bg-slate-700 transition">Konfirmasi Kehadiran</button>
                </div>
            </div>
        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cover = document.getElementById('cover');
            const mainContent = document.getElementById('main-content');
            const openButton = document.getElementById('open-invitation');

            document.body.style.opacity = 1;

            openButton.addEventListener('click', function() {
                cover.style.transition = 'opacity 1s ease-out, transform 1s ease-out';
                cover.style.opacity = 0;
                cover.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    cover.style.display = 'none';
                    mainContent.style.display = 'block';
                    window.scrollTo(0, 0);
                }, 1000);
            });

            // Countdown Timer Logic
            const countdownDate = new Date("{{ $event->date }}").getTime();
            const countdownElement = document.getElementById('countdown');
            if (countdownElement) {
                const interval = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = countdownDate - now;
                    document.getElementById('days').innerText = Math.floor(distance / (1000 * 60 * 60 * 24));
                    document.getElementById('hours').innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    document.getElementById('minutes').innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    document.getElementById('seconds').innerText = Math.floor((distance % (1000 * 60)) / 1000);
                    if (distance < 0) {
                        clearInterval(interval);
                        countdownElement.innerHTML = "<p class='col-span-4 text-2xl'>The event has started!</p>";
                    }
                }, 1000);
            }
        });
    </script>

</body>
</html>