<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fff;
            color: #333;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #003366;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
            display: block;
        }

        .header-text h1 {
            font-size: 18px;
            color: #003366;
            margin-bottom: 3px;
        }

        .report-date {
            text-align: right;
            font-size: 11px;
            color: #666;
            padding-top: 5px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-section h2 {
            font-size: 16px;
            color: #003366;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .title-section p {
            font-size: 12px;
            color: #555;
        }

        .statistic-section {
            background: #f8f9fa;
            border-left: 4px solid #003366;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .stat-row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #003366;
        }

        .daily-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .daily-header {
            background: #003366;
            color: #fff;
            padding: 8px 12px;
            margin-bottom: 0;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px 4px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }

        th {
            background: #e8eef7;
            border: 1px solid #bbb;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #003366;
        }

        td {
            border: 1px solid #ddd;
            padding: 7px 8px;
            vertical-align: top;
        }

        tbody tr:nth-child(odd) {
            background: #fafafa;
        }

        tbody tr:nth-child(even) {
            background: #fff;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-hadir {
            background: #d4edda;
            color: #155724;
        }

        .status-terlambat {
            background: #fff3cd;
            color: #856404;
        }

        .status-izin {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-alpa {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .daily-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
<div class="container">
    @php
        $rangeStartLabel = !empty($range['start']) ? \Carbon\Carbon::parse($range['start'])->translatedFormat('d F Y') : '-';
        $rangeEndLabel = !empty($range['end']) ? \Carbon\Carbon::parse($range['end'])->translatedFormat('d F Y') : '-';
    @endphp

    <div class="header">
        <div class="logo-section">
            <img id="logo-bps" src="{{ asset('logo-bps.png') }}" alt="Logo BPS" class="logo">
            <div class="header-text">
                <h1>Badan Pusat Statistik<br>Provinsi Sulawesi Utara</h1>
            </div>
        </div>
        <div class="report-date">
            <p>Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>

    <div class="title-section">
        <h2>Laporan Presensi Peserta Magang</h2>
        <p>Periode: {{ $rangeStartLabel }} s/d {{ $rangeEndLabel }}</p>
    </div>


    @php
        $rowsByDate = collect($rows)->groupBy('tanggal')->sortKeys();
    @endphp

    @forelse($rowsByDate as $tanggal => $dataHari)
        <div class="daily-section">
            <div class="daily-header">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                <span style="float: right;">{{ $dataHari->count() }} peserta</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Nama</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Jam Masuk</th>
                        <th style="width: 15%;">Jam Pulang</th>
                        <th style="width: 15%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataHari as $r)
                        <tr>
                            <td><strong>{{ $r['nama'] }}</strong></td>
                            <td>{{ $r['email'] }}</td>
                            <td>
                                @php
                                    $statusClass = match($r['status']) {
                                        'hadir' => 'status-hadir',
                                        'terlambat' => 'status-terlambat',
                                        'izin' => 'status-izin',
                                        'alpa' => 'status-alpa',
                                        default => 'status-alpa',
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ ucfirst($r['status']) }}</span>
                            </td>
                            <td>{{ $r['jam_masuk'] ?? '-' }}</td>
                            <td>{{ $r['jam_pulang'] ?? '-' }}</td>
                            <td><small>{{ $r['keterangan'] ?? '-' }}</small></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align: center; color: #999; padding: 20px;">Tidak ada data untuk periode yang dipilih.</p>
    @endforelse

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem InternHub pada {{ now()->translatedFormat('d F Y H:i:s') }}</p>
    </div>
</div>

<script>
    (function () {
        const logo = document.getElementById('logo-bps');
        let printed = false;

        function triggerPrint() {
            if (printed) return;
            printed = true;
            window.print();
        }

        if (logo && !logo.complete) {
            logo.addEventListener('load', triggerPrint, { once: true });
            logo.addEventListener('error', triggerPrint, { once: true });
            setTimeout(triggerPrint, 2000);
        } else {
            setTimeout(triggerPrint, 200);
        }
    })();
</script>
</body>
</html>
