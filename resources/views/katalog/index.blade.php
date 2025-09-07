<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Undangan Digital</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        .font-header {
            font-family: 'Montserrat', sans-serif;
        }
        .filter-btn.active {
            background-color: #f59e0b; /* amber-500 */
            color: white;
            border-color: #f59e0b;
        }
        .writing-mode-vertical {
            writing-mode: vertical-rl;
            text-orientation: mixed;
        }
        .hero-slider .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="text-gray-800">

    {{-- BAGIAN HEADER YANG DIPERBARUI --}}
    <header class="bg-amber-600 text-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <a href="/" class="text-2xl font-header font-bold">Undangan Digital</a>
                
                {{-- DIUBAH: Mengganti tombol MENU dengan dua link navigasi --}}
                <div class="flex items-center gap-4 md:gap-6">
                    <a href="#katalog" class="text-sm font-semibold hover:text-white/80 transition-colors">
                        Katalog
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=6281214019947" target="_blank" class="border border-white/50 px-4 py-1.5 rounded-md text-sm font-semibold tracking-wider hover:bg-white hover:text-amber-600 transition-colors">
                        Konsultasi Gratis
                    </a>
                </div>

            </div>
        </div>
    </header>

    <section class="relative h-[60vh] md:h-[70vh]">
        <div class="swiper hero-slider absolute inset-0 w-full h-full -z-10">
            <div class="swiper-wrapper">
                @php
                    $heroImages = ['1.png', '2.png', '3.png', '4.jpg', '5.png'];
                @endphp
                @foreach ($heroImages as $image)
                    <div class="swiper-slide">
                        <img src="{{ asset('images/' . $image) }}" alt="Wedding Background Slide">
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="absolute inset-0 bg-black/50 z-0"></div>

        <div class="relative h-full flex items-center justify-center text-center text-white px-4 sm:px-6 lg:px-8">
            <div class="z-10">
                <h1 class="text-3xl md:text-5xl font-bold font-header">Undangan Digital Express #1</h1>
                <p class="mt-2 text-lg md:text-xl">Cepat, Mudah, dan Praktis</p>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#katalog" class="bg-yellow-400 text-gray-900 font-bold px-8 py-3 rounded-full shadow-lg hover:bg-yellow-300 transition-colors">
                        Lihat Katalog Undangan
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=6281214019947" target="_blank" class="w-full sm:w-auto bg-amber-600 font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-amber-500 transition-colors">
                        Hubungi WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <main id="katalog" class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        
        <section class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold font-header">
                Pilihan <span class="text-amber-600">Tema</span>
            </h2>
            <p class="mt-2 text-gray-500">Klik tombol di bawah ini untuk melihat contoh katalog</p>
            
            <div id="filter-buttons" class="flex flex-wrap justify-center gap-2 md:gap-4 mt-8">
                @foreach ($katalogData as $kategori)
                    <button 
                        type="button" 
                        data-filter="{{ $kategori['slug'] }}" 
                        data-name="{{ $kategori['name'] }}"
                        data-features="{{ json_encode($kategori['features']) }}"
                        class="filter-btn {{ $loop->first ? 'active' : '' }} px-4 py-2 text-sm font-semibold border border-gray-300 rounded-full hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-colors flex items-center gap-2">
                        {{ $kategori['name'] }}
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($kategori['items']) }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        <section id="tema-details" class="mt-12 max-w-4xl mx-auto bg-gray-100 p-6 rounded-lg shadow-sm">
             <h3 id="tema-title" class="text-2xl font-bold font-header text-amber-700">#Tema Special</h3>
             <div id="tema-features-list" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-gray-700">
                </div>
        </section>
        
        <div id="catalog-grid" class="mt-12 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($katalogData as $kategoriSlug => $kategori)
                @foreach ($kategori['items'] as $item)
                    <div class="catalog-item group" data-category="{{ $kategoriSlug }}" style="{{ !$loop->parent->first ? 'display: none;' : '' }}">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 relative">
                            <div class="absolute top-0 right-0 h-full bg-gray-700 text-white font-bold p-2 flex items-center justify-center">
                                <span class="writing-mode-vertical transform rotate-180 uppercase tracking-widest text-sm">{{ $kategori['name'] }} {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>

                            <div class="pr-10">
                                <img src="{{ $item['preview_img'] }}" alt="{{ $item['nama'] }}" loading="lazy" class="w-full h-auto object-cover aspect-[9/16]">
                            </div>

                            <div class="p-4 text-left">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-gray-200 text-gray-600 text-xs font-semibold px-2 py-1 rounded-full">{{ $item['tag'] }}</span>
                                    @if($item['bisa_ganti_font'])
                                    <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-1 rounded-full">Bisa Ganti Font</span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-lg">{{ $item['nama'] }}</h4>
                                
                                <div class="mt-2 flex items-baseline gap-2">
                                     <span class="text-xl font-bold text-amber-600">Rp {{ number_format($item['harga_diskon'], 0, ',', '.') }}</span>
                                     <span class="text-sm text-gray-400 line-through">Rp {{ number_format($item['harga_asli'], 0, ',', '.') }}</span>
                                     <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-md">-{{ round((($item['harga_asli'] - $item['harga_diskon']) / $item['harga_asli']) * 100) }}%</span>
                                </div>

                                <div class="mt-4 grid grid-cols-1 gap-2">
                                    <a href="{{ route('katalog.show', ['id' => $item['id']]) }}" target="_blank" class="w-full text-center text-sm px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition flex items-center justify-center gap-2">
                                        <i class="ph ph-eye"></i>
                                        Lihat Contoh
                                    </a>
                                    <a href="{{ route('order.start', ['template_id' => $item['id']]) }}" class="w-full text-center text-sm px-4 py-2 bg-amber-600 text-white font-bold rounded-lg hover:bg-amber-700 transition shadow flex items-center justify-center gap-2">
                                        <i class="ph ph-shopping-cart-simple"></i>
                                        Pesan Sekarang
                                    </a>
                                 </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

    </main>

    <section class="bg-amber-700 text-white py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold font-header">Fitur Undangan</h2>
            <div class="mt-8 max-w-4xl mx-auto grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-4 text-left">
                @php
                    $features = [
                        'Sebar Undangan Sepuasnya', 'Custom Penerima Nama Tamu', 'Profil Mempelai',
                        'Informasi Detail Acara', 'Bisa Ganti Tema', 'Revisi Tanpa Batas',
                        'Konfirmasi Kehadiran / RSVP', 'Ucapan & Do\'a', 'Bebas Custom Backsound',
                        'Navigasi Google Map', 'Amplop Digital', 'Penerima Kado',
                        'Galeri Foto', 'Video Prewedding', 'Quotes/ Ayat suci',
                        'Dresscode', 'Live Streaming', 'Love Story', 'Hitung Mundur (Countdown)'
                    ];
                @endphp
                @foreach ($features as $feature)
                <div class="flex items-center gap-2">
                    <i class="ph-fill ph-check-circle text-xl text-yellow-300"></i>
                    <span>{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('#filter-buttons .filter-btn');
        const catalogItems = document.querySelectorAll('#catalog-grid .catalog-item');
        const temaTitle = document.getElementById('tema-title');
        const temaFeaturesList = document.getElementById('tema-features-list');

        function updateTemaDetails(button) {
            const name = button.dataset.name;
            const features = JSON.parse(button.dataset.features);
            temaTitle.textContent = `#Tema ${name}`;
            let featuresHtml = '';
            features.forEach(feature => {
                featuresHtml += `
                    <div class="flex items-start gap-2">
                        <i class="ph-fill ph-check-circle text-amber-500 mt-1"></i>
                        <span>${feature}</span>
                    </div>`;
            });
            temaFeaturesList.innerHTML = featuresHtml;
        }

        const initialActiveButton = document.querySelector('#filter-buttons .filter-btn.active');
        if (initialActiveButton) {
            updateTemaDetails(initialActiveButton);
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateTemaDetails(this);
                const filterValue = this.getAttribute('data-filter');
                catalogItems.forEach(item => {
                    if (filterValue === 'all' || item.dataset.category === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        const heroSwiper = new Swiper('.hero-slider', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
        });
    });
    </script>

</body>
</html>