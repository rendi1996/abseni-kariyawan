<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Absensi</title>

    {{-- PWA Meta Tags --}}
    <meta name="theme-color" content="#0f766e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Absensi">
    <meta name="application-name" content="Absensi Harian">
    <meta name="msapplication-TileColor" content="#0f766e">
    <meta name="msapplication-TileImage" content="/icons/icon-144.png">

    {{-- Manifest & Icons --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-152.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512.png">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-2: #eef3fb;
            --text: #1f2937;
            --muted: #64748b;
            --line: #d9e2ef;
            --primary: #0f766e;
            --primary-2: #14b8a6;
            --danger: #dc2626;
            --success: #16a34a;
            --shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
            --radius: 16px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 0% 0%, #d8f8f2 0, transparent 35%),
                radial-gradient(circle at 100% 100%, #dbeafe 0, transparent 30%),
                var(--bg);
            min-height: 100vh;
            padding: 28px 16px 48px;
        }

        .container { max-width: 760px; margin: 0 auto; }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand h1 { margin: 0; font-size: clamp(1.6rem, 2.8vw, 2.2rem); letter-spacing: 0.2px; }
        .brand p  { margin: 4px 0 0; color: var(--muted); font-size: 0.95rem; }

        .welcome {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-weight: 500;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: var(--shadow);
        }

        .card h2 { margin-top: 0; margin-bottom: 16px; font-size: 1.2rem; }

        .success-alert {
            background: #ebfff4;
            border: 1px solid #9de7bb;
            color: #065f46;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 18px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 12px;
            padding: 10px 16px;
            margin: 2px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            transition: transform 0.15s ease, filter 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            box-shadow: 0 10px 22px rgba(20, 184, 166, 0.3);
        }

        .btn:hover:not(:disabled) { transform: translateY(-1px); filter: brightness(1.05); }
        .btn:disabled { cursor: not-allowed; }

        .btn-danger {
            background: linear-gradient(135deg, #b91c1c, var(--danger));
            box-shadow: 0 10px 22px rgba(220, 38, 38, 0.25);
        }

        .btn-success {
            background: linear-gradient(135deg, #15803d, var(--success));
            box-shadow: 0 10px 22px rgba(22, 163, 74, 0.26);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .field { display: flex; flex-direction: column; gap: 6px; }
        .field.full { grid-column: 1 / -1; }
        label { font-weight: 700; color: #0f172a; }

        select, textarea {
            width: 100%;
            border: 1px solid #c4d2e3;
            border-radius: 12px;
            padding: 11px 12px;
            font: inherit;
            color: inherit;
            background: #ffffff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        select:focus, textarea:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }

        #cameraContainer {
            background: var(--surface-2);
            border-radius: 14px;
            border: 1px solid #a8bfd7;
            overflow: hidden;
        }

        #cameraWrap {
            position: relative;
            display: none;
        }

        #cameraVideo {
            display: none;
            width: 100%;
            aspect-ratio: 3 / 4;
            background: #000;
            object-fit: cover;
            vertical-align: top;
        }

        #photoPreview {
            display: none;
            width: 100%;
            object-fit: cover;
            vertical-align: top;
        }

        #cameraCanvas { display: none; }

        .capture-overlay {
            position: absolute;
            bottom: 24px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            pointer-events: none;
        }

        .camera-toolbar { display: flex; flex-wrap: wrap; gap: 8px; padding: 10px 12px; justify-content: center; align-items: center; }
        .camera-info { padding: 0 12px 12px; }

        #captureBtn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0;
            background: linear-gradient(135deg, #15803d, var(--success));
            box-shadow: 0 8px 24px rgba(22, 163, 74, 0.4);
            border: none;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            pointer-events: all;
        }
        #captureBtn:hover { transform: scale(1.08); box-shadow: 0 12px 28px rgba(22,163,74,0.5); }
        #captureBtn svg { pointer-events: none; }
        .helper-text { margin: 8px 0 0; font-size: 0.9rem; color: var(--muted); }

        .schedule-strip {
            margin-bottom: 14px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid #c7d9ee;
            background: #f5faff;
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
        }

        .photo-result-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 14px;
        }

        .photo-result-card {
            border: 1px solid #d9e2ef;
            border-radius: 12px;
            padding: 12px;
            background: #ffffff;
        }

        .photo-result-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        .photo-result-time {
            margin-top: 8px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .photo-result-img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #d9e2ef;
            background: #f8fafc;
        }

        .photo-result-empty {
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 10px;
            border: 1px dashed #cbd5e1;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 0.88rem;
            background: #f8fafc;
        }

        .photo-status {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 7px 12px;
            background: #effcf6;
            color: #166534;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .photo-status.pending { background: #fff7ed; color: #9a3412; }

        .camera-placeholder {
            min-height: 220px;
            border-radius: 12px;
            border: 1px solid #9fb3c8;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #dbeafe, #ecfeff);
            color: #0f172a;
            text-align: center;
            padding: 16px;
        }

        /* ── Attendance action buttons ── */
        .attendance-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .attendance-btn { min-height: 56px; font-size: 1rem; margin: 0; }

        .attendance-btn-out {
            background: linear-gradient(135deg, #b91c1c, var(--danger));
            box-shadow: 0 10px 22px rgba(220, 38, 38, 0.25);
        }

        .attendance-btn-wfh {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.24);
        }

        /* ── Geo box ── */
        .geo-box {
            background: #f8fbff;
            border: 1px solid #dae6f5;
            border-radius: 12px;
            margin-top: 16px;
            padding: 12px;
            color: #0f172a;
        }

        .geo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .geo-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .geo-meta-item {
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            padding: 10px;
            background: #ffffff;
        }

        .geo-meta-item strong {
            display: block;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--muted);
            margin-bottom: 6px;
        }

        /* ── Radius alert ── */
        .radius-alert {
            display: none;
            margin-top: 14px;
            border-radius: 12px;
            padding: 12px 14px;
            font-weight: 600;
            font-size: 0.92rem;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }

        .radius-alert.danger {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            display: flex;
        }

        .radius-alert.success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #15803d;
            display: flex;
        }

        #attendanceMap {
            width: 100%;
            min-height: 300px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #cfe0f1;
        }

        .map-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .geo-box p { margin: 6px 0; }

        /* ── Tables ── */
        .table-wrap {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid #d8e3f1;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 780px;
        }

        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5ecf4;
            text-align: left;
            vertical-align: middle;
            font-size: 0.92rem;
        }

        th {
            background: #f3f8ff;
            color: #0f172a;
            font-size: 0.82rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tbody tr:hover { background: #f8fbff; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 10px;
            font-weight: 700;
            font-size: 0.76rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .status-in  { background: #dcfce7; color: #166534; }
        .status-out { background: #fee2e2; color: #991b1b; }
        .status-wfh { background: #dbeafe; color: #1e40af; }

        .thumb { height: 56px; border-radius: 9px; border: 1px solid #d7e2ef; object-fit: cover; }
        .pagination { margin-top: 14px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            border: 1px solid #d8e3f1;
            border-radius: 14px;
            padding: 16px;
        }

        .stat-label { color: var(--muted); font-size: 0.85rem; margin-bottom: 8px; }
        .stat-value { font-size: 1.8rem; font-weight: 800; color: #0f172a; }

        .section-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .table-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .table-title h2 { margin: 0; }
        .table-title p  { margin: 4px 0 0; color: var(--muted); font-size: 0.9rem; }
        .empty-state { padding: 18px; text-align: center; color: var(--muted); }

        @media (max-width: 860px) {
            .stats, .section-grid { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .geo-meta { grid-template-columns: 1fr; }
            .attendance-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .card { padding: 16px; }
            body { padding-top: 18px; }
            .camera-toolbar .btn { min-height: 48px; }
            .photo-result-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    {{-- PWA Install Banner --}}
    <div id="pwa-install-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9999;background:#0f766e;color:#fff;padding:14px 16px;align-items:center;gap:12px;box-shadow:0 -4px 16px rgba(0,0,0,0.2);">
        <img src="/icons/icon-72.png" alt="icon" style="width:40px;height:40px;border-radius:10px;flex-shrink:0;">
        <div style="flex:1;min-width:0;">
            <strong style="font-size:0.95rem;">Install Aplikasi Absensi</strong>
            <p style="margin:2px 0 0;font-size:0.8rem;opacity:0.85;">Tambahkan ke layar utama untuk akses cepat</p>
        </div>
        <button id="pwa-install-btn" style="background:#fff;color:#0f766e;border:none;border-radius:8px;padding:8px 16px;font-weight:700;cursor:pointer;font-size:0.85rem;white-space:nowrap;">Install</button>
        <button id="pwa-dismiss-btn" style="background:transparent;border:none;color:#fff;font-size:1.4rem;cursor:pointer;padding:0 4px;line-height:1;">&times;</button>
    </div>

    <div class="container">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="brand">
                <h1>Absensi Harian</h1>
                <p>Foto, lokasi, dan jadwal masuk.</p>
            </div>
            <div class="welcome">
                <span>Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="success-alert">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="success-alert" style="background:#fff1f2;border-color:#fecdd3;color:#9f1239;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Absensi --}}
        <div class="card">
            <h2>Form Absensi</h2>
            <div class="schedule-strip">
                Jadwal Masuk: 07:00 - 16:00
            </div>

            <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" id="statusInput" value="in">

                <div class="form-grid">

                    {{-- Foto --}}
                    <div class="field full">
                        <label>Foto</label>
                        <div id="cameraContainer">
                            <div id="cameraPlaceholder" class="camera-placeholder">
                                <div>
                                    <strong>Kamera Perangkat</strong>
                                    <p class="helper-text" style="margin:8px 0 0;color:#334155;">
                                        Buka kamera lalu ambil foto langsung dari perangkat.
                                    </p>
                                </div>
                            </div>
                            <div id="cameraWrap">
                                <video id="cameraVideo" autoplay playsinline muted></video>
                                <canvas id="cameraCanvas"></canvas>
                                <img id="photoPreview" alt="Preview Foto">
                                <div class="capture-overlay">
                                    <button type="button" id="captureBtn" style="display:none;" title="Ambil Foto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                            <circle cx="12" cy="13" r="4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="camera-toolbar">
                                <button type="button" id="openCameraBtn" class="btn">Buka Kamera</button>
                                <button type="button" id="retakeBtn" class="btn" style="display:none;">Ulangi Foto</button>
                            </div>
                            <div class="camera-info">
                                <p class="helper-text"><strong>Android mode:</strong> kamera belakang diprioritaskan agar framing wajah lebih pas.</p>
                                <div id="photoStatus" class="photo-status pending">Foto belum diambil</div>
                                <div id="captureWarning" style="display:none;background:#fee2e2;border:1px solid #fecdd3;color:#9f1239;border-radius:8px;padding:10px;margin-top:8px;font-size:0.85rem;">
                                    ⚠️ <strong>Warning:</strong> File ini seperti upload manual, bukan dari kamera. Silakan ambil foto langsung dari kamera.
                                </div>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photoInput" style="display:none;" accept="image/*" capture="environment">
                    </div>
                </div>

                <input type="hidden" name="latitude"  id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="address" id="addressInput">

                {{-- Geo box --}}
                <div class="geo-box">
                    <div class="geo-header">
                        <div>
                            <strong>Lokasi Absensi</strong>
                            <p id="geo-status">Mencari lokasi...</p>
                        </div>
                        <button class="btn" id="refreshLocation" type="button">Refresh Lokasi</button>
                    </div>

                    <div id="attendanceMap"></div>

                    <div class="geo-meta">
                        <div class="geo-meta-item">
                            <strong>Latitude</strong>
                            <span id="latVal">-</span>
                        </div>
                        <div class="geo-meta-item">
                            <strong>Longitude</strong>
                            <span id="lngVal">-</span>
                        </div>
                        <div class="geo-meta-item">
                            <strong>Akurasi GPS</strong>
                            <span id="accuracyVal">-</span>
                        </div>
                        <div class="geo-meta-item" style="grid-column: 1 / -1;">
                            <strong>Alamat</strong>
                            <span id="addressVal" style="font-size:0.88rem;line-height:1.5;">Menunggu lokasi...</span>
                        </div>
                    </div>

                    {{-- Radius alert --}}
                    <div id="radiusAlert" class="radius-alert"></div>

                    <div class="map-actions">
                        <a href="#" id="openMapsLink" class="btn" style="pointer-events:none;opacity:.6;">
                            Buka di Google Maps
                        </a>
                    </div>
                </div>

                {{-- Submit buttons --}}
                <div class="attendance-actions">
                    <button class="btn btn-success attendance-btn" type="submit" data-status="in" data-requires-geo="1"
                            id="btnMasuk" disabled style="opacity:.45;" title="Menunggu lokasi...">
                        Absen Masuk
                    </button>
                    <button class="btn attendance-btn attendance-btn-out" type="submit" data-status="out" data-requires-geo="1"
                            id="btnPulang" disabled style="opacity:.45;" title="Menunggu lokasi...">
                        Absen Pulang
                    </button>
                </div>
                @if(!($canCheckoutByHours ?? false) && !empty($checkoutWaitMessage))
                    <p style="margin-top:10px;font-size:0.86rem;color:#b91c1c;font-weight:600;">
                        {{ $checkoutWaitMessage }}
                    </p>
                @endif
            </form>
        </div>

        @php
            $lastIn = $checkIns->first();
            $lastOut = $checkOuts->first();
        @endphp
        <div class="card">
            <h2>Foto Absensi Terakhir</h2>
            <div class="photo-result-grid">
                <div class="photo-result-card">
                    <div class="photo-result-title">Foto Datang Terakhir</div>
                    @if($lastIn && $lastIn->photo_path)
                        <img src="{{ asset('storage/' . $lastIn->photo_path) }}" alt="Foto datang terakhir" class="photo-result-img">
                        <div class="photo-result-time">
                            {{ $lastIn->attended_at ? $lastIn->attended_at->format('d-m-Y H:i') : '-' }}
                        </div>
                    @else
                        <div class="photo-result-empty">Belum ada foto datang</div>
                    @endif
                </div>

                <div class="photo-result-card">
                    <div class="photo-result-title">Foto Pergi Terakhir</div>
                    @if($lastOut && $lastOut->photo_path)
                        <img src="{{ asset('storage/' . $lastOut->photo_path) }}" alt="Foto pergi terakhir" class="photo-result-img">
                        <div class="photo-result-time">
                            {{ $lastOut->attended_at ? $lastOut->attended_at->format('d-m-Y H:i') : '-' }}
                        </div>
                    @else
                        <div class="photo-result-empty">Belum ada foto pergi</div>
                    @endif
                </div>
            </div>
        </div>
    </div><!-- /.container -->

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // ── DOM refs ──────────────────────────────────────────────────────────
        const geoStatus      = document.getElementById('geo-status');
        const latVal         = document.getElementById('latVal');
        const lngVal         = document.getElementById('lngVal');
        const accuracyVal    = document.getElementById('accuracyVal');
        const addressVal     = document.getElementById('addressVal');
        const addressInput   = document.getElementById('addressInput');
        const radiusAlert    = document.getElementById('radiusAlert');
        const latitudeInput  = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const refreshBtn     = document.getElementById('refreshLocation');
        const openMapsLink   = document.getElementById('openMapsLink');
        const photoStatus    = document.getElementById('photoStatus');
        const statusInput    = document.getElementById('statusInput');
        const attendanceBtns = document.querySelectorAll('[data-status]');
        const geoRequiredBtns = document.querySelectorAll('[data-requires-geo="1"]');
        const btnPulang = document.getElementById('btnPulang');
        const canCheckoutByHours = @json($canCheckoutByHours ?? false);
        const checkoutWaitMessage = @json($checkoutWaitMessage ?? '');

        const openCameraBtn     = document.getElementById('openCameraBtn');
        const captureBtn        = document.getElementById('captureBtn');
        const retakeBtn         = document.getElementById('retakeBtn');
        const photoInput        = document.getElementById('photoInput');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const cameraWrap        = document.getElementById('cameraWrap');
        const photoPreview      = document.getElementById('photoPreview');
        const cameraVideo       = document.getElementById('cameraVideo');
        const cameraCanvas      = document.getElementById('cameraCanvas');
        let   cameraStream      = null;

        // ── Konfigurasi 5 wilayah kantor (nilai dari config/attendance.php) ──
        const OFFICE_AREAS = @json($officeAreas);

        let map, marker, accuracyCircle;

        // ── Peta ──────────────────────────────────────────────────────────────
        function initializeMap() {
            const firstArea = OFFICE_AREAS[0] || { latitude: -6.1200, longitude: 106.1503 };
            map = L.map('attendanceMap', { zoomControl: true }).setView([
                Number(firstArea.latitude),
                Number(firstArea.longitude),
            ], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            OFFICE_AREAS.forEach((area) => {
                const areaLat = Number(area.latitude);
                const areaLng = Number(area.longitude);
                const areaRadius = Number(area.radius || 500);

                const officeIcon = L.divIcon({
                    html: '<div style="background:#0f766e;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 2px #0f766e;"></div>',
                    className: '',
                    iconAnchor: [7, 7],
                });

                L.marker([areaLat, areaLng], { icon: officeIcon })
                    .addTo(map)
                    .bindPopup('<strong>' + area.name + '</strong><br>Radius absensi: ' + areaRadius + ' m');

                L.circle([areaLat, areaLng], {
                    radius: areaRadius,
                    color: '#0f766e',
                    fillColor: '#14b8a6',
                    fillOpacity: 0.08,
                    dashArray: '6 4',
                }).addTo(map);
            });
        }

        // ── Haversine distance (meter) ─────────────────────────────────────────
        function haversineDistance(lat1, lng1, lat2, lng2) {
            const R    = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a    = Math.sin(dLat / 2) ** 2
                       + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                       * Math.sin(dLng / 2) ** 2;
            return R * 2 * Math.asin(Math.sqrt(a));
        }

        // ── Reverse geocoding (Nominatim) ─────────────────────────────────────
        let _geocodeTimer = null;
        function fetchAddress(lat, lng) {
            addressVal.textContent = 'Mencari alamat...';
            clearTimeout(_geocodeTimer);
            _geocodeTimer = setTimeout(() => {
                fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng, {
                    headers: { 'Accept-Language': 'id' }
                })
                .then(r => r.json())
                .then(data => {
                    const resolvedAddress = data.display_name || '-';
                    addressVal.textContent = resolvedAddress;
                    addressInput.value = resolvedAddress === '-' ? '' : resolvedAddress;
                })
                .catch(() => {
                    addressVal.textContent = 'Alamat tidak tersedia.';
                    addressInput.value = '';
                });
            }, 800);
        }

        function getNearestOfficeArea(lat, lng) {
            let nearest = null;

            OFFICE_AREAS.forEach((area) => {
                const areaLat = Number(area.latitude);
                const areaLng = Number(area.longitude);
                const areaRadius = Number(area.radius || 500);
                const distance = haversineDistance(areaLat, areaLng, lat, lng);

                if (!nearest || distance < nearest.distance) {
                    nearest = {
                        name: area.name,
                        radius: areaRadius,
                        distance,
                        inside: distance <= areaRadius,
                    };
                }
            });

            if (!nearest) {
                return {
                    name: 'Wilayah kantor',
                    radius: 0,
                    distance: Number.POSITIVE_INFINITY,
                    inside: false,
                };
            }

            return nearest;
        }

        // ── Kontrol tombol submit ─────────────────────────────────────────────
        function setSubmitState(enabled) {
            geoRequiredBtns.forEach(btn => {
                const isCheckoutBtn = btn.dataset.status === 'out';
                const enabledByHours = isCheckoutBtn ? canCheckoutByHours : true;
                const finalEnabled = enabled && enabledByHours;

                btn.disabled      = !finalEnabled;
                btn.style.opacity = finalEnabled ? '1' : '0.45';

                if (!enabled) {
                    btn.title = 'Anda di luar radius kantor atau lokasi belum diperoleh';
                } else if (isCheckoutBtn && !enabledByHours) {
                    btn.title = checkoutWaitMessage || 'Absen pulang aktif setelah 8 jam dari absen masuk.';
                } else {
                    btn.title = '';
                }
            });
        }

        // ── Foto ──────────────────────────────────────────────────────────────
        function updatePhotoStatus(isReady) {
            photoStatus.textContent = isReady ? 'Foto siap dikirim' : 'Foto belum diambil';
            photoStatus.classList.toggle('pending', !isReady);
        }

        // ── Kamera via getUserMedia ────────────────────────────────────────
        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(t => t.stop());
                cameraStream = null;
            }
            cameraVideo.srcObject = null;
            cameraVideo.style.display = 'none';
            captureBtn.style.display  = 'none';
        }

        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                photoInput.click();
                return;
            }

            try {
                // Prioritaskan kamera belakang dan rasio portrait agar cocok untuk Android.
                let constraints = {
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1080 },
                        height: { ideal: 1440 },
                        aspectRatio: { ideal: 0.75 }
                    }
                };
                try {
                    cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                } catch (e) {
                    cameraStream = await navigator.mediaDevices.getUserMedia({ video: true });
                }
                cameraVideo.srcObject     = cameraStream;
                await cameraVideo.play();
                cameraWrap.style.display  = 'block';
                cameraVideo.style.display = 'block';
                captureBtn.style.display  = 'inline-flex';
                openCameraBtn.style.display = 'none';
                photoPreview.style.display  = 'none';
                cameraPlaceholder.style.display = 'none';
                retakeBtn.style.display     = 'none';
            } catch (err) {
                let msg = 'Tidak dapat mengakses kamera langsung.';
                if (err.name === 'NotAllowedError')  msg = 'Izin kamera ditolak. Silakan izinkan akses kamera di browser Anda.';
                if (err.name === 'NotFoundError')    msg = 'Kamera tidak ditemukan di perangkat ini.';
                if (err.name === 'NotReadableError') msg = 'Kamera sedang digunakan oleh aplikasi lain.';
                alert(msg + ' Browser akan membuka mode kamera bawaan.');
                photoInput.click();
            }
        }

        function capturePhoto() {
            if (!cameraStream) return;
            cameraCanvas.width  = cameraVideo.videoWidth  || 1280;
            cameraCanvas.height = cameraVideo.videoHeight || 720;
            cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

            cameraCanvas.toBlob((blob) => {
                const file = new File([blob], 'absensi-' + Date.now() + '.jpg', { type: 'image/jpeg' });

                // Masukkan file ke input via DataTransfer agar bisa terkirim lewat form
                try {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    photoInput.files = dt.files;
                } catch (e) {
                    // Safari lama: simpan blob, kirim via intercept submit
                    window._capturedPhotoBlob = { blob, file };
                }

                photoPreview.src           = URL.createObjectURL(blob);
                photoPreview.style.display = 'block';
                stopCamera();
                retakeBtn.style.display     = 'inline-flex';
                openCameraBtn.style.display = 'none';
                updatePhotoStatus(true);
            }, 'image/jpeg', 0.88);
        }

        openCameraBtn.addEventListener('click', startCamera);
        captureBtn.addEventListener('click', capturePhoto);

        retakeBtn.addEventListener('click', () => {
            stopCamera();
            photoInput.value               = '';
            photoPreview.src               = '';
            photoPreview.style.display     = 'none';
            cameraPlaceholder.style.display = 'flex';
            retakeBtn.style.display        = 'none';
            openCameraBtn.style.display    = 'inline-flex';
            document.getElementById('captureWarning').style.display = 'none';
            window._capturedPhotoBlob      = null;
            updatePhotoStatus(false);
            // Langsung buka kamera ulang
            startCamera();
        });

        // Intercept submit untuk Safari lama yang tidak support DataTransfer assignment
        document.querySelector('form').addEventListener('formdata', (e) => {
            if (window._capturedPhotoBlob && (!photoInput.files || photoInput.files.length === 0)) {
                e.formData.set('photo', window._capturedPhotoBlob.blob, window._capturedPhotoBlob.file.name);
            }
        });

        attendanceBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const selectedStatus = btn.dataset.status;
                statusInput.value = selectedStatus;

                if (selectedStatus === 'wfh') {
                    latitudeInput.value = '';
                    longitudeInput.value = '';
                    addressInput.value = '';
                }
            });
        });

        // ── Geolocation ───────────────────────────────────────────────────────
        function updateLocation(position) {
            const lat      = position.coords.latitude.toFixed(7);
            const lng      = position.coords.longitude.toFixed(7);
            const accuracy = Math.round(position.coords.accuracy || 0);
            const latNum   = Number(lat);
            const lngNum   = Number(lng);

            latVal.textContent      = lat;
            lngVal.textContent      = lng;
            accuracyVal.textContent = accuracy ? accuracy + ' meter' : '-';
            latitudeInput.value     = lat;
            longitudeInput.value    = lng;

            // Hitung jarak ke wilayah kantor terdekat
            const nearestArea = getNearestOfficeArea(latNum, lngNum);
            const dist = Math.round(nearestArea.distance);
            const insideRadius = nearestArea.inside;

            radiusAlert.className   = 'radius-alert ' + (insideRadius ? 'success' : 'danger');
            radiusAlert.textContent = insideRadius
                ? '✓ Anda berada ' + dist + ' m dari titik ' + nearestArea.name + ' — dalam jangkauan absensi (' + nearestArea.radius + ' m).'
                : '✗ Anda berada ' + dist + ' m dari titik ' + nearestArea.name + ' (batas ' + nearestArea.radius + ' m). Harap mendekat dahulu.';

            setSubmitState(insideRadius);

            geoStatus.textContent = insideRadius
                ? 'Lokasi diperoleh — dalam jangkauan wilayah ' + nearestArea.name + '.'
                : 'Lokasi diperoleh — di luar jangkauan wilayah kantor.';

            // Marker posisi user
            if (!marker) {
                marker = L.marker([latNum, lngNum]).addTo(map);
            } else {
                marker.setLatLng([latNum, lngNum]);
            }

            if (!accuracyCircle) {
                accuracyCircle = L.circle([latNum, lngNum], {
                    radius: accuracy || 10,
                    color: '#0f766e',
                    fillColor: '#14b8a6',
                    fillOpacity: 0.15,
                }).addTo(map);
            } else {
                accuracyCircle.setLatLng([latNum, lngNum]);
                accuracyCircle.setRadius(accuracy || 10);
            }

            marker.bindPopup('Posisi Anda saat ini').openPopup();
            map.setView([latNum, lngNum], 17);

            openMapsLink.href              = 'https://www.google.com/maps?q=' + lat + ',' + lng;
            openMapsLink.style.pointerEvents = 'auto';
            openMapsLink.style.opacity     = '1';

            fetchAddress(lat, lng);
        }

        function handleError(error) {
            geoStatus.textContent   = 'Error mendapatkan lokasi: ' + error.message;
            addressVal.textContent  = '-';
            addressInput.value      = '';
            setSubmitState(false);
        }

        function fetchLocation() {
            if (!navigator.geolocation) {
                geoStatus.textContent = 'Geolocation tidak didukung browser ini.';
                return;
            }
            geoStatus.textContent = 'Meminta lokasi...';
            navigator.geolocation.getCurrentPosition(updateLocation, handleError, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            });
        }

        refreshBtn.addEventListener('click', fetchLocation);

        // ── Init ──────────────────────────────────────────────────────────────
        initializeMap();
        updatePhotoStatus(false);
        setSubmitState(false);
        fetchLocation();

        // ── PWA Service Worker ─────────────────────────────────────────────
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .catch((err) => console.warn('SW registration failed:', err));
            });
        }

        // ── PWA Install Banner ─────────────────────────────────────────────
        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const banner = document.getElementById('pwa-install-banner');
            if (banner) banner.style.display = 'flex';
        });

        window.addEventListener('appinstalled', () => {
            const banner = document.getElementById('pwa-install-banner');
            if (banner) banner.style.display = 'none';
            deferredPrompt = null;
        });

        document.getElementById('pwa-install-btn')?.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            if (outcome === 'accepted') {
                document.getElementById('pwa-install-banner').style.display = 'none';
            }
        });

        document.getElementById('pwa-dismiss-btn')?.addEventListener('click', () => {
            document.getElementById('pwa-install-banner').style.display = 'none';
        });
    </script>
</body>
</html>
