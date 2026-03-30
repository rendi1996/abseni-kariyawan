<?php

namespace App\Http\Controllers;

use App\Models\NightReport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminNightReportController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $dateFrom = (string) $request->get('date_from', '');
        $dateTo = (string) $request->get('date_to', '');

        $query = NightReport::with(['user.employee'])->latest('reported_at');

        if ($search !== '') {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($department !== '') {
            $query->whereHas('user.employee', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        if ($dateFrom !== '') {
            $query->whereDate('reported_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('reported_at', '<=', $dateTo);
        }

        $reports = $query->paginate(15)->appends($request->query());

        $departments = Employee::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.night-reports.index', compact('reports', 'departments', 'search', 'department', 'dateFrom', 'dateTo'));
    }

    public function exportCsv(Request $request)
    {
        $filename = 'laporan_satpam_' . now()->format('Ymd_His') . '.xls';
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $dateFrom = (string) $request->get('date_from', '');
        $dateTo = (string) $request->get('date_to', '');

        $query = NightReport::with(['user.employee'])->latest('reported_at');

        if ($search !== '') {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($department !== '') {
            $query->whereHas('user.employee', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        if ($dateFrom !== '') {
            $query->whereDate('reported_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('reported_at', '<=', $dateTo);
        }

        $reports = $query->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($reports) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Nama Pegawai</th>';
            echo '<th>Email</th>';
            echo '<th>Departemen</th>';
            echo '<th>Jabatan</th>';
            echo '<th>Tanggal</th>';
            echo '<th>Waktu</th>';
            echo '<th>Laporan</th>';
            echo '<th>Alamat</th>';
            echo '</tr>';

            foreach ($reports as $i => $report) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . e($report->user ? $report->user->name : 'Guest') . '</td>';
                echo '<td>' . e($report->user ? $report->user->email : '-') . '</td>';
                echo '<td>' . e(optional(optional($report->user)->employee)->department ?: '-') . '</td>';
                echo '<td>' . e(optional(optional($report->user)->employee)->position ?: '-') . '</td>';
                echo '<td>' . e($report->reported_at ? $report->reported_at->format('Y-m-d') : '-') . '</td>';
                echo '<td>' . e($report->reported_at ? $report->reported_at->format('H:i:s') : '-') . '</td>';
                echo '<td>' . e($report->report ?: '-') . '</td>';
                echo '<td>' . e($report->address ?: '-') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        };

        return Response::stream($callback, 200, $headers);
    }
}
