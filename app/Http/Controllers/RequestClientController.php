<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RequestClientController extends Controller
{
    /**
     * Menampilkan halaman daftar permintaan klien.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $query = ClientRequest::with('user')->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $allRequests = $query->get();
        
        // Mengelompokkan request berdasarkan status untuk view
        $groupedRequests = $allRequests->groupBy('status');

        return view('admin.requestclient.index', [
            'all' => $allRequests,
            'pending' => $groupedRequests->get('pending', collect()),
            'waiting_for_payment' => $groupedRequests->get('waiting_for_payment', collect()),
            'waiting_for_approval' => $groupedRequests->get('waiting_for_approval', collect()),
            'inProgress' => $groupedRequests->get('in_progress', collect()),
            'complete' => $groupedRequests->get('complete', collect()),
            'approve' => $groupedRequests->get('approve', collect()),
            'status' => $status,
        ]);
    }

    /**
     * Menetapkan harga, membuat tagihan, dan mengirim notifikasi email.
     */
    public function generatePayment(Request $request, ClientRequest $clientRequest)
    {
        $request->validate(['price' => 'required|numeric|min:1000']);

        // Pastikan hanya request yang 'pending' yang bisa diproses
        if ($clientRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $clientRequest->update([
            'price' => $request->price,
            'status' => 'waiting_for_payment',
            'payment_status' => 'unpaid',
        ]);
        
        // Kirim email notifikasi ke user
        try {
            Mail::to($clientRequest->user->email)->send(new InvoiceCreatedMail($clientRequest));
            return back()->with('success', 'Tagihan berhasil dibuat dan notifikasi email telah dikirim.');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email tagihan untuk request ID ' . $clientRequest->id . ': ' . $e->getMessage());
            return back()->with('error', 'Tagihan berhasil dibuat, TETAPI notifikasi email gagal dikirim. Cek log.');
        }
    }

    /**
     * Menyetujui permintaan yang pembayarannya sudah dikonfirmasi.
     */
    public function approveRequest(ClientRequest $clientRequest)
    {
        // Menambahkan validasi untuk memastikan hanya request yang menunggu persetujuan yang bisa di-approve
        if ($clientRequest->status !== 'waiting_for_approval') {
            return back()->with('error', 'Hanya request yang menunggu persetujuan yang bisa di-approve.');
        }

        $clientRequest->update(['status' => 'approve']);

        return back()->with('success', 'Permintaan berhasil disetujui.');
    }
}