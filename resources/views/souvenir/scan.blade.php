@extends('layouts.app')

@section('page_title', 'Scan Souvenir')

@section('content')
<div class="bg-slate-900 min-h-screen p-4 sm:p-6 lg:p-8 flex flex-col items-center justify-center text-white">
    <div class="w-full max-w-md text-center">
        <h1 class="text-3xl font-bold">Scan QR Code Souvenir</h1>
        <p class="text-slate-300 mt-2 mb-6">Arahkan kamera ke QR code pada kartu undangan tamu.</p>

        <div id="qr-reader" class="w-full bg-slate-800 rounded-lg overflow-hidden border-2 border-slate-700"></div>
        
        <div id="qr-reader-results" class="mt-4"></div>

        <a href="{{ route('souvenir.index') }}" class="mt-8 inline-block text-sm text-amber-400 hover:text-amber-300">
            &larr; Kembali ke Daftar Souvenir
        </a>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library untuk QR Scanner --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function onScanSuccess(decodedText, decodedResult) {
        // decodedText akan berisi URL, contoh: http://domain.com/checkin/uuid-tamu
        // Kita ambil bagian terakhir (UUID) dari URL tersebut
        const uuid = decodedText.split('/').pop();

        // Hentikan pemindaian agar tidak memindai berulang kali
        html5QrcodeScanner.clear();

        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang memvalidasi data tamu.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('souvenir.redeem') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ uuid: uuid })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `${data.guestName} telah mengambil souvenir.`,
                }).then(() => {
                    // Mulai ulang pemindai setelah notifikasi ditutup
                    location.reload(); 
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                }).then(() => {
                    location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan. Periksa koneksi Anda.',
            }).then(() => {
                location.reload();
            });
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { fps: 10, qrbox: { width: 250, height: 250 } },
        false
    );
    html5QrcodeScanner.render(onScanSuccess);
});
</script>
@endpush
