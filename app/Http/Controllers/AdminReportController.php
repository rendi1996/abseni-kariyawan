<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminReportController extends Controller
{
    public function monthly(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $department = trim((string) $request->get('department', ''));

        $usersQuery = User::whereHas('employee')
            ->with('employee');

        if ($department !== '') {
            $usersQuery->whereHas('employee', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $users = $usersQuery->orderBy('name')->get();

        $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;

        // Get all attendances for this month grouped by user
        $attendances = Attendance::whereMonth('attended_at', $month)
            ->whereYear('attended_at', $year)
            ->get()
            ->groupBy('user_id');

        $reportData = [];

        foreach ($users as $user) {
            $userAttendances = $attendances->get($user->id, collect());

            $totalIn = $userAttendances->where('status', 'in')->count();
            $totalOut = $userAttendances->where('status', 'out')->count();
            $totalWfh = $userAttendances->where('status', 'wfh')->count();
            $totalHadir = $userAttendances->whereIn('status', ['in', 'wfh'])
                ->unique(function ($a) { return $a->attended_at->format('Y-m-d'); })
                ->count();

            // Daily breakdown
            $dailyMap = [];
            foreach ($userAttendances as $a) {
                $day = $a->attended_at->day;
                if (!isset($dailyMap[$day])) {
                    $dailyMap[$day] = [];
                }
                $dailyMap[$day][] = $a->status;
            }

            $reportData[] = [
                'user' => $user,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'total_wfh' => $totalWfh,
                'total_hadir' => $totalHadir,
                'total_absen' => $daysInMonth - $totalHadir,
                'daily' => $dailyMap,
            ];
        }

        $departments = Employee::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Summary stats
        $totalEmployees = $users->count();
        $avgHadir = $totalEmployees > 0
            ? round(collect($reportData)->avg('total_hadir'), 1)
            : 0;

        return view('admin.reports.monthly', compact(
            'reportData', 'departments', 'department',
            'month', 'year', 'daysInMonth',
            'totalEmployees', 'avgHadir'
        ));
    }

    public function yearly(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $department = trim((string) $request->get('department', ''));

        $usersQuery = User::whereHas('employee')
            ->with('employee');

        if ($department !== '') {
            $usersQuery->whereHas('employee', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $users = $usersQuery->orderBy('name')->get();

        // Get all attendances for this year
        $attendances = Attendance::whereYear('attended_at', $year)
            ->get()
            ->groupBy('user_id');

        $reportData = [];

        foreach ($users as $user) {
            $userAttendances = $attendances->get($user->id, collect());

            $monthlyBreakdown = [];
            $yearTotalHadir = 0;

            for ($m = 1; $m <= 12; $m++) {
                $monthAtt = $userAttendances->filter(function ($a) use ($m) {
                    return $a->attended_at->month === $m;
                });

                $hadir = $monthAtt->whereIn('status', ['in', 'wfh'])
                    ->unique(function ($a) { return $a->attended_at->format('Y-m-d'); })
                    ->count();

                $wfh = $monthAtt->where('status', 'wfh')
                    ->unique(function ($a) { return $a->attended_at->format('Y-m-d'); })
                    ->count();

                $monthlyBreakdown[$m] = [
                    'hadir' => $hadir,
                    'wfh' => $wfh,
                ];

                $yearTotalHadir += $hadir;
            }

            $reportData[] = [
                'user' => $user,
                'monthly' => $monthlyBreakdown,
                'year_total' => $yearTotalHadir,
            ];
        }

        $departments = Employee::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        $totalEmployees = $users->count();

        return view('admin.reports.yearly', compact(
            'reportData', 'departments', 'department',
            'year', 'totalEmployees'
        ));
    }

    public function exportMonthly(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $department = trim((string) $request->get('department', ''));

        $monthNames = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $filename = 'rekap_bulanan_' . $monthNames[$month] . '_' . $year . '.xls';

        $data = $this->buildMonthlyData($month, $year, $department);
        $reportData = $data['reportData'];
        $daysInMonth = $data['daysInMonth'];

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($reportData, $daysInMonth, $monthNames, $month, $year) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<tr><th colspan="' . ($daysInMonth + 5) . '" style="font-size:14px;font-weight:bold;">Rekap Kehadiran - ' . $monthNames[$month] . ' ' . $year . '</th></tr>';
            echo '<tr>';
            echo '<th>No</th><th>Nama</th><th>Departemen</th>';
            for ($d = 1; $d <= $daysInMonth; $d++) {
                echo '<th>' . $d . '</th>';
            }
            echo '<th>Hadir</th><th>Absen</th>';
            echo '</tr>';

            foreach ($reportData as $i => $row) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . e($row['user']->name) . '</td>';
                echo '<td>' . e(optional($row['user']->employee)->department ?: '-') . '</td>';
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    if (isset($row['daily'][$d])) {
                        $statuses = $row['daily'][$d];
                        if (in_array('in', $statuses) && in_array('out', $statuses)) {
                            echo '<td style="background:#dcfce7;">V</td>';
                        } elseif (in_array('in', $statuses)) {
                            echo '<td style="background:#dcfce7;">M</td>';
                        } elseif (in_array('out', $statuses)) {
                            echo '<td style="background:#fee2e2;">P</td>';
                        } else {
                            echo '<td>-</td>';
                        }
                    } else {
                        echo '<td>-</td>';
                    }
                }
                echo '<td style="font-weight:bold;">' . $row['total_hadir'] . '</td>';
                echo '<td style="font-weight:bold;">' . $row['total_absen'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportYearly(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $department = trim((string) $request->get('department', ''));

        $filename = 'rekap_tahunan_' . $year . '.xls';
        $monthShort = ['', 'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

        $data = $this->buildYearlyData($year, $department);
        $reportData = $data['reportData'];

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($reportData, $monthShort, $year) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<tr><th colspan="16" style="font-size:14px;font-weight:bold;">Rekap Kehadiran Tahunan - ' . $year . '</th></tr>';
            echo '<tr>';
            echo '<th>No</th><th>Nama</th><th>Departemen</th>';
            for ($m = 1; $m <= 12; $m++) {
                echo '<th>' . $monthShort[$m] . '</th>';
            }
            echo '<th>Total</th>';
            echo '</tr>';

            foreach ($reportData as $i => $row) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . e($row['user']->name) . '</td>';
                echo '<td>' . e(optional($row['user']->employee)->department ?: '-') . '</td>';
                for ($m = 1; $m <= 12; $m++) {
                    $h = $row['monthly'][$m]['hadir'];
                    echo '<td>' . ($h > 0 ? $h : '-') . '</td>';
                }
                echo '<td style="font-weight:bold;">' . $row['year_total'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        };

        return Response::stream($callback, 200, $headers);
    }

    public function printMonthly(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $department = trim((string) $request->get('department', ''));

        $data = $this->buildMonthlyData($month, $year, $department);

        return view('admin.reports.print-monthly', array_merge($data, [
            'month' => $month,
            'year' => $year,
            'department' => $department,
        ]));
    }

    public function printYearly(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $department = trim((string) $request->get('department', ''));

        $data = $this->buildYearlyData($year, $department);

        return view('admin.reports.print-yearly', array_merge($data, [
            'year' => $year,
            'department' => $department,
        ]));
    }

    private function buildMonthlyData(int $month, int $year, string $department): array
    {
        $usersQuery = User::whereHas('employee')->with('employee');

        if ($department !== '') {
            $usersQuery->whereHas('employee', fn($q) => $q->where('department', $department));
        }

        $users = $usersQuery->orderBy('name')->get();
        $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;

        $attendances = Attendance::whereMonth('attended_at', $month)
            ->whereYear('attended_at', $year)
            ->get()
            ->groupBy('user_id');

        $reportData = [];

        foreach ($users as $user) {
            $ua = $attendances->get($user->id, collect());

            $totalWfh = $ua->where('status', 'wfh')->count();
            $totalHadir = $ua->whereIn('status', ['in', 'wfh'])
                ->unique(fn($a) => $a->attended_at->format('Y-m-d'))
                ->count();

            $dailyMap = [];
            foreach ($ua as $a) {
                $dailyMap[$a->attended_at->day][] = $a->status;
            }

            $reportData[] = [
                'user' => $user,
                'total_wfh' => $totalWfh,
                'total_hadir' => $totalHadir,
                'total_absen' => $daysInMonth - $totalHadir,
                'daily' => $dailyMap,
            ];
        }

        return compact('reportData', 'daysInMonth', 'users');
    }

    private function buildYearlyData(int $year, string $department): array
    {
        $usersQuery = User::whereHas('employee')->with('employee');

        if ($department !== '') {
            $usersQuery->whereHas('employee', fn($q) => $q->where('department', $department));
        }

        $users = $usersQuery->orderBy('name')->get();

        $attendances = Attendance::whereYear('attended_at', $year)
            ->get()
            ->groupBy('user_id');

        $reportData = [];

        foreach ($users as $user) {
            $ua = $attendances->get($user->id, collect());
            $monthlyBreakdown = [];
            $yearTotal = 0;

            for ($m = 1; $m <= 12; $m++) {
                $monthAtt = $ua->filter(fn($a) => $a->attended_at->month === $m);
                $hadir = $monthAtt->whereIn('status', ['in', 'wfh'])
                    ->unique(fn($a) => $a->attended_at->format('Y-m-d'))
                    ->count();
                $wfh = $monthAtt->where('status', 'wfh')
                    ->unique(fn($a) => $a->attended_at->format('Y-m-d'))
                    ->count();

                $monthlyBreakdown[$m] = compact('hadir', 'wfh');
                $yearTotal += $hadir;
            }

            $reportData[] = [
                'user' => $user,
                'monthly' => $monthlyBreakdown,
                'year_total' => $yearTotal,
            ];
        }

        return compact('reportData', 'users');
    }
}
