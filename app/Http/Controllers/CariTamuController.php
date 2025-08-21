<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CariTamuController extends Controller
{
    /**
     * Menampilkan halaman utama yang berisi tombol untuk membuka modal.
     */
    public function index(): View
    {
        return view('cari-tamu.index');
    }

    /**
     * Menangani request pencarian tamu via API.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q');
        $allGuests = [
            ['id' => 1, 'name' => 'Asep Suryatna, S.Kep', 'address' => 'Primaya Rajawali Hospital Bandung'],
            ['id' => 2, 'name' => 'H. Asep Wargana dan Hj. Euis Siti', 'address' => 'Alumni KBIH Al Ihsan'],
            ['id' => 3, 'name' => 'Asep Anwar', 'address' => 'Keluarga Putu Abah Suhatmi'],
            ['id' => 4, 'name' => 'Asep Mulyana', 'address' => 'Keluarga Putu Abah Suhatmi'],
            ['id' => 5, 'name' => 'Asep Agus', 'address' => 'Keluarga Putu Abah Suhatmi'],
            ['id' => 6, 'name' => 'Asep Erva, AMK', 'address' => 'IBS RSUD Welas Asih Bandung'],
            ['id' => 7, 'name' => 'Budi Santoso', 'address' => 'Rekan Kerja Kantor Pusat'],
            ['id' => 8, 'name' => 'Citra Lestari', 'address' => 'Sahabat SMA'],
        ];

        if (empty($query)) {
            return response()->json([]);
        }

        // Filter data dummy berdasarkan query
        $filteredGuests = array_filter($allGuests, function ($guest) use ($query) {
            return stripos($guest['name'], $query) !== false;
        });

        // Mengembalikan hasil dalam format JSON
        return response()->json(array_values($filteredGuests));
    }
}