<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $status = (string) $request->get('status', '');

        $query = Employee::with('user');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('employee_code', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($department !== '') {
            $query->where('department', $department);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $employees = $query->latest()->paginate(10)->appends($request->query());

        $editEmployee = null;

        if ($request->filled('edit')) {
            $editEmployee = Employee::with('user')->findOrFail((int) $request->input('edit'));
        }

        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('is_active', true)->count(),
            'inactive' => Employee::where('is_active', false)->count(),
            'filtered' => $employees->total(),
        ];

        $departments = Employee::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.employees.index', compact('employees', 'editEmployee', 'stats', 'departments', 'search', 'department', 'status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $photoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('employees', 'public')
            : null;

        DB::transaction(function () use ($data, $photoPath) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user',
            ]);

            Employee::create([
                'user_id' => $user->id,
                'employee_code' => $data['employee_code'],
                'profile_photo_path' => $photoPath,
                'phone' => $data['phone'] ?? null,
                'department' => $data['department'] ?? null,
                'position' => $data['position'] ?? null,
                'address' => $data['address'] ?? null,
                'hire_date' => $data['hire_date'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
        });

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'employee_code' => ['required', 'string', 'max:50', Rule::unique('employees', 'employee_code')->ignore($employee->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $photoPath = null;

        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('employees', 'public');
        }

        DB::transaction(function () use ($employee, $data, $photoPath) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            if ($photoPath && $employee->profile_photo_path) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }

            $employee->user()->update($userData);

            $employee->update([
                'employee_code' => $data['employee_code'],
                'profile_photo_path' => $photoPath ?: $employee->profile_photo_path,
                'phone' => $data['phone'] ?? null,
                'department' => $data['department'] ?? null,
                'position' => $data['position'] ?? null,
                'address' => $data['address'] ?? null,
                'hire_date' => $data['hire_date'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
        });

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->profile_photo_path) {
            Storage::disk('public')->delete($employee->profile_photo_path);
        }

        $employee->user()->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $filename = 'data_karyawan_' . now()->format('Ymd_His') . '.xls';

        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $status = (string) $request->get('status', '');

        $query = Employee::with('user');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('employee_code', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($department !== '') {
            $query->where('department', $department);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $employees = $query->orderBy('employee_code')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($employees) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Kode Karyawan</th>';
            echo '<th>Nama</th>';
            echo '<th>Email</th>';
            echo '<th>Foto</th>';
            echo '<th>Telepon</th>';
            echo '<th>Departemen</th>';
            echo '<th>Jabatan</th>';
            echo '<th>Tanggal Masuk</th>';
            echo '<th>Status</th>';
            echo '<th>Alamat</th>';
            echo '</tr>';

            foreach ($employees as $index => $employee) {
                echo '<tr>';
                echo '<td>' . e($index + 1) . '</td>';
                echo '<td>' . e($employee->employee_code) . '</td>';
                echo '<td>' . e(optional($employee->user)->name) . '</td>';
                echo '<td>' . e(optional($employee->user)->email) . '</td>';
                echo '<td>' . e($employee->profile_photo_path ? asset('storage/' . $employee->profile_photo_path) : '-') . '</td>';
                echo '<td>' . e($employee->phone ?: '-') . '</td>';
                echo '<td>' . e($employee->department ?: '-') . '</td>';
                echo '<td>' . e($employee->position ?: '-') . '</td>';
                echo '<td>' . e($employee->hire_date ? $employee->hire_date->format('Y-m-d') : '-') . '</td>';
                echo '<td>' . e($employee->is_active ? 'Aktif' : 'Nonaktif') . '</td>';
                echo '<td>' . e($employee->address ?: '-') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        };

        return Response::stream($callback, 200, $headers);
    }
}