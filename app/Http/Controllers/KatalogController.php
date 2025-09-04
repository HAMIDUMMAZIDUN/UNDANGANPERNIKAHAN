<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class KatalogController extends Controller
{
private function getKatalogData(): array
    {
        return [
            'classic_gold' => [
                ['id' => 1, 'nama' => 'Classic Gold', 'gambar' => asset('images/previews/classic-gold-1.png'), 'preview_img' => asset('images/previews/classic-gold-1.png'), 'musik' => 'music/musik1.mp3', 'template' => 'katalog.themes.classic-gold'],
            ],
            'rustic_elegance' => [
                ['id' => 6, 'nama' => 'Rustic Bohemian', 'gambar' => asset('images/previews/rustic-bohemian.png'), 'preview_img' => asset('images/previews/rustic-bohemian.png'), 'musik' => 'music/musik2.mp3', 'template' => 'katalog.themes.rustic-bohemian'],
                ['id' => 7, 'nama' => 'Greenery Charm', 'gambar' => asset('images/previews/greenery-charm.png'), 'preview_img' => asset('images/previews/greenery-charm.png'), 'musik' => 'music/musik2.mp3', 'template' => 'katalog.themes.greenery-charm'],
            ],
            'modern_minimalist' => [
                ['id' => 11, 'nama' => 'Modern Monochrome', 'gambar' => asset('images/previews/modern-monochrome.png'), 'preview_img' => asset('images/previews/modern-monochrome.png'), 'musik' => 'music/musik3.mp3', 'template' => 'katalog.themes.modern-monochrome'],
                ['id' => 12, 'nama' => 'Simple & Clean', 'gambar' => asset('images/previews/simple-clean.png'), 'preview_img' => asset('images/previews/simple-clean.png'), 'musik' => 'music/musik3.mp3', 'template' => 'katalog.themes.simple-clean'],
            ]
        ];
    }

    public function index(): View
    {
        $katalog = $this->getKatalogData();
        return view('katalog.index', ['katalog' => $katalog]);
    }

    public function showDemo(int $id): View
    {
        $katalog = $this->getKatalogData();
        $theme = collect($katalog)->flatten(1)->firstWhere('id', $id);

        if (!$theme) {
            abort(404);
        }

        $event = (object) [
            'groom_name' => 'Aditya', 'bride_name' => 'Lestari',
            'date' => now()->addDays(45)->toDateString(),
            'photo_url' => $theme['gambar'],
            'music_url' => asset($theme['musik'] ?? 'music/musik1.mp3'),
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
            (object)['path' => 'https://source.unsplash.com/random/800x802/?wedding,decoration'],
            (object)['path' => 'https://source.unsplash.com/random/800x803/?wedding,ring'],
        ]);

        $rsvps = new LengthAwarePaginator([], 0, 5);

        return view($theme['template'], compact('event', 'guest', 'photos', 'rsvps', 'theme'));
    }
}