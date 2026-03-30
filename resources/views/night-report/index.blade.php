<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Laporan Satpam</title>

    <meta name="theme-color" content="#4338ca">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-152.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">

    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-2: #eef3fb;
            --text: #1f2937;
            --muted: #64748b;
            --line: #d9e2ef;
            --primary: #4338ca;
            --primary-2: #6366f1;
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
                radial-gradient(circle at 0% 0%, #e0e7ff 0, transparent 35%),
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

        .brand h1 { margin: 0; font-size: clamp(1.4rem, 2.5vw, 2rem); letter-spacing: 0.2px; }
        .brand p  { margin: 4px 0 0; color: var(--muted); font-size: 0.95rem; }

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

        .error-alert {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
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
            transition: transform 0.15s ease, filter 0.2s ease;
            box-shadow: 0 10px 22px rgba(99, 102, 241, 0.3);
        }

        .btn:hover:not(:disabled) { transform: translateY(-1px); filter: brightness(1.05); }

        .btn-success {
            background: linear-gradient(135deg, #15803d, var(--success));
            box-shadow: 0 10px 22px rgba(22, 163, 74, 0.26);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #475569, #64748b);
            box-shadow: 0 10px 22px rgba(100, 116, 139, 0.2);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .field { display: flex; flex-direction: column; gap: 6px; }
        .field.full { grid-column: 1 / -1; }
        label { font-weight: 700; color: #0f172a; }

        textarea {
            width: 100%;
            border: 1px solid #c4d2e3;
            border-radius: 12px;
            padding: 11px 12px;
            font: inherit;
            color: inherit;
            background: #ffffff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            resize: vertical;
        }

        textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        #cameraContainer {
            background: var(--surface-2);
            border-radius: 14px;
            border: 1px solid #a8bfd7;
            overflow: hidden;
        }

        #cameraWrap { position: relative; display: none; }

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
            transition: transform 0.15s ease;
            pointer-events: all;
        }
        #captureBtn:hover { transform: scale(1.08); }

        .btn-flip { background: #0f172a; color: #f8fafc; }

        .helper-text { margin: 8px 0 0; font-size: 0.9rem; color: var(--muted); }

        .camera-placeholder {
            min-height: 180px;
            border-radius: 12px;
            border: 1px solid #9fb3c8;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e0e7ff, #ede9fe);
            color: #0f172a;
            text-align: center;
            padding: 16px;
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

        .geo-box {
            background: #f8fbff;
            border: 1px solid #dae6f5;
            border-radius: 12px;
            margin-top: 16px;
            padding: 12px;
            color: #0f172a;
        }

        .geo-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
            min-width: 600px;
        }

        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5ecf4;
            text-align: left;
            vertical-align: middle;
            font-size: 0.92rem;
        }

        th {
            background: #f3f6ff;
            color: #0f172a;
            font-size: 0.82rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            position: sticky;
            top: 0;
        }

        tbody tr:hover { background: #f8fbff; }

        .thumb { height: 56px; border-radius: 9px; border: 1px solid #d7e2ef; object-fit: cover; }
        .empty-state { padding: 18px; text-align: center; color: var(--muted); }
        .pagination { margin-top: 14px; }

        @media (max-width: 640px) {
            .geo-meta { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Topbar --}}
        <div class="topbar">
            <div class="brand">
                <h1>Laporan Satpam</h1>
                <p>Satpam &mdash; {{ auth()->user()->name }}</p>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                    &larr; Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="success-alert">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error-alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Form Laporan --}}
        <div class="card">
            <h2>Buat Laporan Satpam</h2>

            <form method="POST" action="{{ route('night-report.store') }}" enctype="multipart/form-data" id="nightReportForm">
                @csrf

                <div class="form-grid">
                    {{-- Laporan --}}
                    <div class="field full">
                        <label for="reportText">Isi Laporan</label>
                        <textarea name="report" id="reportText" rows="5" placeholder="Tulis laporan Anda di sini..." required>{{ old('report') }}</textarea>
                    </div>

                    {{-- Foto --}}
                    <div class="field full">
                        <label>Foto Pendukung</label>
                        <div id="cameraContainer">
                            <div id="cameraPlaceholder" class="camera-placeholder">
                                <div>
                                    <strong>Kamera Perangkat</strong>
                                    <p class="helper-text" style="margin:8px 0 0;color:#334155;">
                                        Ambil foto sebagai bukti laporan satpam.
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
                                <button type="button" id="flipCameraBtn" class="btn btn-flip" style="display:none;" title="Ganti Kamera">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><path d="M1 4v6h6"/><path d="M23 20v-6h-6"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                    Ganti Kamera
                                </button>
                                <button type="button" id="retakeBtn" class="btn" style="display:none;">Ulangi Foto</button>
                            </div>
                            <div class="camera-info">
                                <div id="photoStatus" class="photo-status pending">Foto belum diambil</div>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photoInput" style="display:none;" accept="image/*" capture="environment">
                        <input type="hidden" name="photo_base64" id="photoBase64">
                    </div>
                </div>

                <input type="hidden" name="latitude"  id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="address" id="addressInput">

                {{-- Lokasi --}}
                <div class="geo-box">
                    <strong>Lokasi Saat Ini</strong>
                    <p id="geo-status" style="margin:4px 0 0;color:var(--muted);font-size:0.9rem;">Mencari lokasi...</p>
                    <div class="geo-meta">
                        <div class="geo-meta-item">
                            <strong>Latitude</strong>
                            <span id="latVal">-</span>
                        </div>
                        <div class="geo-meta-item">
                            <strong>Longitude</strong>
                            <span id="lngVal">-</span>
                        </div>
                        <div class="geo-meta-item" style="grid-column: 1 / -1;">
                            <strong>Alamat</strong>
                            <span id="addressVal" style="font-size:0.88rem;line-height:1.5;">Menunggu lokasi...</span>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="margin-top:18px;">
                    <button type="submit" class="btn btn-success" style="width:100%;min-height:56px;font-size:1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Kirim Laporan Satpam
                    </button>
                </div>
            </form>
        </div>

        {{-- Riwayat Laporan --}}
        <div class="card">
            <h2>Riwayat Laporan Satpam</h2>
            @if($reports->count())
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Laporan</th>
                                <th>Foto</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td style="white-space:nowrap;">
                                        {{ $report->reported_at ? $report->reported_at->format('d-m-Y H:i') : '-' }}
                                    </td>
                                    <td style="max-width:250px;">{{ \Illuminate\Support\Str::limit($report->report, 100) }}</td>
                                    <td>
                                        @if($report->photo_path)
                                            <img src="{{ asset('storage/' . $report->photo_path) }}" class="thumb" alt="Foto laporan">
                                        @else
                                            <span style="color:var(--muted);font-size:0.85rem;">-</span>
                                        @endif
                                    </td>
                                    <td style="max-width:200px;font-size:0.85rem;">
                                        {{ \Illuminate\Support\Str::limit($report->address, 60) ?: '-' }}
                                    </td>
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

    <script>
    (function() {
        'use strict';

        // ── Camera Elements ──
        const cameraVideo       = document.getElementById('cameraVideo');
        const cameraCanvas      = document.getElementById('cameraCanvas');
        const photoPreview      = document.getElementById('photoPreview');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const cameraWrap        = document.getElementById('cameraWrap');
        const captureBtn        = document.getElementById('captureBtn');
        const openCameraBtn     = document.getElementById('openCameraBtn');
        const flipCameraBtn     = document.getElementById('flipCameraBtn');
        const retakeBtn         = document.getElementById('retakeBtn');
        const photoInput        = document.getElementById('photoInput');
        const photoStatus       = document.getElementById('photoStatus');

        let currentFacingMode = 'environment';
        let cameraStream = null;

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(t => t.stop());
                cameraStream = null;
            }
            cameraVideo.srcObject = null;
            cameraVideo.style.display = 'none';
            captureBtn.style.display = 'none';
            flipCameraBtn.style.display = 'none';
        }

        function updatePhotoStatus(taken) {
            if (taken) {
                photoStatus.textContent = '✓ Foto berhasil diambil';
                photoStatus.className = 'photo-status';
            } else {
                photoStatus.textContent = 'Foto belum diambil';
                photoStatus.className = 'photo-status pending';
            }
        }

        async function startCamera(facingMode) {
            facingMode = facingMode || currentFacingMode;
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                photoInput.click();
                return;
            }

            if (cameraStream) stopCamera();
            currentFacingMode = facingMode;

            try {
                let constraints = {
                    video: {
                        facingMode: { ideal: facingMode },
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

                cameraVideo.srcObject = cameraStream;
                await cameraVideo.play();
                cameraWrap.style.display = 'block';
                cameraVideo.style.display = 'block';
                cameraPlaceholder.style.display = 'none';
                captureBtn.style.display = 'inline-flex';
                flipCameraBtn.style.display = 'inline-flex';

                if (facingMode === 'user') {
                    cameraVideo.style.transform = 'scaleX(-1)';
                } else {
                    cameraVideo.style.transform = '';
                }
            } catch (err) {
                let msg = 'Tidak dapat mengakses kamera.';
                if (err.name === 'NotAllowedError') msg = 'Izin kamera ditolak.';
                if (err.name === 'NotFoundError')   msg = 'Kamera tidak ditemukan.';
                if (err.name === 'NotReadableError') msg = 'Kamera sedang digunakan.';
                alert(msg + ' Browser akan membuka mode kamera bawaan.');
                photoInput.click();
            }
        }

        function capturePhoto() {
            if (!cameraStream) return;
            cameraCanvas.width  = cameraVideo.videoWidth  || 1280;
            cameraCanvas.height = cameraVideo.videoHeight || 720;
            const ctx = cameraCanvas.getContext('2d');
            if (currentFacingMode === 'user') {
                ctx.translate(cameraCanvas.width, 0);
                ctx.scale(-1, 1);
            }
            ctx.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

            // Simpan base64 di hidden input sebagai fallback
            document.getElementById('photoBase64').value = cameraCanvas.toDataURL('image/jpeg', 0.88);

            cameraCanvas.toBlob(function(blob) {
                const file = new File([blob], 'laporan-' + Date.now() + '.jpg', { type: 'image/jpeg' });

                window._capturedPhotoBlob = { blob: blob, file: file };

                try {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    photoInput.files = dt.files;
                } catch (e) {
                    // Browser tidak support DataTransfer, photo_base64 akan digunakan
                }

                photoPreview.src = URL.createObjectURL(blob);
                photoPreview.style.display = 'block';
                stopCamera();
                retakeBtn.style.display = 'inline-flex';
                openCameraBtn.style.display = 'none';
                updatePhotoStatus(true);
            }, 'image/jpeg', 0.88);
        }

        openCameraBtn.addEventListener('click', function() { startCamera(); });
        flipCameraBtn.addEventListener('click', function() {
            startCamera(currentFacingMode === 'environment' ? 'user' : 'environment');
        });
        captureBtn.addEventListener('click', capturePhoto);

        retakeBtn.addEventListener('click', function() {
            stopCamera();
            photoInput.value = '';
            photoPreview.src = '';
            photoPreview.style.display = 'none';
            cameraPlaceholder.style.display = 'flex';
            retakeBtn.style.display = 'none';
            openCameraBtn.style.display = 'inline-flex';
            document.getElementById('photoBase64').value = '';
            window._capturedPhotoBlob = null;
            updatePhotoStatus(false);
            startCamera(currentFacingMode);
        });

        // file input fallback (dari kamera bawaan)
        photoInput.addEventListener('change', function() {
            if (photoInput.files && photoInput.files.length > 0) {
                photoPreview.src = URL.createObjectURL(photoInput.files[0]);
                photoPreview.style.display = 'block';
                cameraWrap.style.display = 'block';
                cameraPlaceholder.style.display = 'none';
                retakeBtn.style.display = 'inline-flex';
                openCameraBtn.style.display = 'none';
                updatePhotoStatus(true);
            }
        });

        // Intercept submit: pastikan blob terkirim
        document.getElementById('nightReportForm').addEventListener('formdata', function(e) {
            if (window._capturedPhotoBlob) {
                e.formData.set('photo', window._capturedPhotoBlob.blob, window._capturedPhotoBlob.file.name);
            }
        });

        // ── Geolocation ──
        function updateLocation(position) {
            var lat = position.coords.latitude.toFixed(7);
            var lng = position.coords.longitude.toFixed(7);

            document.getElementById('latVal').textContent = lat;
            document.getElementById('lngVal').textContent = lng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('geo-status').textContent = 'Lokasi ditemukan';

            // Reverse geocode
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1', {
                headers: { 'Accept-Language': 'id' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.display_name) {
                    document.getElementById('addressVal').textContent = data.display_name;
                    document.getElementById('addressInput').value = data.display_name;
                }
            })
            .catch(function() {});
        }

        function handleGeoError(err) {
            document.getElementById('geo-status').textContent = 'Gagal mendapatkan lokasi: ' + err.message;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(updateLocation, handleGeoError, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            });
        }
    })();
    </script>
</body>
</html>
