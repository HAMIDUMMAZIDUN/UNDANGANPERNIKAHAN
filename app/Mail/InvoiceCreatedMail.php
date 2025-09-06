<?php

namespace App\Mail;

use App\Models\ClientRequest; // Import model Anda
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
        return new Content(
            // Arahkan ke file view yang akan kita buat selanjutnya
            view: 'emails.invoice-created',
        );
    }
}