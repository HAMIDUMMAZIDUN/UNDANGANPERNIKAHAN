<section class="hero-slider swiper relative h-[500px] md:h-[600px] overflow-hidden">
    <div class="swiper-wrapper">
        @foreach($heroImages as $slide)
            <div class="swiper-slide relative">
                {{-- Background Image --}}
                <img src="{{ asset($slide['image']) }}" 
                     alt="{{ $slide['title'] }}" 
                     loading="lazy"
                     class="w-full h-full object-cover">
                
                {{-- Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                
                {{-- Text Content --}}
                <div class="absolute inset-0 flex flex-col justify-end items-center text-center p-8 md:p-16 text-white">
                    <h2 class="text-3xl md:text-5xl font-header font-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                        {{ $slide['title'] }}
                    </h2>
                    @if(isset($slide['subtitle']))
                        <p class="mt-4 max-w-xl" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.6);">
                            {{ $slide['subtitle'] }}
                        </p>
                    @endif
                    <a href="#katalog" class="mt-8 bg-amber-500 text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-amber-600 transition-all duration-300 transform hover:scale-105">
                        Lihat Desain
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>