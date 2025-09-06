<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran untuk user.
     * Rute ini sekarang harus dilindungi oleh middleware 'signed'.
     */
    public function show(ClientRequest $clientRequest)
    {
        return view('payment.show', compact('clientRequest'));
    }

    /**
     * User mengonfirmasi bahwa mereka sudah membayar.
     */
    public function confirm(Request $request, ClientRequest $clientRequest)
    {
        $clientRequest->update([
            'status' => 'waiting_for_approval'
        ]);
        $signedUrl = URL::temporarySignedRoute(
            'payment.show',
            now()->addHours(24), // Buat link valid untuk 24 jam lagi
            ['clientRequest' => $clientRequest->id]
        );

        return redirect($signedUrl)
                    ->with('success', 'Konfirmasi pembayaran Anda telah terkirim. Admin akan segera melakukan verifikasi.');
    }
}