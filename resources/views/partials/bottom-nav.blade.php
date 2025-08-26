<!-- 
  - PENTING: Tidak ada class `md:hidden` di sini.
  - Ini membuat bottom navigation selalu tampil di semua ukuran layar.
-->
<div class="fixed bottom-0 left-0 right-0 bg-white shadow-[0_-2px_10px_rgba(0,0,0,0.1)] z-20 md:left-64">
    <nav class="flex justify-around h-16">
        <!-- Item 1: Souvenir -->
        <a href="{{ url('souvenir') }}" class="flex flex-col items-center justify-center w-full text-center {{ request()->is('souvenir*') ? 'text-blue-600' : 'text-slate-500' }}">
            <i class="fas fa-gift text-xl"></i>
            <span class="text-xs font-medium mt-1">Souvenir</span>
        </a>
        
        <!-- Item 2: Cari Tamu -->
        <a href="{{ url('cari-tamu') }}" class="flex flex-col items-center justify-center w-full text-center {{ request()->is('cari-tamu*') ? 'text-blue-600' : 'text-slate-500' }}">
            <i class="fas fa-search text-xl"></i>
            <span class="text-xs font-medium mt-1">Cari Tamu</span>
        </a>
        
        <!-- Item 3: Scan QR -->
        <a href="{{ url('check-in') }}" class="flex flex-col items-center justify-center w-full text-center {{ request()->is('check-in*') ? 'text-blue-600' : 'text-slate-500' }}">
            <i class="fas fa-qrcode text-xl"></i>
            <span class="text-xs font-medium mt-1">Scan QR</span>
        </a>
    </nav>
</div>
