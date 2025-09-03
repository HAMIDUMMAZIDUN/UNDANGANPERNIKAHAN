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
        body {
            background-color: #fdfaf6;
            background-image: url('https://www.transparenttextures.com/patterns/subtle-white-feathers.png');
        }
        .decorative-bg {
            background-image: 
                radial-gradient(circle at top left, rgba(239, 227, 217, 0.5), transparent 40%),
                radial-gradient(circle at top right, rgba(239, 227, 217, 0.5), transparent 40%);
        }
        .filter-btn.active {
            background-color: #6d5d4d;
            color: white;
            border-color: #6d5d4d;
        }
        .catalog-item {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
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
                Cara Terbaik Kabarkan Momen Spesial Anda
            </h1>
            <p class="mt-4 text-sm md:text-base text-gray-600">
                Saatnya beralih ke undangan digital. Sebarkan momen bahagiamu tanpa terhalang jarak dan waktu.
            </p>
            <a href="https://api.whatsapp.com/send?phone=6281214019947&text=Halo,%20saya%20tertarik%20dengan%20undangan%20digitalnya." target="_blank" class="inline-flex items-center justify-center gap-2 bg-[#25D366] text-white font-bold px-6 py-3 rounded-full mt-8 shadow-lg hover:bg-[#128C7E] transition-colors">
                <i class="ph ph-whatsapp-logo text-xl"></i>
                <span>Konsultasi via WA</span>
            </a>
        </header>

        <main class="mt-16 md:mt-24">
            <div class="text-center">
                <h2 class="font-serif text-xl md:text-2xl font-bold tracking-[0.2em] text-gray-500">KATALOG TEMA PILIHAN</h2>
                <p class="mt-2 text-gray-600">Pilih kategori untuk melihat tema undangan kami.</p>
                
                <div id="filter-buttons" class="flex flex-wrap justify-center gap-2 sm:gap-4 mt-6">
                    <button type="button" data-filter="all" class="filter-btn active px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm transition">Semua</button>
                    <button type="button" data-filter="classic_gold" class="filter-btn px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm transition">Classic</button>
                    <button type="button" data-filter="rustic_elegance" class="filter-btn px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm transition">Rustic</button>
                    <button type="button" data-filter="modern_minimalist" class="filter-btn px-5 py-2 text-sm font-semibold text-brand-text bg-white border border-brand-brown-dark rounded-full shadow-sm transition">Modern</button>
                </div>
            </div>

            <div id="catalog-grid" class="mt-12 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($katalog as $kategori => $items)
                    @foreach ($items as $item)
                        <div class="catalog-item text-center group" data-category="{{ $kategori }}">
                            <div class="bg-white border border-gray-200 rounded-lg p-2 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                <div class="overflow-hidden rounded-md">
                                    <img src="{{ $item['preview_img'] }}" alt="{{ $item['nama'] }}" loading="lazy" class="w-full h-auto object-cover aspect-[3/4] transition-transform duration-300 group-hover:scale-110">
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-800 mt-4 text-sm">{{ $item['nama'] }}</h3>
                            <div class="flex flex-col sm:flex-row gap-2 justify-center mt-2">
                                <a href="{{ route('katalog.show', ['id' => $item['id']]) }}" target="_blank" class="text-xs px-4 py-1.5 border border-brand-brown-dark text-brand-text rounded-full hover:bg-brand-brown transition">Lihat Demo</a>
                                <a href="#" class="text-xs px-4 py-1.5 bg-brand-text text-white rounded-full hover:opacity-80 transition">Pesan</a>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </main>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('#filter-buttons .filter-btn');
    const catalogItems = document.querySelectorAll('#catalog-grid .catalog-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            catalogItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (filterValue === 'all' || filterValue === itemCategory) {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                    setTimeout(() => {
                        item.style.display = 'block';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
</script>

</body>
</html>