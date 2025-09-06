<?php

namespace App\Mail;

use App\Models\ClientRequest; // Import model Anda
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InvoiceCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientRequest; // Properti untuk menampung data

    /**
     * Create a new message instance.
     */
    public function __construct(ClientRequest $clientRequest)
    {
        // Terima data request saat email dibuat
        $this->clientRequest = $clientRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tagihan untuk Pesanan Anda Telah Dibuat',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // 1. Membuat URL pembayaran yang sudah ditandatangani (signed URL)
        $paymentUrl = URL::temporarySignedRoute(
            'payment.show',                  // Nama route yang kita tuju
            now()->addDays(7),               // URL ini akan valid selama 7 hari
            ['clientRequest' => $this->clientRequest->id] // Parameter yang dibutuhkan oleh route
        );

        // 2. Mengirimkan URL tersebut ke view bersama dengan data lainnya
        return new Content(
            view: 'emails.invoice-created',
            with: [
                'paymentUrl' => $paymentUrl, // Variabel ini sekarang bisa diakses di view
            ],
        );
    }
}
