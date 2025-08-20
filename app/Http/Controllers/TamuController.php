<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;

class TamuController extends Controller
{
    
public function index()
{
    $guests = Guest::all();
    return view('tamu.index', compact('guests'));
}


public function destroy($id)
{
    $guest = Guest::findOrFail($id);
    $guest->delete();

    return redirect()->back()->with('status', 'Tamu berhasil dihapus');
}
}
