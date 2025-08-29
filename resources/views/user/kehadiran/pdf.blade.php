<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kehadiran Tamu</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Kehadiran Tamu</h1>
    <p><strong>Status:</strong> {{ ucfirst($status) }}</p>
    <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY, HH:mm') }}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tamu</th>
                <th>Kategori</th>
                <th>Waktu Check-in</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($guests as $guest)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $guest->name }}</td>
                    <td>{{ $guest->affiliation }}</td>
                    <td>
                        @if($guest->check_in_time)
                            {{ \Carbon\Carbon::parse($guest->check_in_time)->isoFormat('D MMMM YYYY, HH:mm') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
