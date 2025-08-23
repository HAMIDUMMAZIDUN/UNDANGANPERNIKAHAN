<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Imports\GuestsImport;   
use Maatwebsite\Excel\Facades\Excel; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TamuController extends Controller
{
    public function index()
    {
        // 1. Fetch a single event. You might get this from a database,
        //    a session, or a URL parameter. Here's an example:
        $event = Event::first(); // This fetches the first event in your database

        // 2. Get the guests related to that event.
        if ($event) {
            $guests = $event->guests()->paginate(10);
        } else {
            // Handle the case where no event is found
            $guests = collect(); // Return an empty collection
        }

        // 3. Pass both variables to the view using compact()
        return view('tamu.index', compact('guests', 'event'));
    }

    // Example for a method that already has the event as a parameter
    public function create(Event $event)
    {
        // The $event is already available thanks to route model binding
        return view('tamu.create', compact('event'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->firstOrFail();

        Guest::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $request->name,
            'affiliation' => $request->affiliation,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('tamu.index')->with('success', 'Tamu baru berhasil ditambahkan!');
    }

    public function edit(Guest $guest): View
    {
        // Pastikan tamu yang diedit milik user yang sedang login
        if ($guest->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tamu.edit', compact('guest'));
    }

    public function update(Request $request, Guest $tamu): RedirectResponse
    {
        if ($tamu->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $tamu->update($request->all());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Guest $tamu): RedirectResponse
    {
        if ($tamu->user_id !== Auth::id()) {
            abort(403);
        }
        
        $tamu->delete();
        return redirect()->route('tamu.index')->with('success', 'Tamu berhasil dihapus.');
    }
     public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $user = Auth::user();
            $event = Event::where('user_id', $user->id)->firstOrFail();

            Excel::import(new GuestsImport($user->id, $event->id), $request->file('file'));
            
            return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diimpor!');

        } catch (\Exception $e) {
            // Tangani error jika event tidak ditemukan atau error lainnya
            return redirect()->route('tamu.index')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Menyediakan file template untuk diunduh.
     */
    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_import_tamu.xlsx');
        
        if (!file_exists($filePath)) {
            // Beri pesan error jika file template tidak ada
            abort(404, 'File template tidak ditemukan.');
        }

        return response()->download($filePath);
    }


public function printQr($uuid)
{
    $guest = Guest::where('uuid', $uuid)->firstOrFail();

    // Menggunakan QrCode untuk membuat string SVG
    $qrCodeSvg = QrCode::size(250)->generate(route('undangan.show', ['event' => $event->uuid, 'guest' => $guest->uuid]));

    // Buat view dan kirimkan data QR
    $data = [
        'guest' => $guest,
        'qrCodeSvg' => $qrCodeSvg
    ];

    $pdf = PDF::loadView('tamu.pdf_template', $data);

    return $pdf->download('qrcode-'.$guest->name.'.pdf');
}
}
