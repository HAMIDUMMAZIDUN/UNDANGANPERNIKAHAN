<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CariTamuController extends Controller
{
    /**
     * Menampilkan halaman utama pencarian tamu.
     */
    public function index(Request $request): View
    {
        // Saat halaman pertama kali dimuat, daftar tamu masih kosong.
        $guests = collect(); 
        return view('user.cari-tamu.index', compact('guests'));
    }

    /**
     * Menangani pencarian tamu secara dinamis (dipanggil oleh JavaScript).
     */
    public function search(Request $request): View
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();
        $guestsQuery = Guest::query();

        if ($event) {
            $guestsQuery->where('event_id', $event->id);
            
            if ($search = $request->query('search')) {
                // Mencari berdasarkan nama ATAU kategori (affiliation)
                $guestsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('affiliation', 'LIKE', "%{$search}%");
                });
            } else {
                // Jika input pencarian kosong, jangan tampilkan apa-apa.
                $guestsQuery->whereRaw('1=0'); 
            }
        } else {
            // Jika tidak ada event, jangan tampilkan apa-apa.
            $guestsQuery->whereRaw('1=0');
        }
        
        // Ambil maksimal 20 hasil dan kirim ke view partial.
        $guests = $guestsQuery->take(20)->get(); 
        return view('user.cari-tamu._guest-list', compact('guests'));
    }

    /**
     * Memproses check-in tamu dari modal.
     */
    public function checkIn(Request $request, $guestId): JsonResponse
    {
        $guest = Guest::find($guestId);

        if (!$guest) {
            return response()->json(['message' => 'Tamu tidak ditemukan.'], 404);
        }

        // Validasi input dari modal
        $request->validate([
            'jumlah_tamu' => 'required|integer|min:1',
        ]);
        
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        // Pastikan tamu ini milik event user yang sedang login
        if (!$event || $guest->event_id !== $event->id) {
            return response()->json(['message' => 'Akses tidak diizinkan.'], 403);
        }

        // Cek apakah tamu sudah pernah check-in
        if ($guest->check_in_time !== null) {
            return response()->json(['message' => 'Tamu ini sudah check-in sebelumnya.'], 422);
        }
        
        // Update data tamu dengan waktu check-in dan jumlah yang hadir
        $guest->update([
            'check_in_time' => now(),
            'number_of_guests' => $request->jumlah_tamu,
        ]);
        
        return response()->json([
            'message' => 'Tamu "' . $guest->name . '" berhasil check-in.'
        ]);
    }

    /**
     * Menyimpan data tamu dari form check-in manual.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'nullable|string|max:255', 
            'guest_count' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        if (!$event) {
            return back()->with('error', 'Tidak ada event aktif untuk melakukan check-in.');
        }
        
        Guest::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $validatedData['name'],
            'affiliation' => $validatedData['affiliation'] ?? 'Lainnya',
            'number_of_guests' => $validatedData['guest_count'],
            'check_in_time' => now(), 
        ]);
        
        // Asumsi route 'kehadiran.index' adalah halaman daftar tamu yang sudah check-in
        return redirect()->route('kehadiran.index')->with('success', 'Tamu manual berhasil check-in!');
    }
}
