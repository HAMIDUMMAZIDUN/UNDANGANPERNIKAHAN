<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Undangan Digital Modern | DigitalInvitation</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        .font-header { font-family: 'Montserrat', sans-serif; }
        body { font-family: 'Poppins', sans-serif; }
        .swiper-pagination-bullet-active { background-color: #f97316 !important; }
        .filter-btn.active { background-color: #f97316; color: white; }
    </style>
</head>
<body x-data="katalogHandler(@json($katalogData))" x-init="init()">
    
    @include('components.katalog.header')

    @include('components.katalog.hero', ['heroImages' => $heroImages])
    
    <main id="katalog" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        
        {{-- Filter Section --}}
        <section id="filter-section" class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-header font-extrabold text-slate-800">Pilih Tema Impian Anda</h2>
                <p class="mt-2 text-slate-500 max-w-xl mx-auto">Setiap tema dirancang dengan fitur-fitur eksklusif untuk hari spesial Anda.</p>
            </div>
            <div id="filter-buttons" class="flex flex-wrap justify-center gap-2 md:gap-3 mb-10">
                <template x-for="katalog in katalogData" :key="katalog.category">
                    <button @click="setActiveCategory(katalog.category)"
                            :class="{ 'active': activeCategory === katalog.category }"
                            class="filter-btn px-4 py-2 text-sm md:text-base font-semibold rounded-full transition-all duration-300 hover:bg-amber-100 hover:text-amber-700">
                        <span x-text="katalog.name"></span>
                    </button>
                </template>
            </div>
            <div id="tema-details" class="max-w-3xl mx-auto bg-slate-50 p-6 rounded-2xl transition-opacity duration-300" x-show="activeThemeDetails" x-transition.opacity>
                <h3 id="tema-title" class="font-bold text-slate-700 mb-4" x-text="`#Fitur Unggulan Tema ${activeThemeDetails.name}`"></h3>
                <div id="tema-features-list" class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm text-slate-600">
                    <template x-for="feature in activeThemeDetails.features" :key="feature">
                        <div class="flex items-start gap-2.5">
                            <i class="ph-fill ph-check-circle text-amber-500 mt-1 flex-shrink-0"></i>
                            <span x-text="feature"></span>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        {{-- Catalog Grid --}}
        <section id="catalog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="item in activeItems" :key="item.id">
                <div class="catalog-item bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden"
                     x-show="isItemVisible(item.category)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    
                    <a :href="item.demo_url" target="_blank" class="block">
                        <img :src="item.thumbnail" :alt="item.name" class="w-full h-60 object-cover">
                    </a>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-xl text-slate-800" x-text="item.name"></h3>
                                <p class="text-sm text-amber-600 font-semibold mt-1" x-text="item.category_name"></p>
                            </div>
                            <span class="text-lg font-bold text-slate-700" x-text="item.price_formatted"></span>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <a :href="item.demo_url" target="_blank" class="flex-1 text-center bg-slate-100 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-200 transition-colors">Lihat Demo</a>
                            <a :href="item.order_url" class="flex-1 text-center bg-amber-500 text-white font-semibold py-2.5 rounded-lg hover:bg-amber-600 transition-colors">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            </template>
        </section>
        
    </main>

    @include('components.katalog.features')
    @include('components.katalog.footer')

    <script>
    function katalogHandler(katalogData) {
        return {
            katalogData: katalogData,
            activeCategory: 'premium', // Kategori awal yang aktif
            
            init() {
                // Inisialisasi Hero Slider
                this.$nextTick(() => {
                    new Swiper('.hero-slider', {
                        loop: true,
                        effect: 'fade',
                        fadeEffect: { crossFade: true },
                        autoplay: { delay: 4000, disableOnInteraction: false },
                        speed: 1000,
                    });
                });
            },
            
            // Mengambil detail tema yang sedang aktif
            get activeThemeDetails() {
                return this.katalogData.find(k => k.category === this.activeCategory);
            },

            // Mengambil semua item dari semua kategori
            get allItems() {
                return this.katalogData.flatMap(k => k.items);
            },
            
            // Mengambil item yang sesuai dengan kategori aktif
            get activeItems() {
                if (this.activeCategory === 'all') {
                    return this.allItems;
                }
                const activeKatalog = this.katalogData.find(k => k.category === this.activeCategory);
                return activeKatalog ? activeKatalog.items : [];
            },

            // Fungsi untuk mengganti kategori aktif
            setActiveCategory(category) {
                this.activeCategory = category;
            },
            
            // Fungsi untuk menentukan visibilitas item (selalu true karena sudah difilter di `activeItems`)
            isItemVisible(itemCategory) {
                return true;
            }
        }
    }
    </script>
</body>
</html>