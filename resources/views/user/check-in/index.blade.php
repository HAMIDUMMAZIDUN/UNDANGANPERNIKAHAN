@extends('user.layouts.app')

@section('page_title', 'Scan QR Check-in')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-slate-800">Check-in Tamu</h1>
        <p class="text-slate-500 mt-1">Arahkan kamera ke QR Code pada undangan tamu.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        
        {{-- Tombol untuk Flip Kamera (Mirror) --}}
        <div class="text-center mb-4">
            {{-- PERBAIKAN: ID dan Teks Tombol Diubah --}}
            <button id="mirror-camera-button" class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-100 text-slate-700 py-2 px-4 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 12h10M7 16l-4-4m4 4l4-4m6-6v12m0-12H9m4 0l4 4m-4-4l-4 4" />
                </svg>
                Flip Tampilan Kamera
            </button>
        </div>

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

{{-- PERBAIKAN: Menambahkan CSS untuk flip/mirror DAN styling tombol library --}}
<style>
    #qr-reader.mirrored video {
        /* Menerapkan flip horizontal */
        transform: scaleX(-1);
        -webkit-transform: scaleX(-1); /* Untuk kompatibilitas Safari */
    }

    /* --- PERBAIKAN: Styling untuk Tombol Bawaan Library --- */
    #qr-reader__dashboard_section_csr {
        display: flex;
        flex-direction: column;
        gap: 0.75rem; /* 12px */
        align-items: center;
        margin-top: 1rem; /* 16px */
    }

    /* Menata Select (Dropdown) Kamera */
    #qr-reader__dashboard_section_csr select {
        width: 100%;
        padding: 0.5rem 1rem; /* 8px 16px */
        font-size: 0.875rem; /* 14px */
        line-height: 1.25rem; /* 20px */
        border: 1px solid #cbd5e1; /* slate-300 */
        border-radius: 0.5rem; /* rounded-lg */
        background-color: #ffffff; /* bg-white */
        color: #334155; /* slate-700 */
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    #qr-reader__dashboard_section_csr select:focus {
        outline: none;
        border-color: #f59e0b; /* amber-500 */
        box-shadow: 0 0 0 2px #fde68a; /* amber-200 */
    }

    /* Menata Tombol "Start Scanning" */
    #qr-reader__dashboard_section_csr button {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem; /* 8px 16px */
        font-size: 0.875rem; /* 14px */
        font-weight: 500; /* medium */
        border-radius: 0.5rem; /* rounded-lg */
        background-color: #f59e0b; /* bg-amber-500 */
        color: #1e293b; /* text-slate-900 */
        border: 1px solid transparent;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: background-color 0.2s;
        cursor: pointer;
    }
    #qr-reader__dashboard_section_csr button:hover {
        background-color: #d97706; /* bg-amber-600 */
    }

    /* Menata Link "Scan an Image File" */
    #qr-reader__dashboard_section_csr a {
        font-size: 0.875rem; /* 14px */
        font-weight: 500; /* medium */
        color: #f59e0b; /* text-amber-600 */
        text-decoration: underline;
        transition: color 0.2s;
    }
    #qr-reader__dashboard_section_csr a:hover {
        color: #d97706; /* text-amber-700 */
    }
</style>

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
        resultContainer.innerHTML = `<span class="text-blue-600">Memproses...</span>`;
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

    // Fungsi yang dijalankan jika scan gagal (opsional)
    function onScanFailure(error) {
        // Biarkan kosong atau tambahkan log jika perlu
        // console.warn(`Scan error: ${error}`);
    }

    // --- PERBAIKAN: LOGIKA UNTUK FLIP TAMPILAN KAMERA (MIRROR) ---
    document.getElementById('mirror-camera-button').addEventListener('click', () => {
        const readerElement = document.getElementById('qr-reader');
        // Menambah/menghapus kelas CSS 'mirrored'
        readerElement.classList.toggle('mirrored');
    });

    // --- PERBAIKAN: INISIALISASI SCANNER (Hanya sekali, tanpa logika switch) ---
    
    // Konfigurasi untuk scanner
    const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        facingMode: "environment" // Selalu mulai dengan kamera belakang
    };

    // Inisialisasi scanner
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        config, 
        false // false = verbose (tidak menampilkan log detail)
    );
    
    // Mulai proses scanning
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>
@endpush

