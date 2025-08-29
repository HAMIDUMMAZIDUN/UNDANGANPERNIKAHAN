@extends('user.layouts.app')

@section('page_title', 'Scan QR Check-in')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-slate-800">Check-in Tamu</h1>
        <p class="text-slate-500 mt-1">Arahkan kamera ke QR Code pada undangan tamu.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        {{-- Element ini akan menjadi container untuk kamera --}}
        <div id="qr-reader" style="width: 100%;"></div>
        
        {{-- Element ini untuk menampilkan pesan hasil scan --}}
        <div id="qr-scan-result" class="mt-4 text-center font-medium text-lg">
            Menunggu hasil scan...
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memuat library html5-qrcode dari CDN --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const resultContainer = document.getElementById('qr-scan-result');
    let lastScanTime = 0;
    const scanCooldown = 3000; // Cooldown 3 detik untuk mencegah scan berulang

    // Fungsi yang akan dijalankan ketika QR code berhasil di-scan
    function onScanSuccess(decodedText, decodedResult) {
        const now = Date.now();
        if (now - lastScanTime < scanCooldown) {
            // Jika scan terjadi terlalu cepat, abaikan
            return;
        }
        lastScanTime = now; // Update waktu scan terakhir

        // Menampilkan status sedang memproses...
        resultContainer.innerHTML = `<span class="text-blue-600">Memproses: ${decodedText}...</span>`;
        resultContainer.style.color = '#3b82f6'; // Blue

        // Mengambil CSRF token dari meta tag di layout
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Mengirim data hasil scan ke backend menggunakan Fetch API (AJAX)
        fetch("{{ route('check-in.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            // Menampilkan pesan dari server
            resultContainer.textContent = data.message;
            if (data.success) {
                resultContainer.style.color = '#16a34a'; // Green
            } else {
                resultContainer.style.color = '#dc2626'; // Red
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultContainer.textContent = 'Terjadi kesalahan saat mengirim data.';
            resultContainer.style.color = '#dc2626'; // Red
        });
    }

    // Inisialisasi scanner
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: { width: 250, height: 250 } }
    );
    
    // Mulai proses scanning
    html5QrcodeScanner.render(onScanSuccess);
});
</script>
@endpush