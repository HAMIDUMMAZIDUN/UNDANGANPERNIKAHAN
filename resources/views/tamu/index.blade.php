@extends('layouts.app')

@section('page_title', 'Tamu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        {{-- Ganti dengan data event dinamis jika memungkinkan --}}
        <h1 class="text-3xl font-bold text-slate-800">{{ $event->name ?? 'Ages & April' }}</h1>
        <p class="text-slate-500 mt-1">{{ isset($event->date) ? \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') : 'Minggu, 21 September 2025' }}</p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-5">
        <h2 class="text-xl font-semibold text-slate-700">Data Tamu ({{ $guests->total() }} Undangan)</h2>
        <div class="flex items-center gap-2">
            <button id="printQrButton" disabled class="inline-flex items-center justify-center bg-slate-600 text-white px-4 py-2 rounded-lg border border-slate-600 hover:bg-slate-700 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print QR Terpilih
            </button>
            <button onclick="openImportModal()" class="inline-flex items-center justify-center bg-white text-slate-700 px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import Tamu
            </button>
            <a href="{{ route('tamu.create') }}" class="inline-flex items-center justify-center bg-amber-500 text-slate-900 px-4 py-2 rounded-lg shadow-md hover:bg-amber-600 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                + Tambah Tamu
            </a>
        </div>
    </div>

    <div class="mb-5">
        <input type="text" placeholder="Cari nama tamu atau kategori..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition">
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="p-4">
                            <input type="checkbox" id="selectAllCheckbox" class="h-4 w-4 text-amber-600 border-slate-300 rounded focus:ring-amber-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($guests as $guest)
                    <tr id="guest-row-{{$guest->id}}" data-guest-name="{{ $guest->name }}" data-guest-qr-data="{{ route('checkin.guest', ['guest' => $guest->uuid]) }}">
                        <td class="p-4">
                            <input type="checkbox" name="guest_ids[]" value="{{ $guest->id }}" class="guest-checkbox h-4 w-4 text-amber-600 border-slate-300 rounded focus:ring-amber-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $guest->affiliation }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            {{-- ... Tombol aksi Anda ... --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-16 text-slate-500">
                            <p class="font-semibold">Belum ada data tamu.</p>
                            <p class="text-sm mt-1">Silakan klik tombol "+ Tambah Tamu" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($guests->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $guests->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ... Modal import Anda ... --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const guestCheckboxes = document.querySelectorAll('.guest-checkbox');
        const printButton = document.getElementById('printQrButton');

        function updatePrintButtonState() {
            const anyChecked = Array.from(guestCheckboxes).some(cb => cb.checked);
            printButton.disabled = !anyChecked;
        }

        selectAllCheckbox.addEventListener('change', function () {
            guestCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updatePrintButtonState();
        });

        guestCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(guestCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
                updatePrintButtonState();
            });
        });

        printButton.addEventListener('click', function () {
            const selectedGuests = [];
            guestCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = document.getElementById(`guest-row-${checkbox.value}`);
                    selectedGuests.push({
                        id: checkbox.value,
                        name: row.dataset.guestName,
                        qrData: row.dataset.guestQrData,
                    });
                }
            });

            if (selectedGuests.length > 0) {
                generatePrintLayout(selectedGuests);
            }
        });

        function generatePrintLayout(guests) {
            const eventName = "{{ $event->name ?? 'Ages & April' }}";
            const printWindow = window.open('', '_blank');
            
            // Konten HTML untuk dicetak
            let content = `
                <html>
                <head>
                    <title>Cetak Kartu QR Tamu</title>
                    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"><\/script>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
                        body { font-family: 'Montserrat', sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
                        .page { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; page-break-after: always; }
                        .card { background: white; border: 1px solid #ccc; text-align: center; padding: 15px; display: flex; flex-direction: column; justify-content: space-between; height: 350px; box-sizing: border-box; }
                        .card h1 { font-size: 14px; margin: 5px 0; font-weight: normal; }
                        .card h2 { font-size: 20px; margin: 10px 0; }
                        .card .qr-code { margin: 15px auto; width: 150px; height: 150px; }
                        .card .guest-name { font-size: 16px; font-weight: bold; margin-top: 10px; }
                        .card p { font-size: 10px; margin: 0; color: #555; }
                        .card .footer { background-color: #4a4a4a; color: white; padding: 8px; margin: 15px -15px -15px -15px; font-size: 10px; font-weight: bold; }
                        @media print {
                            body { background-color: white; padding: 0; }
                            .page { gap: 0; }
                            .card { border: 1px solid #eee; }
                        }
                    </style>
                </head>
                <body>
                    <div class="page">
            `;
            
            guests.forEach(guest => {
                content += `
                    <div class="card">
                        <div>
                            <h1>The Wedding of</h1>
                            <h2>${eventName}</h2>
                            <div class="qr-code" id="qr-${guest.id}"></div>
                            <p>Kepada Yang Terhormat Bapak/Ibu/Saudara/i</p>
                            <p class="guest-name">${guest.name}</p>
                        </div>
                        <div class="footer">
                            HARAP BAWA KARTU INI UNTUK CHECK-IN DI LOKASI ACARA
                        </div>
                    </div>
                `;
            });
            
            content += `
                    </div>
                    <script>
                        const guests = ${JSON.stringify(guests)};
                        guests.forEach(guest => {
                            new QRCode(document.getElementById('qr-' + guest.id), {
                                text: guest.qrData,
                                width: 150,
                                height: 150,
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        });
                        setTimeout(function() {
                            window.print();
                            window.close();
                        }, 500);
                    <\/script>
                </body>
                </html>
            `;

            printWindow.document.write(content);
            printWindow.document.close();
        }
    });
</script>
@endpush