<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Undangan Digital Modern</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc; /* slate-50 */
            color: #334155; /* slate-700 */
        }
        .font-header {
            font-family: 'Montserrat', sans-serif;
        }
        .filter-btn.active {
            background-color: #f59e0b; /* amber-500 */
            color: white;
            border-color: #f59e0b; /* amber-500 */
            box-shadow: 0 4px 14px 0 rgb(245 158 11 / 39%);
        }
        .hero-slider .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <header class="bg-white/80 backdrop-blur-lg text-slate-800 shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-xl font-header font-bold text-amber-600">DigitalInvitation</a>
                <nav class="flex items-center gap-4 md:gap-6">
                    <a href="#katalog" class="text-sm font-semibold hover:text-amber-600 transition-colors">
                        Katalog
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=6281214019947" target="_blank" class="bg-amber-500 text-white px-4 py-2 rounded-full text-sm font-semibold tracking-wider hover:bg-amber-600 transition-all shadow-sm hover:shadow-md">
                        Konsultasi Gratis
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <section class="relative h-[70vh] md:h-[80vh] flex items-center text-white">
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
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="max-w-xl text-left">
                <h1 class="text-4xl md:text-6xl font-header font-extrabold tracking-tight">
                    Buat Momen Spesialmu Tak Terlupakan
                </h1>
                <p class="mt-4 text-lg md:text-xl text-white/90 leading-relaxed">
                    Undangan digital modern, cepat, dan praktis. Sebarkan kebahagiaanmu dengan sekali klik.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row items-start justify-start gap-4">
                    <a href="#katalog" class="bg-amber-500 text-white font-bold px-8 py-3 rounded-full shadow-lg hover:bg-amber-400 transition-all transform hover:scale-105">
                        Lihat Desain
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=6281214019947" target="_blank" class="bg-white/20 backdrop-blur-sm font-semibold px-8 py-3 rounded-full hover:bg-white/30 transition-colors">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <main id="katalog" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        
        <section class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold font-header tracking-tight">
                Pilihan <span class="text-amber-600">Desain</span> Eksklusif
            </h2>
            <p class="mt-3 text-slate-500 max-w-2xl mx-auto">
                Setiap kategori memiliki fitur unggulannya masing-masing. Pilih tema yang paling sesuai dengan acaramu.
            </p>

            <div class="max-w-5xl mx-auto mt-12 bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
                <div id="filter-buttons" class="flex flex-wrap justify-center gap-2 md:gap-3">
                    @foreach ($katalogData as $kategori)
                        <button 
                            type="button" 
                            data-filter="{{ $kategori['slug'] }}" 
                            data-name="{{ $kategori['name'] }}"
                            data-features="{{ json_encode($kategori['features']) }}"
                            class="filter-btn {{ $loop->first ? 'active' : '' }} px-5 py-2 text-sm font-semibold border border-slate-300 rounded-full hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-300 flex items-center gap-2">
                            {{ $kategori['name'] }}
                            <span class="bg-slate-200 text-slate-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($kategori['items']) }}</span>
                        </button>
                    @endforeach
                </div>
                <div id="tema-details" class="mt-6 pt-6 border-t border-slate-200/80 transition-all duration-500">
                    <h3 id="tema-title" class="text-xl font-bold font-header text-amber-800"></h3>
                    <div id="tema-features-list" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-slate-600">
                    </div>
                </div>
            </div>
        </section>
        
        <div id="catalog-grid" class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
            @foreach ($katalogData as $kategoriSlug => $kategori)
                @foreach ($kategori['items'] as $item)
                    <div class="catalog-item group" data-category="{{ $kategori['slug'] }}">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                            <div class="relative">
                                <img src="{{ $item['preview_img'] }}" alt="{{ $item['nama'] }}" loading="lazy" class="w-full h-auto object-cover aspect-[9/16]">
                                <div class="absolute top-3 right-3 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                                    {{ $kategori['name'] }}
                                </div>
                            </div>

                            <div class="p-5 flex flex-col h-full">
                                <div class="flex-grow">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $item['tag'] }}</span>
                                        @if($item['bisa_ganti_font'])
                                        <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">Custom Font</span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-lg text-slate-800 group-hover:text-amber-600 transition-colors">{{ $item['nama'] }}</h4>
                                    <div class="mt-3 flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-amber-600">Rp{{ number_format($item['harga_diskon'], 0, ',', '.') }}</span>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm text-slate-400 line-through">Rp{{ number_format($item['harga_asli'], 0, ',', '.') }}</span>
                                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-md">-{{ round((($item['harga_asli'] - $item['harga_diskon']) / $item['harga_asli']) * 100) }}%</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    @if(isset($item['is_dynamic']) && $item['is_dynamic'])
                                        <a href="{{ route('katalog.design.demo', $item['id']) }}" target="_blank" class="w-full text-center text-sm px-4 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 hover:border-slate-400 transition flex items-center justify-center gap-2">
                                            <i class="ph ph-eye"></i>
                                            Lihat Demo
                                        </a>
                                        <a href="{{ route('order.start', ['template_id' => 'custom-' . $item['id']]) }}" class="w-full text-center text-sm px-4 py-2.5 bg-amber-500 text-white font-bold rounded-lg hover:bg-amber-600 transition shadow-sm hover:shadow-lg hover:-translate-y-0.5 transform flex items-center justify-center gap-2">
                                            <i class="ph ph-shopping-cart-simple"></i>
                                            Pesan Desain
                                        </a>
                                    @else
                                        <a href="{{ route('katalog.demo', $item['id']) }}" target="_blank" class="w-full text-center text-sm px-4 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 hover:border-slate-400 transition flex items-center justify-center gap-2">
                                            <i class="ph ph-eye"></i>
                                            Lihat Demo
                                        </a>
                                        <a href="{{ route('katalog.show', $item['id']) }}" class="w-full text-center text-sm px-4 py-2.5 bg-amber-500 text-white font-bold rounded-lg hover:bg-amber-600 transition shadow-sm hover:shadow-lg hover:-translate-y-0.5 transform flex items-center justify-center gap-2">
                                            <i class="ph ph-shopping-cart-simple"></i>
                                            Detail & Pesan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </main>

    <section class="bg-slate-800 text-white py-16 md:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold font-header">Semua Fitur Terbaik Untuk Anda</h2>
            <p class="mt-3 text-slate-400 max-w-2xl mx-auto">
                Dari galeri foto hingga amplop digital, semua yang Anda butuhkan sudah tersedia dalam satu paket.
            </p>
            <div class="mt-12 max-w-5xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-6 gap-y-6 text-left">
                @php
                    $features = [
                        'Nama Tamu Eksklusif', 'Galeri & Video Prewedding', 'Amplop Digital & Kado',
                        'Navigasi Peta Lokasi', 'Background Musik Custom', 'Konfirmasi Kehadiran (RSVP)',
                        'Buku Tamu & Ucapan', 'Hitung Mundur Acara', 'Love Story & Quotes',
                        'Informasi Dresscode', 'Live Streaming Acara', 'Revisi Tanpa Batas'
                    ];
                @endphp
                @foreach ($features as $feature)
                <div class="flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-2xl text-amber-400"></i>
                    <span class="text-slate-300">{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-slate-100 border-t border-slate-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-slate-500 text-sm">
            <p>&copy; {{ date('Y') }} DigitalInvitation. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('#filter-buttons .filter-btn');
        const catalogItems = document.querySelectorAll('#catalog-grid .catalog-item');
        const temaTitle = document.getElementById('tema-title');
        const temaFeaturesList = document.getElementById('tema-features-list');
        const temaDetailsSection = document.getElementById('tema-details');

        function updateTemaDetails(button) {
            const name = button.dataset.name;
            const features = JSON.parse(button.dataset.features);
            
            temaDetailsSection.style.opacity = '0';
            
            setTimeout(() => {
                temaTitle.textContent = `#Fitur Unggulan Tema ${name}`;
                let featuresHtml = '';
                features.forEach(feature => {
                    featuresHtml += `
                        <div class="flex items-start gap-2.5">
                            <i class="ph-fill ph-check-circle text-amber-500 mt-1 flex-shrink-0"></i>
                            <span>${feature}</span>
                        </div>`;
                });
                temaFeaturesList.innerHTML = featuresHtml;
                temaDetailsSection.style.opacity = '1';
            }, 300);
        }

        function setInitialCatalogState() {
            const initialActiveButton = document.querySelector('#filter-buttons .filter-btn.active');
            if (!initialActiveButton) return;
            const initialFilterValue = initialActiveButton.getAttribute('data-filter');
            catalogItems.forEach(item => {
                if (item.dataset.category === initialFilterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        const initialActiveButton = document.querySelector('#filter-buttons .filter-btn.active');
        if (initialActiveButton) {
            updateTemaDetails(initialActiveButton);
        }
        
        setInitialCatalogState();

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                updateTemaDetails(this);
                
                const filterValue = this.getAttribute('data-filter');
                
                catalogItems.forEach(item => {
                    item.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    
                    setTimeout(() => {
                        if (item.dataset.category === filterValue) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'scale(1)';
                            }, 50);
                        } else {
                            item.style.display = 'none';
                        }
                    }, 300);
                });
            });
        });

        const heroSwiper = new Swiper('.hero-slider', {
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            speed: 1000,
        });
    });
    </script>

</body>
</html>

