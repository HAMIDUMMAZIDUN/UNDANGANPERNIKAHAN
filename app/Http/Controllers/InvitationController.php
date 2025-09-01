<?php

namespace App\Http\Controllers;

use App\Models\Invitation; 
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    // Method untuk menampilkan halaman editor
    public function edit(Invitation $invitation)
    {
        // Tambahkan otorisasi jika perlu
        // abort_if(auth()->id() !== $invitation->user_id, 403);
        return view('invitations.editor', compact('invitation'));
    }

    // Method untuk menyimpan konten dari editor
    public function save(Request $request, Invitation $invitation)
    {
        // Tambahkan otorisasi di sini juga
        $validated = $request->validate([
            'content' => 'required|array'
        ]);

        $invitation->content = $validated['content'];
        $invitation->save();

        return response()->json(['message' => 'Konten berhasil diperbarui.']);
    }

    // Method untuk menampilkan halaman publik
    public function show(Invitation $invitation)
    {
        return view('invitations.show', compact('invitation'));
    }
}