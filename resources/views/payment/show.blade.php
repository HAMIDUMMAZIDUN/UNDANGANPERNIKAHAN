@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold">Detail Pembayaran</h2>
        <p class="text-gray-600">Selesaikan pembayaran untuk mengaktifkan layanan Anda.</p>
    </div>

    {{-- Notifikasi standar dihapus, akan digantikan oleh script SweetAlert di bawah --}}

    <!-- Detail Transaksi -->
    <div class="border rounded-lg p-4 mb-6 bg-gray-50">
        <div class="flex justify-between items-center mb-2 pb-2 border-b">
            <span class="text-gray-600">ID Tagihan:</span>
            <span class="font-mono text-sm">#INV-{{ str_pad($clientRequest->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600">Nama Pelanggan:</span>
            <span class="font-semibold">{{ $clientRequest->user->name ?? 'N/A' }}</span>
        </div>
        <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600">Tanggal:</span>
            <span class="font-semibold">{{ $clientRequest->created_at->format('d F Y') }}</span>
        </div>
         <div class="flex justify-between items-center">
            <span class="text-gray-600">Deskripsi:</span>
            <span class="font-semibold">{{ $clientRequest->template->name ?? 'Aktivasi Layanan' }}</span>
        </div>
    </div>


    <!-- Total Tagihan -->
    <div class="border-t border-b border-gray-200 py-4 mb-6">
        <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600">Total Tagihan:</span>
            <span class="font-semibold text-2xl text-blue-600">Rp {{ number_format($clientRequest->price ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Status:</span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full
                @if($clientRequest->status == 'waiting_for_payment') bg-yellow-100 text-yellow-800 @endif
                @if($clientRequest->status == 'waiting_for_approval') bg-blue-100 text-blue-800 @endif
                @if($clientRequest->status == 'approved') bg-green-100 text-green-800 @endif
            ">
                {{ str_replace('_', ' ', Str::title($clientRequest->status)) }}
            </span>
        </div>
    </div>

    <!-- Area QR Code dan Form Konfirmasi -->
    @if ($clientRequest->status == 'waiting_for_payment')
        <div class="text-center">
            <p class="mb-2 font-semibold">Silakan pindai kode QR di bawah ini:</p>
            <div>
                @php
                    // Membuat data dinamis untuk QR Code (Contoh: untuk QRIS)
                    $qrData = "INV" . $clientRequest->id . ".TOTAL." . $clientRequest->price;
                @endphp
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrData) }}" alt="QR Code Pembayaran" class="mx-auto border-4 border-white rounded-lg shadow-lg">
            </div>
            <p class="mt-4 text-sm text-gray-500">
                Setelah melakukan pembayaran, klik tombol di bawah ini untuk konfirmasi.
            </p>

            <form method="POST" action="{{ route('payment.confirm', $clientRequest) }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                    Saya Sudah Membayar
                </button>
            </form>
        </div>
    @elseif ($clientRequest->status == 'waiting_for_approval')
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="font-semibold text-blue-800">Menunggu Verifikasi Admin</p>
            <p class="text-sm text-blue-700">Terima kasih atas konfirmasi Anda. Tim kami akan segera memeriksa pembayaran dan mengaktifkan akun Anda.</p>
        </div>
    @elseif ($clientRequest->status == 'approved')
         <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="font-semibold text-green-800">Pembayaran Diterima!</p>
            <p class="text-sm text-green-700">Akun Anda telah aktif. Klik tombol di bawah untuk masuk dan mulai menggunakan layanan.</p>
            {{-- Mengarahkan ke halaman login dengan email yang sudah terisi untuk mempercepat proses --}}
            <a href="{{ route('login', ['email' => $clientRequest->user->email]) }}" class="mt-4 w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-800 disabled:opacity-25 transition">
                Masuk ke Dashboard
            </a>
        </div>
    @endif

    {{-- Menambahkan SweetAlert2 library dan script untuk menampilkan notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        </script>
    @endif
@endsection

