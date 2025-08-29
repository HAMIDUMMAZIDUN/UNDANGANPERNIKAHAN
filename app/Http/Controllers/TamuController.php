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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class TamuController extends Controller
{
    use AuthorizesRequests;
    public function index(Event $event): View
    {
        $this->authorize('view', $event); 

        $guests = $event->guests()->latest()->paginate(10);
        return view('user.tamu.index', compact('guests', 'event'));
    }

    public function create(Event $event): View
    {
        $this->authorize('update', $event);
        return view('user.tamu.create', compact('event'));
    }

    public function store(Request $request, Event $event): RedirectResponse
{
    $this->authorize('update', $event);

    $request->validate([
        'name' => 'required|string|max:255',
        'affiliation' => 'nullable|string|max:255',
    ]);

    $event->guests()->create([
        'user_id' => Auth::id(),
        'name' => $request->name,
        'affiliation' => $request->affiliation ?? 'Teman',
    ]);

    return redirect()->route('events.tamu.index', $event)->with('success', 'Tamu baru berhasil ditambahkan!');
}

    public function update(Request $request, Event $event, Guest $guest): RedirectResponse
    {
        $this->authorize('update', $event);

        $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'nullable|string|max:255', 
        ]);

        $guestData = $request->only(['name']);
        $guestData['affiliation'] = $request->affiliation ?? 'Teman';
        
        $guest->update($guestData);

        return redirect()->route('events.tamu.index', $event)->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Event $event, Guest $guest): RedirectResponse
    {
        $this->authorize('update', $event);
        $guest->delete();
        return redirect()->route('events.tamu.index', $event)->with('success', 'Tamu berhasil dihapus.');
    }

    public function import(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new GuestsImport(Auth::id(), $event->id), $request->file('file'));
            return redirect()->route('events.tamu.index', $event)->with('success', 'Data tamu berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->route('events.tamu.index', $event)->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_import_tamu.xlsx');
        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }
        return response()->download($filePath);
    }
    
    public function printMultipleQr(Request $request, Event $event)
    {
        $this->authorize('view', $event);
        
        $guestUuids = $request->query('guests');
        if (empty($guestUuids)) {
            return redirect()->route('events.tamu.index', $event)->with('error', 'Tidak ada tamu yang dipilih.');
        }

        $guests = Guest::whereIn('uuid', $guestUuids)->get()->map(function ($guest) use ($event) {
            $guest->qrCodeSvg = QrCode::size(150)->generate(route('undangan.show', ['event' => $event->uuid, 'guest' => $guest->uuid]));
            return $guest;
        });
        
        return view('user.tamu.print_qr', compact('guests', 'event'));
    }   

public function downloadQr(Event $event, Guest $guest)
{
    $this->authorize('view', $event);

    $invitationUrl = route('undangan.show', ['event' => $event->uuid, 'guest' => $guest->uuid]);
    $qrCode = QrCode::format('png')
                    ->driver('gd')
                    ->size(300)
                    ->margin(2)
                    ->generate($invitationUrl);
    $fileName = 'qrcode-' . Str::slug($guest->name, '_') . '.png';
    return response($qrCode)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
}

}