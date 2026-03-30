<?php

namespace App\Http\Controllers;

use App\Models\NightReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NightReportController extends Controller
{
    public function index()
    {
        $reports = NightReport::where('user_id', auth()->id())
            ->latest('reported_at')
            ->paginate(10);

        $officeAreas = config('attendance.office_areas', []);

        return view('night-report.index', compact('reports', 'officeAreas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report' => 'required|string|max:2000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:4096',
            'photo_base64' => 'nullable|string|max:8000000',
        ]);

        // Simpan foto dari file upload
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');

            $fileModifiedTime = @filemtime($photoFile->getRealPath());
            if ($fileModifiedTime !== false) {
                $timeDiffSeconds = time() - $fileModifiedTime;
                if ($timeDiffSeconds > 900) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['photo' => 'Foto harus diambil langsung dari kamera real-time.']);
                }
            }

            $data['photo_path'] = $photoFile->store('night-reports', 'public');
        } elseif ($request->filled('photo_base64')) {
            $base64 = $request->input('photo_base64');

            if (! preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/', $base64, $matches)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Format foto tidak valid.']);
            }

            $imageData = base64_decode($matches[2], true);
            if ($imageData === false || strlen($imageData) > 4 * 1024 * 1024) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Data foto tidak valid atau terlalu besar.']);
            }

            $imageInfo = @getimagesizefromstring($imageData);
            if (! $imageInfo) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'File bukan gambar yang valid.']);
            }

            $filename = 'night-reports/laporan-' . time() . '-' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, $imageData);
            $data['photo_path'] = $filename;
        }

        unset($data['photo'], $data['photo_base64']);

        $data['reported_at'] = now();
        $data['user_id'] = auth()->id();

        NightReport::create($data);

        return redirect()->back()->with('success', 'Laporan satpam berhasil disimpan.');
    }

    public function print()
    {
        $reports = NightReport::where('user_id', auth()->id())
            ->latest('reported_at')
            ->get();

        return view('night-report.print', compact('reports'));
    }
}
