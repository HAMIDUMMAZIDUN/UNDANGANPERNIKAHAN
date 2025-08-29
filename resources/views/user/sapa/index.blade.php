<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Sapa - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .fade-in { animation: fadeIn 1.5s ease-in-out; }
        .fade-out { animation: fadeOut 1.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        @keyframes fadeOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(0.95); } }
    </style>
</head>
<body class="bg-slate-900 text-white overflow-hidden">

    {{-- Tampilan Slideshow --}}
    <div id="slideshow-container" class="relative w-screen h-screen flex items-center justify-center p-8">
        </div>

    {{-- Footer dengan detail event dan QR code --}}
    <footer class="absolute bottom-0 left-0 w-full p-6 flex justify-between items-center bg-gradient-to-t from-black/50 to-transparent">
        <div>
            <h1 class="font-serif-display text-3xl italic">{{ $event->name }}</h1>
            <p class="text-slate-300">{{ \Carbon\Carbon::parse($event->date)->isoFormat('D MMMM YYYY') }}</p>
        </div>
        <div id="qrcode-container">
            </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slideshowContainer = document.getElementById('slideshow-container');
            const dataUrl = `{{ route('sapa.data', ['event' => $event->uuid]) }}`;
            let slideshowItems = [];
            let currentIndex = 0;
            let intervalId;

            // Generate QR code saat halaman dimuat
            new QRCode(document.getElementById("qrcode-container"), {
                text: "{{ url('/undangan/' . $event->uuid) }}",
                width: 80,
                height: 80,
                colorDark: "#FFF",
                colorLight: "transparent"
            });

            async function fetchData() {
                try {
                    const response = await fetch(dataUrl);
                    const data = await response.json();
                    if (data && data.length > 0) {
                        slideshowItems = data;
                        if (slideshowContainer.innerHTML.trim() === '') {
                             // Jika ini pertama kali, tampilkan item pertama
                             showNextItem();
                        }
                    } else {
                         // Tampilkan pesan jika tidak ada data
                         slideshowContainer.innerHTML = `<div class="text-center fade-in"><p class="text-2xl">Belum ada ucapan atau foto untuk ditampilkan.</p></div>`;
                    }
                } catch (error) {
                    console.error('Gagal mengambil data:', error);
                    slideshowContainer.innerHTML = `<div class="text-center fade-in"><p class="text-2xl">Gagal memuat data ucapan dan foto.</p></div>`;
                }
            }

            function showNextItem() {
                if (slideshowItems.length === 0) {
                    slideshowContainer.innerHTML = `<div class="text-center fade-in"><p class="text-2xl">Belum ada ucapan atau foto untuk ditampilkan.</p></div>`;
                    return;
                }

                const item = slideshowItems[currentIndex];
                let content = '';

                if (item.type === 'rsvp') {
                    content = `
                        <div class="text-center max-w-4xl mx-auto">
                            <p class="text-3xl lg:text-5xl leading-tight text-slate-200">"${item.data.message}"</p>
                            <p class="mt-8 text-2xl lg:text-3xl font-bold text-amber-400">- ${item.data.name} -</p>
                        </div>
                    `;
                } else if (item.type === 'photo') {
                    content = `
                        <div class="w-full h-full flex items-center justify-center">
                            <img src="${item.data}" alt="Galeri Foto" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl object-contain">
                        </div>
                    `;
                }
                
                slideshowContainer.innerHTML = `<div class="w-full h-full flex items-center justify-center fade-in">${content}</div>`;
                currentIndex = (currentIndex + 1) % slideshowItems.length;
            }

            // Panggil fetchData setiap 60 detik untuk memperbarui data
            fetchData();
            setInterval(fetchData, 60000);

            // Panggil showNextItem setiap 8 detik untuk menampilkan item berikutnya
            intervalId = setInterval(showNextItem, 8000);
        });
    </script>
</body>
</html>