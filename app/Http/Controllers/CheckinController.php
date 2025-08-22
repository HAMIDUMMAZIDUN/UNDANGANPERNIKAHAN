<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function handleCheckin(Guest $guest) // The guest model is automatically injected here
    {
        // Now you can use the $guest variable here
        // For example, to mark the guest as checked in
        $guest->update(['checked_in' => true]);

        return view('checkin.success', compact('guest'));
    }
}