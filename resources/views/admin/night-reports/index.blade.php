<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Laporan Satpam</title>
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

        .container { max-width: 1220px; margin: auto; }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: var(--shadow);
        }

        .heading h1 { margin: 0 0 4px; font-size: 1.5rem; }
        .heading p  { margin: 0; color: var(--muted); font-size: 0.92rem; }

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
            min-width: 900px;
        }

        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5ecf4;
            text-align: left;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        th {
            background: #f3f6ff;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            position: sticky;
            top: 0;
        }

        tbody tr:hover { background: #f8fbff; }

        .thumb {
            height: 56px;
            border-radius: 8px;
            border: 1px solid #d7e2ef;
            object-fit: cover;
        }

        .empty-state { padding: 24px; text-align: center; color: var(--muted); }
        .pagination { margin-top: 14px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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

        @media (max-width: 768px) {
            .filter-bar { flex-direction: column; }
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
                    <a href="{{ route('admin.night-reports.index') }}" style="background:rgba(255,255,255,0.28);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.5);">Laporan Satpam</a>
                    <a href="{{ route('admin.reports.monthly') }}" style="background:rgba(255,255,255,0.18);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.3);">&#128202; Rekap Laporan</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="background:#dc2626;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.88rem;font-weight:600;border:none;cursor:pointer;">Logout</button>
                    </form>
                </div>
            </div>

            <div class="heading">
                <h1>Laporan Satpam</h1>
                <p>Data laporan dari seluruh pegawai satpam.</p>
            </div>
        </div>

        {{-- Stats --}}
        @php
            $totalReports = $reports->total();
            $todayReports = \App\Models\NightReport::whereDate('reported_at', today())->count();
        @endphp
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Laporan</div>
                <div class="stat-value">{{ $totalReports }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Laporan Hari Ini</div>
                <div class="stat-value">{{ $todayReports }}</div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card">
            <form method="GET" action="{{ route('admin.night-reports.index') }}">
                <div class="filter-bar">
                    <div>
                        <label>Cari Nama/Email</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari...">
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
                    <div>
                        <label>Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div>
                        <label>Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div style="display:flex;gap:8px;align-items:flex-end;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.night-reports.index') }}" class="btn" style="background:#64748b;">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Export --}}
        <div style="margin-bottom:14px;display:flex;gap:10px;">
            <a href="{{ route('admin.night-reports.export', request()->query()) }}" class="btn btn-success">&#128229; Export Excel</a>
        </div>

        {{-- Table --}}
        <div class="card">
            <h2 style="margin-top:0;margin-bottom:14px;font-size:1.1rem;">Daftar Laporan Satpam</h2>

            @if($reports->count())
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Jabatan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Laporan</th>
                                <th>Foto</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $i => $report)
                                <tr>
                                    <td>{{ $reports->firstItem() + $i }}</td>
                                    <td>{{ $report->user ? $report->user->name : 'Guest' }}</td>
                                    <td>{{ optional(optional($report->user)->employee)->department ?: '-' }}</td>
                                    <td>{{ optional(optional($report->user)->employee)->position ?: '-' }}</td>
                                    <td style="white-space:nowrap;">{{ $report->reported_at ? $report->reported_at->format('d-m-Y') : '-' }}</td>
                                    <td style="white-space:nowrap;">{{ $report->reported_at ? $report->reported_at->format('H:i') : '-' }}</td>
                                    <td style="max-width:280px;">{{ \Illuminate\Support\Str::limit($report->report, 120) }}</td>
                                    <td>
                                        @if($report->photo_path)
                                            <img src="{{ asset('storage/' . $report->photo_path) }}" class="thumb" alt="Foto laporan">
                                        @else
                                            <span style="color:var(--muted);font-size:0.8rem;">-</span>
                                        @endif
                                    </td>
                                    <td style="max-width:200px;font-size:0.85rem;">{{ \Illuminate\Support\Str::limit($report->address, 80) ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">{{ $reports->links() }}</div>
            @else
                <div class="empty-state">Belum ada laporan satpam.</div>
            @endif
        </div>
    </div>
</body>
</html>
