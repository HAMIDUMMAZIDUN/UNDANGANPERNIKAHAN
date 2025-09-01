<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * Menampilkan halaman katalog undangan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data ini nantinya bisa diambil dari database.
        // Untuk contoh, kita gunakan array statis.
        $katalog = [
            'motion_art' => [
                ['id' => 1, 'nama' => 'Motion Art Midnight 1', 'gambar' => 'https://via.placeholder.com/300x400.png/000000/FFFFFF?text=Midnight+1'],
                ['id' => 2, 'nama' => 'Motion Art Midnight 2', 'gambar' => 'https://via.placeholder.com/300x400.png/000000/FFFFFF?text=Midnight+2'],
                ['id' => 3, 'nama' => 'Motion Art Midnight 3', 'gambar' => 'https://via.placeholder.com/300x400.png/000000/FFFFFF?text=Midnight+3'],
                ['id' => 4, 'nama' => 'Motion Art Midnight 4', 'gambar' => 'https://via.placeholder.com/300x400.png/000000/FFFFFF?text=Midnight+4'],
                ['id' => 5, 'nama' => 'Motion Art Midnight 5', 'gambar' => 'https://via.placeholder.com/300x400.png/000000/FFFFFF?text=Midnight+5'],
            ],
            'garden' => [
                ['id' => 6, 'nama' => 'Garden 01', 'gambar' => 'https://via.placeholder.com/300x400.png/f2e8e3/5c4b3f?text=Garden+01'],
                ['id' => 7, 'nama' => 'Garden 02', 'gambar' => 'https://via.placeholder.com/300x400.png/f2e8e3/5c4b3f?text=Garden+02'],
                ['id' => 8, 'nama' => 'Garden 03', 'gambar' => 'https://via.placeholder.com/300x400.png/f2e8e3/5c4b3f?text=Garden+03'],
                ['id' => 9, 'nama' => 'Garden 04', 'gambar' => 'https://via.placeholder.com/300x400.png/f2e8e3/5c4b3f?text=Garden+04'],
                ['id' => 10, 'nama' => 'Garden 05', 'gambar' => 'https://via.placeholder.com/300x400.png/f2e8e3/5c4b3f?text=Garden+05'],
            ]
        ];

        // Kirim data katalog ke view 'katalog'
        return view('katalog.index', ['katalog' => $katalog]);
    }
}