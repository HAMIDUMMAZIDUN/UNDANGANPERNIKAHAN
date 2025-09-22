<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Menampilkan halaman editor desain.
     */
    public function index()
    {
        return view('admin.design.index');
    }

    /**
     * Menyimpan struktur desain yang baru.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'design_structure' => 'required|json'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil disimpan!',
            'data' => json_decode($validated['design_structure']) 
        ]);
    }
    
    /**
     * Menampilkan daftar desain yang sudah disimpan.
     */
    public function showSavedDesigns()
    {
        return view('admin.design.saved');
    }
}
