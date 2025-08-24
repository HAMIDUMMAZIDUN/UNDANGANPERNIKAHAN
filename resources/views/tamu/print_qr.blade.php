<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Tamu - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-4 sm:p-8">
        <div class="flex justify-between items-center mb-6 no-print">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Cetak QR Code Tamu</h1>
                <p class="text-gray-500">{{ $event->name }}</p>
            </div>
            <button onclick="window.print()" class="bg-amber-500 text-slate-900 font-semibold py-2 px-4 rounded-lg shadow hover:bg-amber-600 transition">
                Cetak Halaman Ini
            </button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($guests as $guest)
                <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center text-center border border-gray-200">
                    <div class="mb-2">
                        {!! $guest->qrCodeSvg !!}
                    </div>
                    <p class="font-bold text-sm text-gray-900 break-words">{{ $guest->name }}</p>
                    <p class="text-xs text-gray-500 break-words">{{ $guest->affiliation }}</p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>