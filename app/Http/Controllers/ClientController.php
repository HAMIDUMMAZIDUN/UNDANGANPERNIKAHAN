<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'user')->with('events');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhereHas('events', function($eventQuery) use ($searchTerm) {
                      $eventQuery->where('groom_name', 'like', $searchTerm)
                                 ->orWhere('bride_name', 'like', $searchTerm);
                  });
            });
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        return view('admin.client.index', compact('clients'));
    }

    public function updateStatus(Request $request, User $client): RedirectResponse
    {
        $request->validate(['status' => 'required|in:approve,pending']);

        if ($client->role !== 'user') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $client->update(['status' => $request->status]);

        return back()->with('success', 'Status klien berhasil diperbarui.');
    }

    public function create(): View
    {
        return view('admin.client.create');
    }

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
            'status' => 'pending', 
        ]);

        return redirect()->route('admin.client.index')->with('success', 'Klien baru berhasil ditambahkan.');
    }

    public function destroy(User $client): RedirectResponse
    {
        if ($client->role !== 'user') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }
        $client->events()->delete();
        $client->delete();

        return back()->with('success', 'Klien berhasil dihapus.');
    }
    
    public function show(User $client): View
    {
        if ($client->role !== 'user') {
            abort(404);
        }
        return view('admin.client.show', compact('client'));
    }

    public function edit(User $client): View
    {
        if ($client->role !== 'user') {
            abort(404);
        }
        return view('admin.client.edit', compact('client'));
    }

    public function update(Request $request, User $client): RedirectResponse
    {
        if ($client->role !== 'user') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'password' => 'nullable|string|min:8',
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $client->update($dataToUpdate);

        return redirect()->route('admin.client.index')->with('success', 'Data klien berhasil diperbarui.');
    }
}

