<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class KatalogController extends Controller
{
    /**
     * Mengambil data katalog statis.
     * @return array
     */
    private function getKatalogData(): array
    {
        // Path gambar sekarang disesuaikan dengan direktori public Anda
        return [
            'special' => [
                'name' => 'Special',
                'slug' => 'special',
                'features' => [
                    'Masa aktif 10 bulan',
                    'Galeri maksimal 10 foto',
                    'Sebar undangan tanpa batas',
                    'Sudah termasuk seluruh fitur lengkap'
                ],
                'items' => [
                    // PERHATIKAN: 'preview_img' sekarang menggunakan asset() dengan path gambar yang benar
                    [
                        'id' => 1, 'nama' => 'Special 01', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true,
                        'preview_img' => asset('images/previews/classic-gold-1.png'),
                        'template' => 'katalog.themes.classic-gold'
                    ],
                    [
                        'id' => 2, 'nama' => 'Special 02', 'tag' => 'Adat Jawa', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true,
                        'preview_img' => asset('images/previews/rustic-bohemian.png'),
                        'template' => 'katalog.themes.rustic-bohemian'
                    ],
                    [
                        'id' => 3, 'nama' => 'Special 03', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => false,
                        'preview_img' => asset('images/previews/greenery-charm.png'),
                        'template' => 'katalog.themes.greenery-charm'
                    ],
                    [
                        'id' => 4, 'nama' => 'Special 04', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => false,
                        'preview_img' => asset('images/previews/modern-monochrome.png'),
                        'template' => 'katalog.themes.modern-monochrome'
                    ],
                    [
                        'id' => 5, 'nama' => 'Special 05', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true,
                        'preview_img' => asset('images/previews/simple-clean.png'),
                        'template' => 'katalog.themes.simple-clean'
                    ],
                    [
                        'id' => 6, 'nama' => 'Special 06', 'tag' => 'Tema General', 'harga_asli' => 200000, 'harga_diskon' => 99000, 'bisa_ganti_font' => true,
                        'preview_img' => asset('images/previews/classic-gold-1.png'), // Menggunakan gambar yang ada, silakan sesuaikan
                        'template' => 'katalog.themes.classic-gold'
                    ],
                ]
            ],
            'luxury' => [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'features' => ['Masa aktif 12 bulan', 'Galeri maksimal 20 foto', 'Fitur premium terbuka', 'Dukungan prioritas'],
                'items' => []
            ],
            '3d_motion' => [
                'name' => '3D Motion',
                'slug' => '3d_motion',
                'features' => ['Animasi 3D interaktif', 'Masa aktif selamanya', 'Semua fitur premium', 'Desain eksklusif'],
                'items' => []
            ],
            'art' => [
                'name' => 'Art',
                'slug' => 'art',
                'features' => ['Desain artistik & unik', 'Masa aktif 12 bulan', 'Palet warna custom', 'Termasuk fitur lengkap'],
                'items' => []
            ],
            'tema_adat' => [
                'name' => 'Tema Adat',
                'slug' => 'tema_adat',
                'features' => ['Ornamen khas adat nusantara', 'Masa aktif 10 bulan', 'Backsound musik daerah', 'Termasuk fitur lengkap'],
                'items' => []
            ],
            'tanpa_foto' => [
                'name' => 'Tanpa Foto',
                'slug' => 'tanpa_foto',
                'features' => ['Fokus pada ilustrasi & tipografi', 'Proses pembuatan lebih cepat', 'Masa aktif 10 bulan', 'Termasuk fitur lengkap'],
                'items' => []
            ],
        ];
    }

    /**
     * Menampilkan halaman utama katalog dengan semua kategori dan item.
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $katalogData = $this->getKatalogData();
        return view('katalog.index', ['katalogData' => $katalogData]);
    }

    /**
     * !! METHOD BARU DITAMBAHKAN DI SINI !!
     * Menampilkan detail satu item katalog.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): View
    {
        $katalogData = $this->getKatalogData();
        
        // Menggabungkan semua item dari semua kategori menjadi satu collection
        $allItems = collect($katalogData)->pluck('items')->flatten(1);
        
        // Mencari item yang cocok berdasarkan ID
        $item = $allItems->firstWhere('id', $id);

        // Jika item tidak ditemukan, tampilkan halaman 404
        if (!$item) {
            abort(404, 'Item katalog tidak ditemukan.');
        }

        // Mengirim data item yang ditemukan ke view 'katalog.show'
        return view('katalog.show', ['item' => $item]);
    }


    /**
     * Menampilkan halaman demo untuk tema tertentu.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDemo(int $id): View
    {
        $katalog = $this->getKatalogData();
        
        $allItems = collect($katalog)->pluck('items')->flatten(1);
        $theme = $allItems->firstWhere('id', $id);

        if (!$theme || !isset($theme['template'])) {
            abort(404, 'Tema atau template untuk demo tidak ditemukan.');
        }

        $event = (object) [
            'groom_name' => 'Aditya', 'bride_name' => 'Lestari',
            'date' => now()->addDays(45)->toDateString(),
            'photo_url' => $theme['preview_img'],
            'music_url' => asset('music/musik1.mp3'),
            'groom_photo' => 'https://source.unsplash.com/random/400x400/?man,portrait',
            'bride_photo' => 'https://source.unsplash.com/random/400x401/?woman,portrait',
            'groom_parents' => 'Bpk. Soleh & Ibu. Aminah', 'bride_parents' => 'Bpk. Budi & Ibu. Wati',
            'groom_instagram' => 'aditya.insta', 'bride_instagram' => 'lestari.insta',
            'akad_time' => '09:00', 'resepsi_time' => '11:00 - 14:00',
            'akad_location' => 'Masjid Istiqlal, Jakarta', 'resepsi_location' => 'Balai Kartini, Jakarta',
            'akad_maps_url' => '#', 'resepsi_maps_url' => '#',
        ];

        $guest = (object) ['uuid' => 'demo-guest-uuid', 'name' => 'Bpk/Ibu Tamu Undangan'];
        
        $photos = collect([
            (object)['path' => 'https://source.unsplash.com/random/800x800/?wedding,couple'],
            (object)['path' => 'https://source.unsplash.com/random/800x801/?wedding,couple'],
        ]);

        $rsvps = new LengthAwarePaginator([], 0, 5);
        
        return view($theme['template'], compact('event', 'guest', 'photos', 'rsvps', 'theme'));
    }
}
