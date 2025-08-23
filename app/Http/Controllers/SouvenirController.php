<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Guest;
use App\Exports\SouvenirsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class SouvenirController extends Controller
{
    private function getFilteredGuests(Request $request)
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();
        $query = Guest::query();

        if ($event) {
            $query = Guest::where('event_id', $event->id)
                          ->whereNotNull('souvenir_taken_at');
            if ($search = $request->query('search')) {
                $query->where('name', 'LIKE', "%{$search}%");
            }
        } else {
            $query->whereRaw('1=0');
        }
        return $query;
    }

    public function index(Request $request): View
    {
        $query = $this->getFilteredGuests($request);
        $totalSouvenirsTaken = (clone $query)->count();
        $guests = $query->latest('souvenir_taken_at')->paginate(15)->withQueryString();
        return view('souvenir.index', compact('guests', 'totalSouvenirsTaken'));
    }

    public function exportExcel(Request $request)
    {
        $guests = $this->getFilteredGuests($request)->get();
        return Excel::download(new SouvenirsExport($guests), 'laporan-pengambilan-souvenir.xlsx');
    }

    public function scan(): View
    {
        return view('souvenir.scan');
    }

    public function redeem(Request $request): JsonResponse
    {
        $request->validate(['uuid' => 'required|string|exists:guests,uuid']);

        $guest = Guest::where('uuid', $request->uuid)->first();

        // Pastikan tamu milik user yang sedang login
        if ($guest->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak terdaftar di event ini.']);
        }

        if ($guest->souvenir_taken_at) {
            $time = \Carbon\Carbon::parse($guest->souvenir_taken_at)->isoFormat('D MMM YYYY, HH:mm');
            return response()->json(['success' => false, 'message' => "Souvenir sudah diambil pada {$time}."]);
        }

        $guest->souvenir_taken_at = now();
        $guest->save();

        return response()->json(['success' => true, 'message' => 'Souvenir berhasil ditukarkan.', 'guestName' => $guest->name]);
    }
}
