<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
// app/Http/Controllers/DashboardController.php

public function index(Request $request): View|RedirectResponse
{
    try {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $event = Event::where('user_id', $user->id)->first();
        if (!$event) {
            return redirect()->route('events.create')
                ->with('info', 'Selamat datang! Silakan buat event pertama Anda untuk memulai.');
        }

        $baseGuestsQuery = Guest::where('event_id', $event->id);
        
        $totalUndangan = (clone $baseGuestsQuery)->count();
        $hadirQuery = (clone $baseGuestsQuery)->whereNotNull('check_in_time');
        $jumlahHadir = (clone $hadirQuery)->count();
        $totalOrangHadir = (clone $hadirQuery)->sum('number_of_guests');
        $totalPotensiOrang = (clone $baseGuestsQuery)->sum('number_of_guests');

        $allGuestsQuery = Guest::where('event_id', $event->id);
        
        if ($request->filled('search')) {
            $searchQuery = $request->input('search');
            $allGuestsQuery->where('name', 'like', "%{$searchQuery}%");
        }

        $guests = $allGuestsQuery->orderBy('check_in_time', 'desc')
                                 ->paginate(10)
                                 ->withQueryString();

        return view('dashboard.index', [
            'event' => $event,
            'guests' => $guests,
            'totalUndangan' => $totalUndangan,
            'jumlahHadir' => $jumlahHadir,
            'totalOrangHadir' => $totalOrangHadir,
            'totalPotensiOrang' => $totalPotensiOrang,
        ]);

    } catch (\Exception $e) {
        Log::error('Dashboard error: ' . $e->getMessage());
        return redirect('/')->with('error', 'Terjadi kesalahan pada sistem dashboard.');
    }
}

}

