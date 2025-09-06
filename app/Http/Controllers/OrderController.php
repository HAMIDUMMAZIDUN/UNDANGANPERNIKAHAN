<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientRequest; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function getKatalogData(): array
    {
        return [
            'classic_gold' => [
                ['id' => 1, 'nama' => 'Classic Gold', 'preview_img' => asset('images/previews/classic-gold-1.png')],
            ],
            'rustic_elegance' => [
                ['id' => 6, 'nama' => 'Rustic Bohemian', 'preview_img' => asset('images/previews/rustic-bohemian.png')],
                ['id' => 7, 'nama' => 'Greenery Charm', 'preview_img' => asset('images/previews/greenery-charm.png')],
            ],
            'modern_minimalist' => [
                ['id' => 11, 'nama' => 'Modern Monochrome', 'preview_img' => asset('images/previews/modern-monochrome.png')],
                ['id' => 12, 'nama' => 'Simple & Clean', 'preview_img' => asset('images/previews/simple-clean.png')],
            ]
        ];
    }

    public function startOrder(int $template_id)
    {
        $katalog = $this->getKatalogData();
        $theme = collect($katalog)->flatten(1)->firstWhere('id', $template_id);

        if (!$theme) {
            abort(404, 'Template tidak ditemukan.');
        }

        return view('order.create', ['theme' => $theme]);
    }

    /**
     * Memproses pendaftaran, membuat order, dan login.
     */
public function processOrder(Request $request)
{
    // 1. Validasi input
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'phone' => ['required', 'string', 'min:10', 'max:15'], // <-- TAMBAHKAN VALIDASI INI
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'template_id' => ['required', 'integer'],
        'template_name' => ['required', 'string'],
    ]);

    // 2. Buat user baru
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'], // <-- SIMPAN NOMOR HP
        'password' => Hash::make($validated['password']),
        'role' => 'user'
    ]);
    
    // ... sisa kode tetap sama ...
    $clientRequest = ClientRequest::create([
        'user_id' => $user->id,
        'template_id' => $validated['template_id'],
        'title' => 'Order: ' . $validated['template_name'],
        'status' => 'pending',
    ]);
    
    return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang diproses oleh admin. Silakan tunggu konfirmasi untuk login.');
}
}