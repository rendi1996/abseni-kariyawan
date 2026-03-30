<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Satpam - BKK Banten</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 11.5px;
            color: #111;
            background: #fff;
            padding: 20px 24px;
        }

        /* ── Header ── */
        .report-header {
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 3px solid #1d4ed8;
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .report-header .logo-circle {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 22px;
            font-weight: 900;
            flex-shrink: 0;
        }

        .report-header .org-info h1 {
            font-size: 17px;
            font-weight: 800;
            color: #1d4ed8;
            letter-spacing: 0.3px;
        }

        .report-header .org-info p {
            font-size: 10.5px;
            color: #555;
            margin-top: 2px;
        }

        .report-header .meta {
            margin-left: auto;
            text-align: right;
            font-size: 10.5px;
            color: #555;
            line-height: 1.7;
        }

        .report-header .meta strong {
            color: #111;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        thead tr {
            background: #1d4ed8;
            color: #fff;
        }

        thead th {
            padding: 8px 7px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        tbody tr { border-bottom: 1px solid #dde6f7; }
        tbody tr:nth-child(even) { background: #f7f9ff; }

        tbody td {
            padding: 7px 7px;
            vertical-align: middle;
        }

        /* ── Photo ── */
        .photo-wrap img {
            display: block;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .photo-placeholder {
            width: 58px; height: 52px;
            background: #e8edf8;
            border: 1px dashed #aab9d8;
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px;
            color: #8ea0be;
            text-align: center;
        }

        /* ── Footer ── */
        .report-footer {
            margin-top: 20px;
            border-top: 1px solid #d0dcf0;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #777;
        }

        .total-badge {
            display: inline-block;
            background: #1d4ed8;
            color: #fff;
            border-radius: 5px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* ── Print ── */
        @media print {
            body { padding: 8px 10px; }
            .no-print { display: none !important; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    {{-- ── Report Header ── --}}
    <div class="report-header">
        <div class="logo-circle">B</div>
        <div class="org-info">
            <h1>BKK BANTEN</h1>
            <p>Laporan Satpam</p>
            <p>Sistem Informasi Kehadiran</p>
        </div>
        <div class="meta">
            <div><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            <div><strong>Nama:</strong> {{ auth()->user()->name }}</div>
            <div><strong>Email:</strong> {{ auth()->user()->email }}</div>
        </div>
    </div>

    {{-- ── Total ── --}}
    <div class="total-badge">Total: {{ $reports->count() }} laporan</div>

    {{-- ── Table ── --}}
    <table>
        <thead>
            <tr>
                <th style="width:32px">No</th>
                <th style="width:70px">Tanggal</th>
                <th style="width:58px">Waktu</th>
                <th style="width:80px">Foto</th>
                <th>Laporan</th>
                <th>Lokasi / Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $i => $report)
            <tr>
                <td style="text-align:center; color:#666">{{ $i + 1 }}</td>

                <td style="white-space:nowrap">
                    {{ $report->reported_at ? $report->reported_at->format('d/m/Y') : '-' }}
                </td>

                <td style="white-space:nowrap; text-align:center">
                    {{ $report->reported_at ? $report->reported_at->format('H:i') : '-' }}
                </td>

                <td>
                    @if($report->photo_path)
                        <div class="photo-wrap">
                            <img src="{{ asset('storage/' . $report->photo_path) }}" width="58" height="52" alt="Foto Laporan">
                        </div>
                    @else
                        <div class="photo-placeholder">Tidak ada foto</div>
                    @endif
                </td>

                <td style="font-size:10.5px; max-width:250px; color:#333">
                    {{ Str::limit($report->report, 150) ?: '-' }}
                </td>

                <td style="font-size:10.5px; max-width:180px">
                    @if($report->address)
                        {{ Str::limit($report->address, 80) }}
                    @elseif($report->latitude && $report->longitude)
                        {{ $report->latitude }}, {{ $report->longitude }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#888">
                    Belum ada laporan satpam.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── Footer ── --}}
    <div class="report-footer">
        <span>BKK Banten &mdash; Sistem Informasi Kehadiran</span>
        <span>Dicetak pada {{ now()->format('d F Y, H:i') }} WIB</span>
    </div>

    {{-- ── Print Button (no-print) ── --}}
    <div class="no-print" style="margin-top:20px; text-align:center">
        <button onclick="window.print()"
            style="padding:10px 28px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer;">
            &#128438; Cetak Laporan
        </button>
        <button onclick="window.close()"
            style="padding:10px 20px; background:#64748b; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; margin-left:10px;">
            Tutup
        </button>
    </div>

</body>
</html>
