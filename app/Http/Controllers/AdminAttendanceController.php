<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $dateFrom = (string) $request->get('date_from', '');
        $dateTo = (string) $request->get('date_to', '');

        $attendances = Attendance::with(['user.employee'])->latest()->paginate(12);

        $checkInQuery = Attendance::with(['user.employee'])
            ->where('status', 'in');

        if ($search !== '') {
            $checkInQuery->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($department !== '') {
            $checkInQuery->whereHas('user.employee', function ($employeeQuery) use ($department) {
                $employeeQuery->where('department', $department);
            });
        }

        if ($dateFrom !== '') {
            $checkInQuery->whereDate('attended_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $checkInQuery->whereDate('attended_at', '<=', $dateTo);
        }

        $todayCheckIns = (clone $checkInQuery)
            ->whereDate('attended_at', today())
            ->orderByDesc('attended_at')
            ->get()
            ->unique('user_id')
            ->values();

        $checkInReport = (clone $checkInQuery)
            ->orderByDesc('attended_at')
            ->paginate(12, ['*'], 'report_page')
            ->appends($request->query());

        $departments = Employee::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.attendance.index', compact('attendances', 'todayCheckIns', 'checkInReport', 'departments', 'search', 'department', 'dateFrom', 'dateTo'));
    }

    public function exportCsv(Request $request)
    {
        $filename = 'laporan_pegawai_masuk_' . now()->format('Ymd_His') . '.xls';
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $dateFrom = (string) $request->get('date_from', '');
        $dateTo = (string) $request->get('date_to', '');

        $query = Attendance::with(['user.employee'])
            ->where('status', 'in');

        if ($search !== '') {
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($department !== '') {
            $query->whereHas('user.employee', function ($employeeQuery) use ($department) {
                $employeeQuery->where('department', $department);
            });
        }

        if ($dateFrom !== '') {
            $query->whereDate('attended_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('attended_at', '<=', $dateTo);
        }

        $attendances = $query
            ->orderByDesc('attended_at')
            ->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        $rows = $attendances->map(function ($attendance, $index) {
            return [
                $index + 1,
                $attendance->user ? $attendance->user->name : 'Guest',
                $attendance->user ? $attendance->user->email : '-',
                optional(optional($attendance->user)->employee)->department ?: '-',
                optional(optional($attendance->user)->employee)->position ?: '-',
                $attendance->attended_at ? $attendance->attended_at->format('Y-m-d') : '-',
                $attendance->attended_at ? $attendance->attended_at->format('H:i:s') : '-',
                $attendance->latitude ?: '-',
                $attendance->longitude ?: '-',
                $attendance->notes ?: '-',
            ];
        });

        $callback = function () use ($rows) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Nama Pegawai</th>';
            echo '<th>Email</th>';
            echo '<th>Departemen</th>';
            echo '<th>Jabatan</th>';
            echo '<th>Tanggal</th>';
            echo '<th>Jam Masuk</th>';
            echo '<th>Latitude</th>';
            echo '<th>Longitude</th>';
            echo '<th>Keterangan</th>';
            echo '</tr>';

            foreach ($rows as $row) {
                echo '<tr>';

                foreach ($row as $cell) {
                    echo '<td>' . e($cell) . '</td>';
                }

                echo '</tr>';
            }

            echo '</table>';
        };

        return Response::stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $search     = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $dateFrom   = (string) $request->get('date_from', '');
        $dateTo     = (string) $request->get('date_to', '');

        $query = Attendance::with(['user.employee']);

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
            $query->whereDate('attended_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('attended_at', '<=', $dateTo);
        }

        $attendances = $query->orderByDesc('attended_at')->get();

        return view('admin.attendance.print', compact('attendances', 'search', 'department', 'dateFrom', 'dateTo'));
    }
}
