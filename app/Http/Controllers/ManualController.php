<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ManualController extends Controller
{
    public function index(Request $request): View
    {
        $guests = collect();
        return view('user.manual.index', compact('guests'));
    }

    public function search(Request $request): View
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();
        $guestsQuery = Guest::query();

        if ($event) {
            $guestsQuery->where('event_id', $event->id);
            if ($search = $request->query('search')) {
                $guestsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('affiliation', 'LIKE', "%{$search}%");
                });
            } else {
                $guestsQuery->whereRaw('1=0');
            }
        } else {
            $guestsQuery->whereRaw('1=0');
        }
        
        $guests = $guestsQuery->take(20)->get(); 
        return view('manual._guest-list', compact('guests'));
    }

    public function checkIn(Request $request, $guestId): JsonResponse
    {
        $guest = Guest::find($guestId);

        if (!$guest) {
            return response()->json(['message' => 'Tamu tidak ditemukan.'], 404);
        }

        $request->validate([
            'jumlah_tamu' => 'required|integer|min:1',
        ]);
        
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        if (!$event || $guest->event_id !== $event->id) {
            return response()->json(['message' => 'Akses tidak diizinkan.'], 403);
        }

        if ($guest->check_in_time !== null) {
            return response()->json(['message' => 'Tamu ini sudah check-in.'], 422);
        }
        
        $guest->update([
            'check_in_time' => now(),
            'number_of_guests' => $request->jumlah_tamu,
        ]);
        
        return response()->json([
            'message' => 'Tamu "' . $guest->name . '" berhasil check-in.'
        ]);
    }

    /**
     * Menyimpan data check-in tamu manual baru.
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

        $existingGuest = Guest::where('event_id', $event->id)
                              ->where('name', $validatedData['name'])
                              ->first();

        if ($existingGuest) {
            return back()->withInput()->with('error', 'Tamu dengan nama "' . $validatedData['name'] . '" sudah ada dalam daftar.');
        }

        Guest::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $validatedData['name'],
            // PERBAIKAN: Jika kosong, simpan sebagai string kosong ''
            'affiliation' => $validatedData['affiliation'] ?? '',
            'check_in_time' => now(), 
            'number_of_guests' => $validatedData['guest_count'],
        ]);

        return redirect()->route('manual.index')->with('success', 'Tamu "' . $validatedData['name'] . '" berhasil ditambahkan!');
    }
}
