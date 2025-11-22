<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Exports\GuestsExport;
use App\Imports\GuestsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::query();

        if ($request->has('search') && $request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $guests = $query->orderBy('name', 'asc')->get();
        
        return view('user.list_tamu.index', compact('guests'));
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->get('query');

        if (!$query) {
            return response()->json([]);
        }

        $guests = Guest::where('name', 'like', '%' . $query . '%')
                        ->limit(10)
                        ->get(['id', 'name', 'pax', 'check_in_at']);

        return response()->json($guests);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required',
        ]);

        $ids = explode(',', $request->ids);
        Guest::whereIn('id', $ids)->delete();

        return back()->with('success', 'Data terpilih berhasil dihapus!');
    }

    public function server1(Request $request)
    {
        if ($request->has('search') && $request->filled('search')) {
            $keyword = $request->search;
            
            $guest = Guest::where('name', $keyword)->first();
            
            if (!$guest) {
                $guest = Guest::where('name', 'like', '%' . $keyword . '%')->first();
            }

            if ($guest) {
                if ($guest->check_in_at) {
                    $jamCheckIn = Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('H:i');
                    return redirect()->route('server1')
                        ->with('warning', "Tamu '{$guest->name}' SUDAH Check-in sebelumnya pada jam {$jamCheckIn}.")
                        ->with('highlight_id', $guest->id);
                }

                $guest->update([
                    'server_number' => 1,
                    'check_in_at' => Carbon::now(),
                    'is_physical_invited' => true 
                ]);

                return redirect()->route('server1')
                    ->with('success', "BERHASIL: {$guest->name} Masuk!")
                    ->with('highlight_id', $guest->id);
            } else {
                return redirect()->route('server1')
                    ->with('error', "GAGAL: Tamu '{$keyword}' TIDAK DITEMUKAN.")
                    ->with('not_found_name', $keyword); 
            }
        }

        $guests = Guest::where('server_number', 1)->orderBy('check_in_at', 'desc')->limit(10)->get(); 
        return view('user.server_1.index', compact('guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
        ]);

        $guest = Guest::create([
            'name' => $request->name,
            'pax' => $request->pax,
            'is_physical_invited' => true,
            'server_number' => 1,
            'check_in_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('server1')
            ->with('success', "Tamu Manual '{$guest->name}' Berhasil Ditambahkan & Masuk!")
            ->with('highlight_id', $guest->id);
    }

    public function server2()
    {
        $guests = Guest::where('server_number', 2)->orderBy('check_in_at', 'desc')->get();
        return view('user.server_2.index', compact('guests'));
    }

    public function attendance()
    {
        $guests = Guest::whereNotNull('check_in_at')->orderBy('check_in_at', 'desc')->get(); 
        $total_invites = $guests->count();
        $total_pax     = $guests->sum('pax');
        
        return view('user.list_tamu_hadir.index', compact('guests', 'total_invites', 'total_pax'));
    }

    public function exportPdf()
    {
        $guests = Guest::whereNotNull('check_in_at')->orderBy('check_in_at', 'desc')->get();
        $total_invites = $guests->count();
        $total_pax     = $guests->sum('pax');

        $pdf = Pdf::loadView('user.list_tamu_hadir.pdf', compact('guests', 'total_invites', 'total_pax'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('rekap_kehadiran_tamu.pdf');
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate(['name' => 'required|string|max:255', 'pax' => 'required|integer|min:1']);
        $guest->update([
            'name' => $request->name,
            'pax' => $request->pax,
            'is_online_invited' => $request->has('is_online_invited'),
            'is_physical_invited' => $request->has('is_physical_invited'),
        ]);
        return back()->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return back()->with('success', 'Data tamu berhasil dihapus!');
    }

    public function export() 
    {
        return Excel::download(new GuestsExport, 'daftar_tamu.xlsx');
    }

    public function import(Request $request) 
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new GuestsImport, $request->file('file'));
        return back()->with('success', 'Data tamu berhasil diimpor!');
    }

    public function create() {}
    public function show(Guest $guest) {}
    public function edit(Guest $guest) {}
}