<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Absensi</title>
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

        * {
            box-sizing: border-box;
        }

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

        .container {
            max-width: 1220px;
            margin: auto;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: var(--shadow);
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .heading h1 {
            margin: 0;
            font-size: clamp(1.6rem, 2.8vw, 2.2rem);
        }

        .subtitle {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 11px;
            padding: 10px 14px;
            margin: 2px;
            text-decoration: none;
            cursor: pointer;
            color: #ffffff;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--primary-soft));
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.28);
            transition: transform 0.15s ease, filter 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .btn-danger {
            background: linear-gradient(135deg, #b91c1c, var(--danger));
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.24);
        }

        .btn-success {
            background: linear-gradient(135deg, #15803d, var(--success));
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.24);
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .welcome {
            color: var(--muted);
            font-weight: 600;
        }

        .table-wrap {
            border: 1px solid #dce6f2;
            border-radius: 14px;
            overflow-x: auto;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 900px;
        }

        th,
        td {
            border-bottom: 1px solid #e4ebf4;
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
            font-size: 0.92rem;
        }

        th {
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-size: 0.8rem;
            background: #f2f7ff;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .status-pill {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .status-in {
            background: #dcfce7;
            color: #166534;
        }

        .status-out {
            background: #fee2e2;
            color: #991b1b;
        }

        .thumb {
            height: 56px;
            border-radius: 8px;
            border: 1px solid #d6e2ef;
            object-fit: cover;
        }

        .pagination {
            margin-top: 12px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            border: 1px solid #dce6f2;
            border-radius: 14px;
            padding: 16px;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text);
        }

        .section-title {
            margin: 20px 0 12px;
            font-size: 1.1rem;
        }

        .empty-state {
            padding: 18px;
            text-align: center;
            color: var(--muted);
        }

        .filters {
            border: 1px solid #dce6f2;
            border-radius: 14px;
            padding: 14px;
            background: #f8fbff;
            margin-bottom: 18px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .field label {
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 700;
        }

        .field input,
        .field select {
            border: 1px solid #cad8e7;
            border-radius: 10px;
            padding: 9px 10px;
            font: inherit;
        }

        @media (max-width: 820px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            body {
                padding-top: 18px;
            }

            .card {
                padding: 16px;
            }
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
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="background:#dc2626;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.88rem;font-weight:600;border:none;cursor:pointer;">Logout</button>
                    </form>
                </div>
            </div>

            <div class="heading">
                <div>
                    <h1>Admin - Manajemen Absensi</h1>
                    <p class="subtitle">Pantau semua absensi karyawan secara terpusat.</p>
                </div>
            </div>

            <div class="toolbar">
                <div class="welcome">Selamat datang, {{ auth()->user()->name }} (Admin)!</div>
                <div class="toolbar-actions">
                    <a href="{{ route('admin.attendance.print', array_filter(['search' => $search, 'department' => $department, 'date_from' => $dateFrom, 'date_to' => $dateTo])) }}" target="_blank" class="btn btn-danger">&#128438; Print Laporan</a>
                    <a href="{{ route('admin.attendance.export', request()->query()) }}" class="btn btn-success">Export Excel Pegawai Masuk</a>
                </div>
            </div>

            <div class="filters">
                <form method="GET" action="{{ route('admin.attendance.index') }}">
                    <div class="filters-grid">
                        <div class="field">
                            <label for="search">Cari Pegawai</label>
                            <input id="search" name="search" type="text" value="{{ $search }}" placeholder="Nama atau email pegawai">
                        </div>
                        <div class="field">
                            <label for="department">Departemen</label>
                            <select id="department" name="department">
                                <option value="">Semua departemen</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="date_from">Tanggal Dari</label>
                            <input id="date_from" name="date_from" type="date" value="{{ $dateFrom }}">
                        </div>
                        <div class="field">
                            <label for="date_to">Tanggal Sampai</label>
                            <input id="date_to" name="date_to" type="date" value="{{ $dateTo }}">
                        </div>
                        <div class="toolbar-actions">
                            <button type="submit" class="btn">Terapkan</button>
                            <a href="{{ route('admin.attendance.index') }}" class="btn">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-label">Pegawai masuk hari ini</div>
                    <div class="stat-value">{{ $todayCheckIns->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total data masuk</div>
                    <div class="stat-value">{{ $checkInReport->total() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total semua absensi</div>
                    <div class="stat-value">{{ $attendances->total() }}</div>
                </div>
            </div>

            <h2 class="section-title">Data Pegawai Yang Masuk Hari Ini</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Email</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Jam Masuk</th>
                            <th>Posisi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayCheckIns as $attendance)
                            <tr>
                                <td>{{ $attendance->user ? $attendance->user->name : 'Guest' }}</td>
                                <td>{{ $attendance->user ? $attendance->user->email : '-' }}</td>
                                <td>{{ optional(optional($attendance->user)->employee)->department ?: '-' }}</td>
                                <td>{{ optional(optional($attendance->user)->employee)->position ?: '-' }}</td>
                                <td>{{ $attendance->attended_at ? $attendance->attended_at->format('H:i:s') : '-' }}</td>
                                <td>{{ $attendance->latitude ?: '-' }}, {{ $attendance->longitude ?: '-' }}</td>
                                <td>{{ $attendance->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">Belum ada pegawai yang check-in hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h2 class="section-title">Laporan Pegawai Masuk</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pegawai</th>
                            <th>Email</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Posisi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkInReport as $attendance)
                            <tr>
                                <td>{{ $attendance->id }}</td>
                                <td>{{ $attendance->user ? $attendance->user->name : 'Guest' }}</td>
                                <td>{{ $attendance->user ? $attendance->user->email : '-' }}</td>
                                <td>{{ optional(optional($attendance->user)->employee)->department ?: '-' }}</td>
                                <td>{{ optional(optional($attendance->user)->employee)->position ?: '-' }}</td>
                                <td>{{ $attendance->attended_at ? $attendance->attended_at->format('Y-m-d') : '-' }}</td>
                                <td>{{ $attendance->attended_at ? $attendance->attended_at->format('H:i:s') : '-' }}</td>
                                <td>{{ $attendance->latitude ?: '-' }}, {{ $attendance->longitude ?: '-' }}</td>
                                <td>{{ $attendance->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">Belum ada laporan pegawai masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $checkInReport->links() }}</div>

            <h2>Daftar Semua Absensi</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Posisi</th>
                            <th>Foto</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->id }}</td>
                                <td>{{ $attendance->user ? $attendance->user->name : 'Guest' }}</td>
                                <td>{{ $attendance->attended_at ? $attendance->attended_at->format('Y-m-d H:i:s') : '-' }}</td>
                                <td>
                                    <span class="status-pill {{ $attendance->status === 'in' ? 'status-in' : 'status-out' }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td>{{ $attendance->latitude ?: '-' }}, {{ $attendance->longitude ?: '-' }}</td>
                                <td>
                                    @if($attendance->photo_path)
                                        <img src="{{ asset('storage/' . $attendance->photo_path) }}" class="thumb" alt="Foto">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $attendance->notes ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $attendances->links() }}</div>
        </div>
    </div>
</body>
</html>
