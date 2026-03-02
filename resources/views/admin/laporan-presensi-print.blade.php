<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan Presensi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 960px; margin: 0 auto; }
        h1 { font-size: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        th { background: #f5f5f5; text-align: left; }
        .stat { margin: 12px 0; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Laporan Presensi</h1>
    <p>Periode: {{ $range['start'] }} s/d {{ $range['end'] }}</p>
    <div class="stat">
        <span>Total: {{ $stat['total'] }}</span> |
        <span>Hadir: {{ $stat['hadir'] }}</span> |
        <span>Terlambat: {{ $stat['terlambat'] }}</span> |
        <span>Izin: {{ $stat['izin'] }}</span> |
        <span>Alpa: {{ $stat['alpa'] }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Masuk</th>
                <th>Pulang</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rows as $r)
            <tr>
                <td>{{ $r['tanggal'] }}</td>
                <td>{{ $r['nama'] }}</td>
                <td>{{ $r['email'] }}</td>
                <td>{{ $r['status'] }}</td>
                <td>{{ $r['jam_masuk'] ?? '' }}</td>
                <td>{{ $r['jam_pulang'] ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<script>window.print()</script>
</body>
</html>
