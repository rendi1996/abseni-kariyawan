<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Bulanan - BKK Banten</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Segoe UI", Arial, sans-serif; font-size: 11px; color: #111; background: #fff; padding: 16px 20px; }

        .header { display: flex; align-items: center; gap: 16px; border-bottom: 3px solid #1d4ed8; padding-bottom: 12px; margin-bottom: 12px; }
        .header .logo { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #1d4ed8, #3b82f6); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; font-weight: 900; flex-shrink: 0; }
        .header .info h1 { font-size: 15px; color: #1d4ed8; }
        .header .info p { font-size: 10px; color: #555; margin-top: 1px; }
        .header .meta { margin-left: auto; text-align: right; font-size: 10px; color: #555; line-height: 1.7; }
        .header .meta strong { color: #111; }

        .badge { display: inline-block; background: #1d4ed8; color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 10px; font-weight: 700; margin-bottom: 8px; }

        table { width: 100%; border-collapse: collapse; font-size: 9px; }
        thead tr { background: #1d4ed8; color: #fff; }
        thead th { padding: 5px 3px; font-weight: 700; text-align: center; white-space: nowrap; }
        tbody td { padding: 4px 3px; text-align: center; border-bottom: 1px solid #dde6f7; }
        tbody tr:nth-child(even) { background: #f7f9ff; }

        td.left { text-align: left; white-space: nowrap; }
        .c-in { background: #dcfce7; color: #166534; font-weight: 700; border-radius: 3px; }
        .c-out { background: #fee2e2; color: #991b1b; font-weight: 700; border-radius: 3px; }
        .bold { font-weight: 800; }

        .footer { margin-top: 14px; border-top: 1px solid #d0dcf0; padding-top: 8px; display: flex; justify-content: space-between; font-size: 9px; color: #777; }
        .legend { display: flex; gap: 12px; margin-bottom: 8px; font-size: 9px; color: #555; }
        .legend span { display: inline-flex; align-items: center; gap: 3px; }
        .legend i { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }

        .no-print { margin-top: 16px; text-align: center; }
        @media print {
            body { padding: 6px 8px; }
            .no-print { display: none !important; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .c-in, .c-out { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php
        $monthNames = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    @endphp

    <div class="header">
        <div class="logo">B</div>
        <div class="info">
            <h1>BKK BANTEN</h1>
            <p>Rekap Kehadiran Bulanan</p>
        </div>
        <div class="meta">
            <div><strong>Periode:</strong> {{ $monthNames[$month] }} {{ $year }}</div>
            <div><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            @if($department)<div><strong>Dept:</strong> {{ $department }}</div>@endif
        </div>
    </div>

    <div class="badge">Total: {{ count($reportData) }} karyawan</div>

    <div class="legend">
        <span><i style="background:#dcfce7;"></i> Masuk</span>
        <span><i style="background:#fee2e2;"></i> Pulang</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="text-align:left;">Nama</th>
                <th style="text-align:left;">Dept</th>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th>H</th>
                <th>A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="left">{{ $row['user']->name }}</td>
                    <td class="left">{{ optional($row['user']->employee)->department ?: '-' }}</td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @if(isset($row['daily'][$d]))
                            @php
                                $s = $row['daily'][$d];
                                $hasIn = in_array('in', $s);
                                $hasOut = in_array('out', $s);
                            @endphp
                            @if($hasIn)
                                <td class="c-in">&#10003;</td>
                            @elseif($hasOut)
                                <td class="c-out">P</td>
                            @else
                                <td>-</td>
                            @endif
                        @else
                            <td>-</td>
                        @endif
                    @endfor
                    <td class="bold">{{ $row['total_hadir'] }}</td>
                    <td class="bold">{{ $row['total_absen'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>BKK Banten &mdash; Sistem Informasi Kehadiran</span>
        <span>{{ now()->format('d F Y, H:i') }} WIB</span>
    </div>

    <div class="no-print">
        <button onclick="window.print()" style="padding:8px 24px;background:#1d4ed8;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">Cetak</button>
        <button onclick="window.close()" style="padding:8px 18px;background:#64748b;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;margin-left:8px;">Tutup</button>
    </div>
</body>
</html>
