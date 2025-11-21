<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran Tamu</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .info-box { margin-bottom: 20px; border: 1px solid #000; padding: 10px; }
        .info-row { margin-bottom: 5px; }
        .label { font-weight: bold; display: inline-block; width: 150px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th { background-color: #000; color: #fff; padding: 8px; text-transform: uppercase; font-size: 10px; }
        td { padding: 6px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .summary-header th { background-color: #0000FF; color: white; }
        .summary-total th { background-color: #000; color: white; }
    </style>
</head>
<body>

    <div class="header">
        <h1>DIGITAL GUESTBOOK RECAP</h1>
        <p>Generated on: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="label">NAMA PENGANTIN:</span> {{ Auth::user()->name }}
        </div>
        <div class="info-row">
            <span class="label">TANGGAL/LOKASI:</span> {{ Auth::user()->event_date }} / {{ Auth::user()->event_location }}
        </div>
    </div>

    <!-- TABEL REKAPITULASI -->
    <table class="summary">
        <thead>
            <tr class="summary-header">
                <th style="width: 70%;">KETERANGAN</th>
                <th style="width: 30%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Jumlah Undangan Yang Datang</td>
                <td class="text-center"><strong>{{ $total_invites }}</strong></td>
            </tr>
            <tr>
                <td>Jumlah Orang Yang Datang (Pax)</td>
                <td class="text-center"><strong>{{ $total_pax }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- TABEL DETAIL TAMU -->
    <h3>DETAIL TAMU HADIR</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 55%;">Nama Lengkap</th>
                <th style="width: 15%;">Pax</th>
                <th style="width: 25%;">Waktu Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $index => $guest)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="text-transform: uppercase;">{{ $guest->name }}</td>
                <td class="text-center">{{ $guest->pax }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($guest->check_in_at)->format('d/m/Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada data tamu hadir.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>