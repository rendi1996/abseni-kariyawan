<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - BKK Banten</title>
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

        /* ── Filter Summary ── */
        .filter-summary {
            background: #f0f4ff;
            border: 1px solid #c7d8f8;
            border-radius: 6px;
            padding: 7px 12px;
            font-size: 10.5px;
            color: #333;
            margin-bottom: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-summary span { white-space: nowrap; }
        .filter-summary strong { color: #1d4ed8; }

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

        /* ── Photo cells ── */
        .photo-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            min-width: 90px;
        }

        .photo-wrap {
            position: relative;
            display: inline-block;
        }

        .photo-wrap img {
            display: block;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .photo-wrap .photo-label {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.55);
            color: #fff;
            font-size: 8px;
            text-align: center;
            padding: 1px 0;
            border-radius: 0 0 3px 3px;
        }

        .photo-placeholder {
            width: 58px; height: 58px;
            background: #e8edf8;
            border: 1px dashed #aab9d8;
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px;
            color: #8ea0be;
            text-align: center;
        }

        /* ── Status badge ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-in  { background: #dcfce7; color: #15803d; }
        .badge-out { background: #fef9c3; color: #854d0e; }
        .badge-wfh { background: #dbeafe; color: #1e40af; }

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
            .badge    { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

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
            <p>Laporan Rekap Absensi Pegawai</p>
            <p>Sistem Informasi Kehadiran</p>
        </div>
        <div class="meta">
            <div><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            @if($dateFrom || $dateTo)
            <div>
                <strong>Periode:</strong>
                {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : '...' }}
                &ndash;
                {{ $dateTo   ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : '...' }}
            </div>
            @endif
            @if($department)
            <div><strong>Departemen:</strong> {{ $department }}</div>
            @endif
            @if($search)
            <div><strong>Pegawai:</strong> {{ $search }}</div>
            @endif
        </div>
    </div>

    {{-- ── Filter Summary ── --}}
    @if($search || $department || $dateFrom || $dateTo)
    <div class="filter-summary">
        <span>&#128269; Filter aktif:</span>
        @if($search)    <span>Pegawai: <strong>{{ $search }}</strong></span>     @endif
        @if($department)<span>Dept: <strong>{{ $department }}</strong></span>    @endif
        @if($dateFrom)  <span>Dari: <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong></span> @endif
        @if($dateTo)    <span>Sampai: <strong>{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong></span> @endif
    </div>
    @endif

    {{-- ── Total ── --}}
    <div class="total-badge">Total: {{ $attendances->count() }} data absensi</div>

    {{-- ── Table ── --}}
    <table>
        <thead>
            <tr>
                <th style="width:32px">No</th>
                <th style="width:96px">Foto</th>
                <th>Nama Pegawai</th>
                <th>Dept / Jabatan</th>
                <th style="width:70px">Tanggal</th>
                <th style="width:58px">Jam</th>
                <th style="width:58px">Status</th>
                <th>Lokasi / Alamat</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $i => $attendance)
            @php
                $employee = optional(optional($attendance->user)->employee);
                $profilePhoto = $employee->profile_photo_path ?? null;
                $selfiePhoto  = $attendance->photo_path ?? null;
            @endphp
            <tr>
                {{-- No --}}
                <td style="text-align:center; color:#666">{{ $i + 1 }}</td>

                {{-- Foto kolom: profile kiri, selfie kanan --}}
                <td>
                    <div class="photo-cell">
                        {{-- Foto profil pegawai --}}
                        <div class="photo-wrap">
                            @if($profilePhoto)
                                <img src="{{ asset('storage/' . $profilePhoto) }}" width="42" height="42" style="border-radius:50%; border:2px solid #3b82f6;" alt="Profil">
                            @else
                                <div class="photo-placeholder" style="width:42px;height:42px;border-radius:50%;">Profil</div>
                            @endif
                        </div>
                        {{-- Foto absen selfie --}}
                        <div class="photo-wrap">
                            @if($selfiePhoto)
                                <img src="{{ asset('storage/' . $selfiePhoto) }}" width="58" height="52" alt="Foto Absen">
                                <span class="photo-label">Absen</span>
                            @else
                                <div class="photo-placeholder" style="width:58px;height:52px;">Tidak ada foto</div>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Nama --}}
                <td>
                    <strong>{{ $attendance->user ? $attendance->user->name : 'Guest' }}</strong><br>
                    <span style="color:#666;font-size:10px">{{ $attendance->user ? $attendance->user->email : '-' }}</span>
                    @if($employee->employee_code)
                    <br><span style="color:#1d4ed8;font-size:10px">NIP: {{ $employee->employee_code }}</span>
                    @endif
                </td>

                {{-- Dept / Jabatan --}}
                <td>
                    <span style="font-weight:600">{{ $employee->department ?: '-' }}</span><br>
                    <span style="color:#555;font-size:10px">{{ $employee->position ?: '-' }}</span>
                </td>

                {{-- Tanggal --}}
                <td style="white-space:nowrap">
                    {{ $attendance->attended_at ? $attendance->attended_at->format('d/m/Y') : '-' }}
                </td>

                {{-- Jam --}}
                <td style="white-space:nowrap; text-align:center">
                    {{ $attendance->attended_at ? $attendance->attended_at->format('H:i') : '-' }}
                </td>

                {{-- Status --}}
                <td style="text-align:center">
                    @if($attendance->status === 'in')
                        <span class="badge badge-in">Masuk</span>
                    @elseif($attendance->status === 'out')
                        <span class="badge badge-out">Pulang</span>
                    @elseif($attendance->status === 'wfh')
                        <span class="badge badge-wfh">WFH</span>
                    @else
                        <span class="badge" style="background:#f1f5f9;color:#475569">{{ $attendance->status }}</span>
                    @endif
                </td>

                {{-- Lokasi --}}
                <td style="font-size:10.5px; max-width:180px">
                    @if($attendance->address)
                        {{ Str::limit($attendance->address, 80) }}
                    @elseif($attendance->latitude && $attendance->longitude)
                        {{ $attendance->latitude }}, {{ $attendance->longitude }}
                    @elseif($attendance->status === 'wfh')
                        <span style="color:#1e40af">Work From Home</span>
                    @else
                        -
                    @endif
                </td>

                {{-- Keterangan --}}
                <td style="font-size:10.5px; max-width:140px; color:#444">
                    {{ $attendance->notes ?: '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:20px; color:#888">
                    Tidak ada data absensi untuk filter yang diterapkan.
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