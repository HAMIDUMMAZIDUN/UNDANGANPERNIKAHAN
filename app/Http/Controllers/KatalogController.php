<?php

namespace App\Http\Controllers;

use App\Models\Design; // Pastikan model Design di-import
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class KatalogController extends Controller
{
    /**
     * Mengambil data katalog gabungan (statis dan dinamis).
     * @return array
     */
    private function getKatalogData(): array
    {
        // 1. Ambil desain dinamis dari database
        $dynamicDesigns = Design::latest()->get();
        $formattedDynamicDesigns = [];

        foreach ($dynamicDesigns as $design) {
            $preview_img = 'https://placehold.co/600x900/7c3aed/white?text=' . urlencode($design->name);
            $firstComponent = $design->structure[0] ?? null;
            if ($firstComponent && !empty($firstComponent['styles']['backgroundImage'])) {
                $preview_img = $firstComponent['styles']['backgroundImage'];
            }
            
            $formattedDynamicDesigns[] = [
                'id' => $design->id,
                'nama' => $design->name,
                'tag' => 'Kustom',
                'harga_asli' => 250000,
                'harga_diskon' => 149000,
                'bisa_ganti_font' => true,
                'preview_img' => $preview_img,
                'is_dynamic' => true, 
            ];
        }

        // 2. Data template statis
        $staticData = [
            'special' => [
                'name' => 'Special', 'slug' => 'special',
                'features' => ['Masa aktif 10 bulan', 'Galeri maksimal 10 foto', 'Sebar undangan tanpa batas', 'Sudah termasuk seluruh fitur lengkap'],
                'items' => [
                    ['id' => 1, 'nama' => 'Special 01', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true, 'preview_img' => asset('images/previews/classic-gold-1.png'), 'template' => 'undangan.templates.classic-gold'],
                    ['id' => 2, 'nama' => 'Special 02', 'tag' => 'Adat Jawa', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true, 'preview_img' => asset('images/previews/rustic-bohemian.png'), 'template' => 'undangan.templates.rustic-brown'],
                    ['id' => 3, 'nama' => 'Special 03', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => false, 'preview_img' => asset('images/previews/greenery-charm.png'), 'template' => 'undangan.templates.greenery-charm'],
                    ['id' => 4, 'nama' => 'Special 04', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => false, 'preview_img' => asset('images/previews/modern-monochrome.png'), 'template' => 'undangan.templates.modern-monochrome'],
                    ['id' => 5, 'nama' => 'Special 05', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true, 'preview_img' => asset('images/previews/simple-clean.png'), 'template' => 'undangan.templates.simple-clean'],
                ]
            ],
            'luxury' => [ 'name' => 'Luxury', 'slug' => 'luxury', 'features' => ['Masa aktif 12 bulan', 'Galeri maksimal 20 foto', 'Fitur premium terbuka', 'Dukungan prioritas'], 'items' => [] ],
            '3d_motion' => [ 'name' => '3D Motion', 'slug' => '3d_motion', 'features' => ['Animasi 3D interaktif', 'Masa aktif selamanya', 'Semua fitur premium', 'Desain eksklusif'], 'items' => [] ],
            'art' => [ 'name' => 'Art', 'slug' => 'art', 'features' => ['Desain artistik & unik', 'Masa aktif 12 bulan', 'Palet warna custom', 'Termasuk fitur lengkap'], 'items' => [] ],
            'tema_adat' => [ 'name' => 'Tema Adat', 'slug' => 'tema_adat', 'features' => ['Ornamen khas adat nusantara', 'Masa aktif 10 bulan', 'Backsound musik daerah', 'Termasuk fitur lengkap'], 'items' => [] ],
            'tanpa_foto' => [ 'name' => 'Tanpa Foto', 'slug' => 'tanpa_foto', 'features' => ['Fokus pada ilustrasi & tipografi', 'Proses pembuatan lebih cepat', 'Masa aktif 10 bulan', 'Termasuk fitur lengkap'], 'items' => [] ],
        ];

        $staticData['special']['items'] = array_merge($formattedDynamicDesigns, $staticData['special']['items']);
        
        return $staticData;
    }

    public function index(): View
    {
        $katalogData = $this->getKatalogData();
        return view('katalog.index', ['katalogData' => $katalogData]);
    }

    public function show(int $id): View
    {
        $katalogData = $this->getKatalogData();
        $allItems = collect($katalogData)->pluck('items')->flatten(1);
        $item = $allItems->firstWhere('id', $id);

        if (!$item) {
            abort(404, 'Item katalog tidak ditemukan.');
        }

        return view('katalog.show', ['item' => $item]);
    }

    public function showDemo(int $id): View
    {
        $katalog = $this->getKatalogData();
        $allItems = collect($katalog)->pluck('items')->flatten(1);
        $theme = $allItems->firstWhere('id', $id);

        if (isset($theme['is_dynamic']) && $theme['is_dynamic']) {
            return $this->showDynamicDemo(Design::find($theme['id']));
        }

        if (!$theme || !isset($theme['template'])) {
            abort(404, 'Tema atau template untuk demo tidak ditemukan.');
        }

        $event = (object) [ 'groom_name' => 'Aditya', 'bride_name' => 'Lestari', 'date' => now()->addDays(45)->toDateString(), 'photo_url' => $theme['preview_img'], 'music_url' => asset('music/musik1.mp3'), 'groom_photo' => 'https://source.unsplash.com/random/400x400/?man,portrait', 'bride_photo' => 'https://source.unsplash.com/random/400x401/?woman,portrait', 'groom_parents' => 'Bpk. Soleh & Ibu. Aminah', 'bride_parents' => 'Bpk. Budi & Ibu. Wati', 'groom_instagram' => 'aditya.insta', 'bride_instagram' => 'lestari.insta', 'akad_time' => '09:00', 'resepsi_time' => '11:00 - 14:00', 'akad_location' => 'Masjid Istiqlal, Jakarta', 'resepsi_location' => 'Balai Kartini, Jakarta', 'akad_maps_url' => '#', 'resepsi_maps_url' => '#', ];
        $guest = (object) ['uuid' => 'demo-guest-uuid', 'name' => 'Bpk/Ibu Tamu Undangan'];
        $photos = collect([ (object)['path' => 'https://source.unsplash.com/random/800x800/?wedding,couple'], (object)['path' => 'https://source.unsplash.com/random/800x801/?wedding,couple'], ]);
        $rsvps = new LengthAwarePaginator([], 0, 5);
        
        return view($theme['template'], compact('event', 'guest', 'photos', 'rsvps', 'theme'));
    }

    /**
     * METHOD BARU: Menampilkan demo untuk desain dinamis.
     * @param Design $design
     * @return View
     */
    public function showDynamicDemo(Design $design): View
    {
        $formattedDate = now()->isoFormat('dddd, D MMMM YYYY');
        $dummyEvent = (object) [
            'groom_name' => 'Putra', 'groom_parents' => 'Bapak Budi & Ibu Wati', 'bride_name' => 'Putri', 'bride_parents' => 'Bapak Santoso & Ibu Lestari', 'date_formatted' => $formattedDate,
            'cover_photo_url' => 'https://images.unsplash.com/photo-1597861405922-26b21c43c4f9?q=85&fm=jpg&w=1200', 'couple_photo_url' => 'https://images.unsplash.com/photo-1529602264082-036993544063?q=85&fm=jpg&w=1200', 'video_placeholder_url' => 'https://images.unsplash.com/photo-1515934323957-66a7a092a452?q=85&fm=jpg&w=1200',
            'akad_time' => '08:00 - 10:00 WIB', 'resepsi_time' => '11:00 - 14:00 WIB', 'akad_location' => 'Masjid Istiqlal, Jakarta', 'resepsi_location' => 'Gedung Balai Kartini, Jakarta',
            'gallery_photos' => [['url' => 'https://images.unsplash.com/photo-1523438943922-386d36e439fe?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1606992257321-2527ac312a02?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1532712938310-34cb39825785?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1546193435-99805a99859f?q=85&fm=jpg&w=800'],]
        ];
        // Menggunakan view preview yang sudah ada
        return view('admin.design.preview', compact('design', 'dummyEvent'));
    }
}

