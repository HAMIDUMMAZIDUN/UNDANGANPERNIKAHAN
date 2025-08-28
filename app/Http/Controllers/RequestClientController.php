<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use Illuminate\View\View;

class RequestClientController extends Controller
{
    /**
     * Menampilkan halaman daftar permintaan klien.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $query = ClientRequest::with('user');

        // Jika ada filter status, terapkan
        if ($status) {
            $query->where('status', $status);
        }

        // Ambil semua data (atau yang terfilter) dan kelompokkan
        $requests = $query->get()->groupBy('status');

        // Menyiapkan data untuk setiap kategori
        $all = ClientRequest::with('user')->get(); // Untuk tab "All"
        $pending = $requests->get('pending', collect());
        $inProgress = $requests->get('in_progress', collect());
        $complete = $requests->get('complete', collect());
        $approve = $requests->get('approve', collect());

        return view('admin.requestclient.index', compact('all', 'pending', 'inProgress', 'complete', 'approve', 'status'));
    }

    /**
     * Memperbarui status permintaan.
     */
    public function updateStatus(Request $request, ClientRequest $clientRequest)
    {
        $request->validate(['status' => 'required|in:pending,in_progress,complete,approve']);
        
        $clientRequest->update(['status' => $request->status]);

        return back()->with('success', 'Status permintaan berhasil diperbarui.');
    }
}
