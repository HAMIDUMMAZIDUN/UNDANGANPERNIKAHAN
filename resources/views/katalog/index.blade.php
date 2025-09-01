<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Undangan Digital</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                        'serif': ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        'brand-brown': {
                            light: '#fdfaf6',
                            DEFAULT: '#efe3d9',
                            dark: '#c8a68a',
                            text: '#6d5d4d',
                        },
                    }
                }
            }
        }
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        /* Menambahkan background dengan gradasi dan pattern seperti pada gambar */
        body {
            background-color: #fdfaf6; /* Warna dasar */
            background-image: 
                url('https://www.transparenttextures.com/patterns/subtle-white-feathers.png'); /* Pattern halus */
        }
        .decorative-bg {
            background-image: 
                radial-gradient(circle at top left, rgba(239, 227, 217, 0.5), transparent 40%),
                radial-gradient(circle at top right, rgba(239, 227, 217, 0.5), transparent 40%);
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    </style>
</head>
<body class="bg-brand-brown-light font-sans text-brand-text decorative-bg">

    <div class="container mx-auto px-4 py-12 md:py-20">
        <header class="text-center max-w-3xl mx-auto">
            <span class="bg-brand-brown text-brand-text text-xs sm:text-sm font-semibold px-4 py-2 rounded-full">
                Platform Website Undangan 
            </span>
            <h1 class="font-serif text-3xl md:text-5xl font-bold mt-4 text-gray-800">
                Cara Terbaik Kabarkan Momen Spesial Anda Dengan Mudah, Cepat & Hemat.
            </h1>
            <p class="mt-4 text-sm md:text-base text-gray-600">
                Saatnya beralih ke undangan digital Suka-suka kamu sebarkan momen bahagiamu tanpa terhalang jarak dan waktu.
            </p>
            <a href="#" class="inline-flex items-center justify-center gap-2 bg-[#25D366] text-white font-bold px-6 py-3 rounded-full mt-8 shadow-lg hover:bg-[#128C7E] transition-colors">
                <i class="ph ph-whatsapp-logo text-xl"></i>
                <span>Konsultasi via WA</span>
            </a>
        </header>

        <main class="mt-16 md:mt-24">
            <div class="text-center">
                <h2 class="font-serif text-xl md:text-2xl font-bold tracking-[0.2em] text-gray-500">KATALOG TEMA PILIHAN</h2>
                <p class="mt-2 text-gray-600">Tekan 'Tombol di bawah' untuk melihat katalog undangan kami.</p>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mt-6">
                    <button class="px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm hover:bg-brand-brown transition">Motion Art templates</button>
                    <button class="px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm hover:bg-brand-brown transition">Statis* templates</button>
                    <button class="px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm hover:bg-brand-brown transition">Premium Terakomodasi</button>
                </div>
            </div>

            <div class="mt-12">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                    @foreach ($katalog['motion_art'] as $item)
                        <div class="text-center group">
                            <div class="bg-white border border-gray-200 rounded-lg p-2 shadow-sm transition-shadow duration-300 hover:shadow-xl">
                                <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="w-full h-auto rounded-md object-cover">
                            </div>
                            <h3 class="font-semibold text-gray-800 mt-4">{{ $item['nama'] }}</h3>
                            <div class="flex flex-col sm:flex-row gap-2 justify-center mt-2">
                                <a href="#" class="text-xs px-4 py-1.5 border border-brand-brown-dark text-brand-text rounded-full hover:bg-brand-brown transition">Lihat Demo</a>
                                <a href="#" class="text-xs px-4 py-1.5 bg-brand-text text-white rounded-full hover:opacity-80 transition">Order</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-12">
                 <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                    @foreach ($katalog['garden'] as $item)
                        <div class="text-center group">
                            <div class="bg-white border border-gray-200 rounded-lg p-2 shadow-sm transition-shadow duration-300 hover:shadow-xl">
                                <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="w-full h-auto rounded-md object-cover">
                            </div>
                            <h3 class="font-semibold text-gray-800 mt-4">{{ $item['nama'] }}</h3>
                            <div class="flex flex-col sm:flex-row gap-2 justify-center mt-2">
                                <a href="#" class="text-xs px-4 py-1.5 border border-brand-brown-dark text-brand-text rounded-full hover:bg-brand-brown transition">Lihat Demo</a>
                                <a href="#" class="text-xs px-4 py-1.5 bg-brand-text text-white rounded-full hover:opacity-80 transition">Order</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
        </main>
    </div>

</body>
</html>