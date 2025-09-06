<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tagihan Dibuat</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Tagihan Pesanan Anda</div>
        
        <p>Halo, <strong>{{ $clientRequest->user->name }}</strong>!</p>
        
        <p>Admin kami telah membuat tagihan untuk pesanan Anda "{{ $clientRequest->title }}". Berikut rinciannya:</p>
        
        <ul>
            <li><strong>Total Tagihan:</strong> Rp {{ number_format($clientRequest->price, 0, ',', '.') }}</li>
            <li><strong>Status:</strong> Menunggu Pembayaran</li>
        </ul>
        
        <p>Silakan selesaikan pembayaran Anda dengan mengunjungi dasbor akun Anda melalui tombol di bawah ini.</p>
        
        <p style="text-align: center;">
            <a href="{{ route('payment.show', $clientRequest->id) }}" class="button">Buka Halaman Pembayaran</a>
        </p>

        <p>Terima kasih!</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>