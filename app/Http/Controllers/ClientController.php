<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Menampilkan halaman daftar klien.
     */
    public function index(Request $request): View
    {
        // Mengambil user dengan role 'user' dan relasi event-nya
        $query = User::where('role', 'user')->with('events');

        // Logika untuk filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                // 'has('events')' berarti user memiliki minimal 1 event
                $query->has('events');
            } elseif ($request->status == 'off') {
                // 'doesntHave('events')' berarti user tidak memiliki event sama sekali
                $query->doesntHave('events');
            }
        }

        // Logika untuk pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $clients = $query->latest()->paginate(10);

        return view('admin.client.index', compact('clients'));
    }

    /**
     * Menghapus data klien.
     */
    public function destroy(User $client): RedirectResponse
    {
        if ($client->role !== 'user') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $client->delete();

        return back()->with('success', 'Klien berhasil dihapus.');
    }
}
