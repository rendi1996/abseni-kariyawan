<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Laporan Tahunan</title>
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
            padding: 10px 10px;
            border-bottom: 1px solid #e5ecf4;
            text-align: center;
            vertical-align: middle;
            font-size: 0.85rem;
        }

        th {
            background: #f3f6ff;
            font-size: 0.75rem;
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
            font-size: 0.8rem;
            color: var(--muted);
        }

        tbody tr:hover { background: #f8fbff; }

        .month-cell {
            font-weight: 700;
            font-size: 0.88rem;
        }

        .month-zero { color: #cbd5e1; }
        .month-low { color: #dc2626; }
        .month-mid { color: #d97706; }
        .month-high { color: #16a34a; }

        .total-cell {
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--primary);
        }

        .bar-bg {
            background: #f1f5f9;
            border-radius: 6px;
            height: 8px;
            width: 100%;
            margin-top: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: 6px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            transition: width 0.3s ease;
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
            .bar-bg, .bar-fill { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
            <a href="{{ route('admin.reports.monthly', ['year' => $year, 'department' => $department]) }}">&#128197; Bulanan</a>
            <a href="{{ route('admin.reports.yearly', request()->query()) }}" class="active">&#128198; Tahunan</a>
        </div>

        @php
            $monthShort = ['', 'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        @endphp

        {{-- Stats --}}
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Tahun</div>
                <div class="stat-value">{{ $year }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Karyawan</div>
                <div class="stat-value">{{ $totalEmployees }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rata-rata Hadir/Tahun</div>
                <div class="stat-value">
                    {{ $totalEmployees > 0 ? round(collect($reportData)->avg('year_total'), 0) : 0 }}
                    <span style="font-size:0.7rem;color:var(--muted);">hari</span>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card no-print">
            <form method="GET" action="{{ route('admin.reports.yearly') }}">
                <div class="filter-bar">
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
                        <a href="{{ route('admin.reports.yearly') }}" class="btn" style="background:#64748b;">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Export & Print --}}
        <div style="margin-bottom:14px;display:flex;gap:10px;" class="no-print">
            <a href="{{ route('admin.reports.yearly.export', request()->query()) }}" class="btn btn-success">&#128196; Export Excel</a>
            <a href="{{ route('admin.reports.yearly.print', request()->query()) }}" target="_blank" class="btn btn-primary">&#128438; Cetak PDF</a>
        </div>

        {{-- Yearly Table --}}
        <div class="card">
            <h2 style="margin:0 0 14px;font-size:1.1rem;">Rekap Kehadiran Tahunan &mdash; {{ $year }}</h2>

            @if(count($reportData))
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:32px;">No</th>
                                <th style="text-align:left;">Nama</th>
                                <th style="text-align:left;">Dept</th>
                                @for($m = 1; $m <= 12; $m++)
                                    <th>{{ $monthShort[$m] }}</th>
                                @endfor
                                <th style="background:#dbeafe;color:#1d4ed8;">Total</th>
                                <th style="width:100px;">Grafik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $maxTotal = collect($reportData)->max('year_total') ?: 1;
                            @endphp
                            @foreach($reportData as $idx => $row)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="name-col">{{ $row['user']->name }}</td>
                                    <td class="dept-col">{{ optional($row['user']->employee)->department ?: '-' }}</td>
                                    @for($m = 1; $m <= 12; $m++)
                                        @php
                                            $h = $row['monthly'][$m]['hadir'];
                                            $w = $row['monthly'][$m]['wfh'];
                                        @endphp
                                        <td>
                                            @if($h === 0)
                                                <span class="month-cell month-zero">-</span>
                                            @else
                                                <span class="month-cell {{ $h < 10 ? 'month-low' : ($h < 18 ? 'month-mid' : 'month-high') }}" title="Hadir: {{ $h }}, WFH: {{ $w }}">
                                                    {{ $h }}
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="total-cell">{{ $row['year_total'] }}</td>
                                    <td>
                                        <div class="bar-bg">
                                            <div class="bar-fill" style="width:{{ round(($row['year_total'] / $maxTotal) * 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">Tidak ada data karyawan untuk filter yang dipilih.</div>
            @endif
        </div>

        {{-- Monthly Legend --}}
        <div class="card">
            <h3 style="margin:0 0 10px;font-size:0.95rem;">Keterangan Warna</h3>
            <div style="display:flex;gap:20px;flex-wrap:wrap;font-size:0.85rem;color:var(--muted);">
                <div><span style="color:#16a34a;font-weight:700;">&#9679;</span> &ge; 18 hari (Baik)</div>
                <div><span style="color:#d97706;font-weight:700;">&#9679;</span> 10-17 hari (Cukup)</div>
                <div><span style="color:#dc2626;font-weight:700;">&#9679;</span> &lt; 10 hari (Kurang)</div>
                <div><span style="color:#cbd5e1;font-weight:700;">&#9679;</span> 0 hari (Tidak ada)</div>
            </div>
        </div>
    </div>
</body>
</html>
