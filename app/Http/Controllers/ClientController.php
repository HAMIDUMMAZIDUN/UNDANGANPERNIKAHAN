<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash; 

class ClientController extends Controller
{
    /**
     * Menampilkan halaman daftar klien.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'user')->with('events');

        // Filter berdasarkan status (active/off)
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->has('events');
            } elseif ($request->status == 'off') {
                $query->doesntHave('events');
            }
        }

        // ======================================================
        // TAMBAHKAN KODE INI UNTUK FILTER TANGGAL
        // ======================================================
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        // ======================================================
        // AKHIR DARI KODE TAMBAHAN
        // ======================================================

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
     * Menampilkan form untuk membuat klien baru.
     */
    public function create(): View
    {
        return view('admin.client.create');
    }

    /**
     * Menyimpan klien baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('admin.client.index')->with('success', 'Klien baru berhasil ditambahkan.');
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