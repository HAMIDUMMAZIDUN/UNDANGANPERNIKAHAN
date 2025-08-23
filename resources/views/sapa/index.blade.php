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

    <div id="slideshow-container" class="relative w-screen h-screen flex items-center justify-center p-8">
        <!-- Konten slideshow akan dimuat di sini -->
    </div>

    <footer class="absolute bottom-0 left-0 w-full p-6 flex justify-between items-center bg-gradient-to-t from-black/50 to-transparent">
        <div>
            <h1 class="font-serif-display text-3xl italic">{{ $event->name }}</h1>
            <p class="text-slate-300">{{ \Carbon\Carbon::parse($event->date)->isoFormat('D MMMM YYYY') }}</p>
        </div>
        <div class="text-right">
             <!-- Ganti URL dengan link undangan publik Anda jika ada -->
            <div id="qrcode" class="bg-white p-2 rounded-md"></div>
            <p class="text-xs mt-2 text-slate-300">Scan untuk Kirim Ucapan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slideshowContainer = document.getElementById('slideshow-container');
            const dataUrl = `{{ route('sapa.data', $event) }}`;
            let slideshowItems = [];
            let currentIndex = 0;

            // Buat QR Code
            new QRCode(document.getElementById("qrcode"), {
                text: "{{-- TODO: Ganti dengan URL undangan publik Anda --}}",
                width: 80,
                height: 80,
            });

            async function fetchData() {
                try {
                    const response = await fetch(dataUrl);
                    slideshowItems = await response.json();
                    if (slideshowItems.length > 0 && slideshowContainer.innerHTML.trim() === '') {
                        // Mulai slideshow jika data berhasil diambil pertama kali
                        showNextItem();
                    }
                } catch (error) {
                    console.error('Gagal mengambil data:', error);
                    slideshowContainer.innerHTML = `<div class="text-center"><p class="text-2xl">Gagal memuat data ucapan dan foto.</p></div>`;
                }
            }

            function showNextItem() {
                if (slideshowItems.length === 0) {
                    slideshowContainer.innerHTML = `<div class="text-center"><p class="text-2xl">Belum ada ucapan atau foto untuk ditampilkan.</p></div>`;
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
                            <img src="${item.data}" alt="Galeri Foto" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl">
                        </div>
                    `;
                }
                
                slideshowContainer.innerHTML = `<div class="w-full h-full flex items-center justify-center fade-in">${content}</div>`;

                // Pindah ke item selanjutnya
                currentIndex = (currentIndex + 1) % slideshowItems.length;
            }

            // Mulai proses
            fetchData(); // Ambil data pertama kali
            setInterval(fetchData, 60000); // Ambil data baru setiap 1 menit
            setInterval(showNextItem, 8000); // Ganti slide setiap 8 detik
        });
    </script>
</body>
</html>
