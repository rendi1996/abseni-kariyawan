<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $baseQuery = Attendance::where('user_id', auth()->id());
        $officeAreas = config('attendance.office_areas', []);
        $today = now()->toDateString();

        $checkIns = (clone $baseQuery)
            ->where('status', 'in')
            ->latest('attended_at')
            ->paginate(8, ['*'], 'kedatangan_page');

        $checkOuts = (clone $baseQuery)
            ->where('status', 'out')
            ->latest('attended_at')
            ->paginate(8, ['*'], 'pulang_page');

        $wfhs = (clone $baseQuery)
            ->where('status', 'wfh')
            ->latest('attended_at')
            ->paginate(8, ['*'], 'wfh_page');

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'check_ins' => (clone $baseQuery)->where('status', 'in')->count(),
            'check_outs' => (clone $baseQuery)->where('status', 'out')->count(),
            'wfh' => (clone $baseQuery)->where('status', 'wfh')->count(),
        ];

        $lastCheckInToday = (clone $baseQuery)
            ->where('status', 'in')
            ->whereDate('attended_at', $today)
            ->latest('attended_at')
            ->first();

        $canCheckoutByHours = false;
        $checkoutWaitMessage = 'Silakan absen masuk terlebih dahulu.';

        if ($lastCheckInToday) {
            $readyAt = $lastCheckInToday->attended_at->copy()->addHours(8);
            $canCheckoutByHours = now()->greaterThanOrEqualTo($readyAt);

            if (! $canCheckoutByHours) {
                $minutesLeft = now()->diffInMinutes($readyAt, false) * -1;
                $hoursLeft = intdiv($minutesLeft, 60);
                $remainMinutes = $minutesLeft % 60;
                $checkoutWaitMessage = 'Absen pulang aktif setelah 8 jam dari absen masuk. Sisa waktu: ' . $hoursLeft . ' jam ' . $remainMinutes . ' menit.';
            } else {
                $checkoutWaitMessage = '';
            }
        }

        return view('attendance.index', compact('checkIns', 'checkOuts', 'wfhs', 'summary', 'officeAreas', 'canCheckoutByHours', 'checkoutWaitMessage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:in,out,wfh',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:1000',
            'photo' => 'required|image|max:4096',
            'notes' => 'nullable|string|max:500',
        ]);

        $status = $data['status'];
        $statusLabel = $status === 'in' ? 'masuk' : ($status === 'out' ? 'pulang' : 'WFH');
        $now = now();
        $today = $now->toDateString();

        if (in_array($status, ['in', 'out'], true) && ! $this->isWithinAllowedTime($status, $now)) {
            $rule = config('attendance.time_rules.' . $status, []);
            $start = $rule['start'] ?? '-';
            $end = $rule['end'] ?? '-';

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'status' => 'Absensi ' . $statusLabel . ' hanya boleh pada jam ' . $start . '-' . $end . '.',
                ]);
        }

        if ($status === 'out') {
            $lastCheckInToday = Attendance::where('user_id', auth()->id())
                ->where('status', 'in')
                ->whereDate('attended_at', $today)
                ->latest('attended_at')
                ->first();

            if (! $lastCheckInToday) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'status' => 'Absen pulang tidak bisa dilakukan karena Anda belum absen masuk hari ini.',
                    ]);
            }

            $checkoutReadyAt = $lastCheckInToday->attended_at->copy()->addHours(8);
            if ($now->lt($checkoutReadyAt)) {
                $minutesLeft = $now->diffInMinutes($checkoutReadyAt);
                $hoursLeft = intdiv($minutesLeft, 60);
                $remainMinutes = $minutesLeft % 60;

                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'status' => 'Absen pulang baru bisa dilakukan setelah 8 jam dari absen masuk. Sisa waktu: ' . $hoursLeft . ' jam ' . $remainMinutes . ' menit.',
                    ]);
            }
        }

        $alreadyExists = Attendance::where('user_id', auth()->id())
            ->where('status', $status)
            ->whereDate('attended_at', $today)
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'status' => 'Absensi ' . $statusLabel . ' untuk hari ini sudah tercatat.',
                ]);
        }

        if (in_array($status, ['in', 'out'], true)) {
            if ($data['latitude'] === null || $data['longitude'] === null) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'latitude' => 'Lokasi wajib diisi untuk absensi masuk/pulang.',
                    ]);
            }

            $nearestArea = $this->findNearestOfficeArea((float) $data['latitude'], (float) $data['longitude']);

            if (! $nearestArea['inside']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'latitude' => 'Absensi gagal: Anda berada ' . round($nearestArea['distance']) . ' meter dari titik ' . $nearestArea['name'] . '. Batas maksimal wilayah ini adalah ' . $nearestArea['radius'] . ' meter.',
                    ]);
            }
        } else {
            // WFH tidak memerlukan geotag lokasi
            $data['latitude'] = null;
            $data['longitude'] = null;
            $data['address'] = null;
        }

        // Validasi photo capture - harus dari kamera real-time
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            
            // Check if file is recently captured (within last 15 minutes)
            $fileModifiedTime = filemtime($photoFile->getRealPath());
            $currentTime = time();
            $timeDiffSeconds = $currentTime - $fileModifiedTime;
            
            // Reject jika file lebih lama dari 15 menit
            if ($timeDiffSeconds > 900) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Foto harus diambil langsung dari kamera real-time. File ini terlihat upload manual atau terlalu lama.',
                    ]);
            }
            
            $data['photo_path'] = $photoFile->store('attendances', 'public');
        }

        $data['address'] = $data['address'] ?? null;
        $data['notes'] = $data['notes'] ?? null;

        $data['attended_at'] = now();
        $data['user_id'] = auth()->id();

        Attendance::create($data);

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * Hitung jarak dua titik koordinat (meter) menggunakan formula Haversine.
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * asin(sqrt($a));
    }

    private function findNearestOfficeArea(float $latitude, float $longitude): array
    {
        $officeAreas = config('attendance.office_areas', []);

        if (empty($officeAreas)) {
            $defaultRadius = 500;
            return [
                'name' => 'Kantor',
                'distance' => INF,
                'radius' => $defaultRadius,
                'inside' => false,
            ];
        }

        $nearest = null;

        foreach ($officeAreas as $area) {
            $areaLat = (float) ($area['latitude'] ?? 0);
            $areaLng = (float) ($area['longitude'] ?? 0);
            $areaRadius = (int) ($area['radius'] ?? 500);
            $distance = $this->haversineDistance($areaLat, $areaLng, $latitude, $longitude);

            if ($nearest === null || $distance < $nearest['distance']) {
                $nearest = [
                    'name' => (string) ($area['name'] ?? 'Wilayah Kantor'),
                    'distance' => $distance,
                    'radius' => $areaRadius,
                    'inside' => $distance <= $areaRadius,
                ];
            }
        }

        return $nearest;
    }

    private function isWithinAllowedTime(string $status, $now): bool
    {
        $rule = config('attendance.time_rules.' . $status, []);
        $start = $rule['start'] ?? null;
        $end = $rule['end'] ?? null;

        if (! $start || ! $end) {
            return true;
        }

        $current = $now->copy();
        $startTime = $now->copy()->setTimeFromTimeString($start . ':00');
        $endTime = $now->copy()->setTimeFromTimeString($end . ':00');

        return $current->betweenIncluded($startTime, $endTime);
    }
}

