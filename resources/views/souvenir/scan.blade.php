@extends('layouts.app')

@section('page_title', 'Scan Souvenir')

@section('content')
<div class="bg-slate-900 min-h-screen p-4 sm:p-6 lg:p-8 flex flex-col items-center justify-center text-white">
    <div class="w-full max-w-md text-center">
        <h1 class="text-3xl font-bold">Scan QR Code Souvenir</h1>
        <p class="text-slate-300 mt-2 mb-6">Arahkan kamera ke QR code atau unggah gambar.</p>

        {{-- Live Camera Scanner --}}
        <div id="qr-reader" class="w-full bg-slate-800 rounded-lg overflow-hidden border-2 border-slate-700 mb-4"></div>
        
        {{-- Tombol untuk Scan dari File Gambar --}}
        <div class="my-4">
            <label for="qr-input-file" class="cursor-pointer inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-slate-900 bg-amber-500 hover:bg-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
                Pilih Gambar
            </label>
            <input type="file" id="qr-input-file" accept="image/*" class="hidden">
        </div>
        
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
    const qrReaderElement = document.getElementById('qr-reader');
    const qrFileInput = document.getElementById('qr-input-file');
    let html5Qrcode;

    // Fungsi terpusat untuk memproses hasil scan (baik dari kamera atau file)
    function processScanResult(uuid) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang memvalidasi data tamu.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
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
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan. Periksa koneksi Anda.',
            });
        });
    }

    // Callback sukses untuk pemindai kamera
    function onScanSuccess(decodedText, decodedResult) {
        html5Qrcode.pause();
        const uuid = decodedText.split('/').pop();
        processScanResult(uuid);
        setTimeout(() => html5Qrcode.resume(), 3000); // Lanjutkan scan setelah 3 detik
    }
    
    function onScanFailure(error) {
      // Abaikan error pemindaian kamera
    }

    // Inisialisasi pemindai kamera
    html5Qrcode = new Html5Qrcode(qrReaderElement.id);
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    html5Qrcode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
        .catch(err => {
            console.log("Tidak dapat memulai pemindaian.", err);
            qrReaderElement.innerHTML = `<p class="p-4 text-slate-400">Kamera tidak dapat diakses. Coba unggah gambar.</p>`;
        });

    // Event listener untuk input file
    qrFileInput.addEventListener('change', e => {
        if (e.target.files.length === 0) {
            return;
        }
        const imageFile = e.target.files[0];
        
        html5Qrcode.scanFile(imageFile, true)
            .then(decodedText => {
                const uuid = decodedText.split('/').pop();
                processScanResult(uuid);
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memindai',
                    text: 'Tidak ada QR code yang ditemukan di gambar ini.',
                });
                console.error(`Error scanning file. Reason: ${err}`);
            });
    });
});
</script>
@endpush
