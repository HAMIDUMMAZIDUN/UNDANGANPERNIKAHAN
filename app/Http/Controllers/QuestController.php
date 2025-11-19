<?php
namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorHTML; // Library Barcode

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::where('user_id', auth()->id())->get();
        return view('guests.index', compact('guests'));
    }

    public function store(Request $request)
    {
        // Logika simpan manual
        Guest::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'barcode_code' => Str::upper(Str::random(10)), // Generate kode unik
        ]);
        
        // Catat log aktivitas (bisa dibuat function helper terpisah agar clean)
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Menambahkan tamu: ' . $request->name
        ]);

        return back();
    }

    // Fungsi Generate Barcode Tampilan
    public function barcode($id)
    {
        $guest = Guest::findOrFail($id);
        $generator = new BarcodeGeneratorHTML();
        // Tipe C128 umum untuk scanner
        $barcode = $generator->getBarcode($guest->barcode_code, $generator::TYPE_CODE_128);
        
        return view('guests.barcode', compact('guest', 'barcode'));
    }
    
    // Logic Import/Export menggunakan Maatwebsite Excel (perlu setup class Import/Export terpisah)
    // ...
}