<footer class="bg-slate-900 text-slate-400 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            {{-- Info Perusahaan --}}
            <div>
                <h3 class="text-xl font-header font-bold text-white mb-4">DigitalInvitation</h3>
                <p class="text-sm">
                    Platform undangan digital modern untuk momen spesial Anda. Dibuat dengan ❤️ di Indonesia.
                </p>
            </div>

            {{-- Tautan Cepat --}}
            <div>
                <h4 class="font-semibold text-white mb-4">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#katalog" class="hover:text-amber-500 transition-colors">Katalog</a></li>
                    <li><a href="#" class="hover:text-amber-500 transition-colors">Cara Pemesanan</a></li>
                    <li><a href="#" class="hover:text-amber-500 transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Info Kontak --}}
            <div>
                <h4 class="font-semibold text-white mb-4">Hubungi Kami</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-3">
                        <i class="ph-fill ph-whatsapp-logo text-lg text-slate-500"></i>
                        <a href="https://wa.me/6281214019947" class="hover:text-amber-500 transition-colors">+62 812-1401-9947</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph-fill ph-envelope-simple text-lg text-slate-500"></i>
                        <a href="mailto:info@digitalinvitation.com" class="hover:text-amber-500 transition-colors">info@digitalinvitation.com</a>
                    </li>
                </ul>
            </div>

            {{-- Media Sosial --}}
            <div>
                <h4 class="font-semibold text-white mb-4">Ikuti Kami</h4>
                <div class="flex gap-4">
                    <a href="#" aria-label="Instagram" class="text-slate-400 hover:text-amber-500 transition-colors">
                        <i class="ph-fill ph-instagram-logo text-2xl"></i>
                    </a>
                    <a href="#" aria-label="Facebook" class="text-slate-400 hover:text-amber-500 transition-colors">
                        <i class="ph-fill ph-facebook-logo text-2xl"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="text-slate-400 hover:text-amber-500 transition-colors">
                        <i class="ph-fill ph-youtube-logo text-2xl"></i>
                    </a>
                </div>
            </div>
            
        </div>

        {{-- Copyright --}}
        <div class="mt-12 pt-8 border-t border-slate-800 text-center text-sm">
            <p>&copy; {{ now()->year }} DigitalInvitation. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</footer>