<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Laporan Bulanan</title>
    <style>
        :root {
            --bg: #f5f7fc;
            --surface: #ffffff;
            --line: #d8e2f0;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #1d4ed8;
            --primary-soft: #3b82f6;
            --danger: #dc2626;
            --success: #16a34a;
            --shadow: 0 16px 34px rgba(15, 23, 42, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            min-height: 100vh;
            background:
                radial-gradient(circle at 0% 0%, #e0e7ff 0, transparent 40%),
                radial-gradient(circle at 100% 100%, #d1fae5 0, transparent 28%),
                var(--bg);
            padding: 28px 16px 48px;
        }

        .container { max-width: 1320px; margin: auto; }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: var(--shadow);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 10px;
            padding: 8px 18px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.88rem;
            color: #fff;
            text-decoration: none;
            transition: transform 0.12s ease, filter 0.15s ease;
        }
        .btn:hover { transform: translateY(-1px); filter: brightness(1.1); }
        .btn-primary { background: var(--primary); }
        .btn-success { background: var(--success); }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            border: 1px solid #d8e3f1;
            border-radius: 14px;
            padding: 16px;
        }

        .stat-label { color: var(--muted); font-size: 0.83rem; margin-bottom: 6px; }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: #0f172a; }

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: flex-end;
            margin-bottom: 18px;
        }

        .filter-bar label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .filter-bar input, .filter-bar select {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 8px 12px;
            font: inherit;
            font-size: 0.9rem;
            outline: none;
        }

        .filter-bar input:focus, .filter-bar select:focus {
            border-color: var(--primary-soft);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .table-wrap {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid #d8e3f1;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th, td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5ecf4;
            text-align: center;
            vertical-align: middle;
            font-size: 0.82rem;
        }

        th {
            background: #f3f6ff;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            position: sticky;
            top: 0;
            z-index: 1;
            white-space: nowrap;
        }

        td.name-col {
            text-align: left;
            white-space: nowrap;
            font-weight: 600;
            min-width: 160px;
        }

        td.dept-col {
            text-align: left;
            white-space: nowrap;
            font-size: 0.78rem;
            color: var(--muted);
        }

        tbody tr:hover { background: #f8fbff; }

        .day-cell {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .day-in { background: #dcfce7; color: #166534; }
        .day-out { background: #fee2e2; color: #991b1b; }
        .day-wfh { background: #dbeafe; color: #1e40af; }
        .day-empty { background: #f1f5f9; color: #cbd5e1; }

        .summary-cell {
            font-weight: 800;
            font-size: 0.88rem;
        }

        .summary-hadir { color: #166534; }
        .summary-absen { color: #991b1b; }
        .summary-wfh { color: #1e40af; }

        .legend {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            display: inline-block;
        }

        .empty-state { padding: 24px; text-align: center; color: var(--muted); }

        .tab-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 18px;
        }

        .tab-bar a {
            padding: 9px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--muted);
            background: #f1f5f9;
            border: 1px solid var(--line);
            transition: all 0.15s ease;
        }

        .tab-bar a:hover { background: #e0e7ff; color: var(--primary); }
        .tab-bar a.active { background: var(--primary); color: #fff; border-color: var(--primary); }

        @media (max-width: 768px) {
            .filter-bar { flex-direction: column; }
        }

        @media print {
            body { padding: 8px; }
            .no-print { display: none !important; }
            .card { box-shadow: none; border: 1px solid #ddd; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .day-in, .day-out, .day-wfh, .day-empty { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            {{-- Navbar Admin --}}
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;background:#1d4ed8;color:#fff;border-radius:14px;padding:12px 20px;margin-bottom:18px;">
                <div style="font-weight:700;font-size:1rem;letter-spacing:0.5px;">&#128196; BKK Banten &mdash; Admin Panel</div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <a href="{{ route('admin.attendance.index') }}" style="background:rgba(255,255,255,0.18);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.3);">&#128202; Data Absensi</a>
                    <a href="{{ route('admin.employees.index') }}" style="background:rgba(255,255,255,0.18);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.3);">&#128101; Data Karyawan</a>
                    <a href="{{ route('admin.night-reports.index') }}" style="background:rgba(255,255,255,0.18);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.3);">Laporan Satpam</a>
                    <a href="{{ route('admin.reports.monthly') }}" style="background:rgba(255,255,255,0.28);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.5);">&#128202; Rekap Laporan</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="background:#dc2626;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.88rem;font-weight:600;border:none;cursor:pointer;">Logout</button>
                    </form>
                </div>
            </div>

            <div style="margin-bottom:4px;">
                <h1 style="margin:0 0 4px;font-size:1.5rem;">Rekap Laporan Absensi</h1>
                <p style="margin:0;color:var(--muted);font-size:0.92rem;">Analisis kehadiran karyawan secara bulanan dan tahunan.</p>
            </div>
        </div>

        {{-- Tab Bar --}}
        <div class="tab-bar no-print">
            <a href="{{ route('admin.reports.monthly', request()->query()) }}" class="active">&#128197; Bulanan</a>
            <a href="{{ route('admin.reports.yearly', ['year' => $year, 'department' => $department]) }}">&#128198; Tahunan</a>
        </div>

        {{-- Stats --}}
        @php
            $monthNames = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        @endphp
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Periode</div>
                <div class="stat-value" style="font-size:1.2rem;">{{ $monthNames[$month] }} {{ $year }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Karyawan</div>
                <div class="stat-value">{{ $totalEmployees }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rata-rata Hadir</div>
                <div class="stat-value">{{ $avgHadir }} <span style="font-size:0.7rem;color:var(--muted);">hari</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Hari Kerja</div>
                <div class="stat-value">{{ $daysInMonth }} <span style="font-size:0.7rem;color:var(--muted);">hari</span></div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card no-print">
            <form method="GET" action="{{ route('admin.reports.monthly') }}">
                <div class="filter-bar">
                    <div>
                        <label>Bulan</label>
                        <select name="month">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month === $m ? 'selected' : '' }}>{{ $monthNames[$m] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label>Tahun</label>
                        <select name="year">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label>Departemen</label>
                        <select name="department">
                            <option value="">Semua</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:8px;align-items:flex-end;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.reports.monthly') }}" class="btn" style="background:#64748b;">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Export & Print --}}
        <div style="margin-bottom:14px;display:flex;gap:10px;" class="no-print">
            <a href="{{ route('admin.reports.monthly.export', request()->query()) }}" class="btn btn-success">&#128196; Export Excel</a>
            <a href="{{ route('admin.reports.monthly.print', request()->query()) }}" target="_blank" class="btn btn-primary">&#128438; Cetak PDF</a>
        </div>

        {{-- Monthly Table --}}
        <div class="card">
            <h2 style="margin:0 0 12px;font-size:1.1rem;">Rekap Kehadiran &mdash; {{ $monthNames[$month] }} {{ $year }}</h2>

            {{-- Legend --}}
            <div class="legend">
                <div class="legend-item"><span class="legend-dot day-in"></span> Masuk</div>
                <div class="legend-item"><span class="legend-dot day-wfh"></span> WFH</div>
                <div class="legend-item"><span class="legend-dot day-out"></span> Pulang</div>
                <div class="legend-item"><span class="legend-dot day-empty"></span> Tidak Hadir</div>
            </div>

            @if(count($reportData))
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:32px;">No</th>
                                <th style="text-align:left;">Nama</th>
                                <th style="text-align:left;">Dept</th>
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    <th>{{ $d }}</th>
                                @endfor
                                <th style="background:#dcfce7;color:#166534;">H</th>
                                <th style="background:#dbeafe;color:#1e40af;">W</th>
                                <th style="background:#fee2e2;color:#991b1b;">A</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData as $idx => $row)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="name-col">
                                        {{ $row['user']->name }}
                                    </td>
                                    <td class="dept-col">
                                        {{ optional($row['user']->employee)->department ?: '-' }}
                                    </td>
                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        <td>
                                            @if(isset($row['daily'][$d]))
                                                @php
                                                    $statuses = $row['daily'][$d];
                                                    $hasIn = in_array('in', $statuses);
                                                    $hasWfh = in_array('wfh', $statuses);
                                                    $hasOut = in_array('out', $statuses);
                                                @endphp
                                                @if($hasWfh)
                                                    <span class="day-cell day-wfh" title="WFH">W</span>
                                                @elseif($hasIn && $hasOut)
                                                    <span class="day-cell day-in" title="Masuk & Pulang">&#10003;</span>
                                                @elseif($hasIn)
                                                    <span class="day-cell day-in" title="Masuk">M</span>
                                                @elseif($hasOut)
                                                    <span class="day-cell day-out" title="Pulang">P</span>
                                                @endif
                                            @else
                                                <span class="day-cell day-empty">&middot;</span>
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="summary-cell summary-hadir">{{ $row['total_hadir'] }}</td>
                                    <td class="summary-cell summary-wfh">{{ $row['total_wfh'] }}</td>
                                    <td class="summary-cell summary-absen">{{ $row['total_absen'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">Tidak ada data karyawan untuk filter yang dipilih.</div>
            @endif
        </div>
    </div>
</body>
</html>
