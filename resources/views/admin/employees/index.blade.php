<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Karyawan</title>
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
            --warning: #d97706;
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
            max-width: 1260px;
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

        .heading,
        .toolbar,
        .table-title,
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .heading h1,
        .table-title h2 {
            margin: 0;
        }

        .subtitle,
        .table-title p,
        .welcome {
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
            text-decoration: none;
            cursor: pointer;
            color: #ffffff;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--primary-soft));
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.28);
        }

        .btn-danger {
            background: linear-gradient(135deg, #b91c1c, var(--danger));
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.24);
        }

        .btn-success {
            background: linear-gradient(135deg, #15803d, var(--success));
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.24);
        }

        .btn-warning {
            background: linear-gradient(135deg, #b45309, var(--warning));
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.24);
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
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 700;
        }

        input,
        textarea,
        select {
            width: 100%;
            border: 1px solid #cad8e7;
            border-radius: 12px;
            padding: 11px 12px;
            font: inherit;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-row input {
            width: auto;
        }

        .table-wrap {
            border: 1px solid #dce6f2;
            border-radius: 14px;
            overflow-x: auto;
            background: #ffffff;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 980px;
        }

        th,
        td {
            border-bottom: 1px solid #e4ebf4;
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
            font-size: 0.92rem;
        }

        th {
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-size: 0.8rem;
            background: #f2f7ff;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .status-badge {
            display: inline-flex;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .inline-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .inline-actions form {
            margin: 0;
        }

        .alert-success {
            background: #ebfff4;
            color: #065f46;
            border: 1px solid #9de7bb;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .alert-error {
            background: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .pagination {
            margin-top: 14px;
        }

        .filters {
            border: 1px solid #dce6f2;
            border-radius: 14px;
            padding: 14px;
            background: #f8fbff;
            margin-bottom: 16px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .photo-thumb {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            object-fit: cover;
            border: 1px solid #dbe6f3;
        }

        .photo-preview {
            margin-top: 8px;
            width: 74px;
            height: 74px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #dce6f2;
        }

        @media (max-width: 900px) {
            .stats,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
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
                    <a href="{{ route('admin.employees.index') }}" style="background:rgba(255,255,255,0.28);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.5);">&#128101; Data Karyawan</a>
                    <a href="{{ route('admin.night-reports.index') }}" style="background:rgba(255,255,255,0.18);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.3);">Laporan Satpam</a>
                    <a href="{{ route('admin.reports.monthly') }}" style="background:rgba(255,255,255,0.18);color:#fff;padding:7px 16px;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;border:1px solid rgba(255,255,255,0.3);">&#128202; Rekap Laporan</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="background:#dc2626;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.88rem;font-weight:600;border:none;cursor:pointer;">Logout</button>
                    </form>
                </div>
            </div>

            <div class="heading">
                <div>
                    <h1>Admin - Data Karyawan</h1>
                    <p class="subtitle">Kelola akun user dan profil karyawan dalam satu halaman.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <strong>Terjadi kesalahan input.</strong>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <div class="toolbar">
                <div class="welcome">Selamat datang, {{ auth()->user()->name }} (Admin)!</div>
                <div class="actions">
                    <a href="{{ route('admin.employees.export', request()->query()) }}" class="btn btn-success">Export Excel Karyawan</a>
                </div>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-label">Total Karyawan</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Karyawan Aktif</div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Karyawan Nonaktif</div>
                    <div class="stat-value">{{ $stats['inactive'] }}</div>
                </div>
            </div>

            <div class="filters">
                <form method="GET" action="{{ route('admin.employees.index') }}">
                    <div class="filters-grid">
                        <div class="field">
                            <label for="search">Cari</label>
                            <input id="search" name="search" type="text" value="{{ $search }}" placeholder="Nama, email, kode, jabatan, telepon">
                        </div>
                        <div class="field">
                            <label for="department_filter">Departemen</label>
                            <select id="department_filter" name="department">
                                <option value="">Semua departemen</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="status_filter">Status</label>
                            <select id="status_filter" name="status">
                                <option value="" {{ $status === '' ? 'selected' : '' }}>Semua status</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="actions">
                            <button type="submit" class="btn">Terapkan</button>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-warning">Reset</a>
                        </div>
                    </div>
                </form>
                <div class="welcome">Menampilkan {{ $stats['filtered'] }} data sesuai filter.</div>
            </div>

            <div class="table-title">
                <div>
                    <h2>{{ $editEmployee ? 'Edit Karyawan' : 'Tambah Karyawan Baru' }}</h2>
                    <p>{{ $editEmployee ? 'Perbarui data akun dan profil karyawan.' : 'Buat akun login user sekaligus data karyawan.' }}</p>
                </div>
                @if($editEmployee)
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-warning">Batal Edit</a>
                @endif
            </div>

            <form method="POST" action="{{ $editEmployee ? route('admin.employees.update', $editEmployee) : route('admin.employees.store') }}" enctype="multipart/form-data">
                @csrf
                @if($editEmployee)
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $editEmployee ? $editEmployee->user->name : '') }}" required>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $editEmployee ? $editEmployee->user->email : '') }}" required>
                    </div>

                    <div class="field">
                        <label for="password">Password {{ $editEmployee ? '(kosongkan jika tidak diubah)' : '' }}</label>
                        <div style="position: relative;">
                            <input id="password" name="password" type="password" {{ $editEmployee ? '' : 'required' }} style="padding-right: 50px; width: 100%;">
                            <button type="button" id="togglePassword" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 12px; font-weight: 600; color: #0ea5e9; padding: 6px 8px; z-index: 10; pointer-events: auto; -webkit-user-select: none; user-select: none;">
                                Show
                            </button>
                        </div>
                    </div>

                    <div class="field">
                        <label for="employee_code">Kode Karyawan</label>
                        <input id="employee_code" name="employee_code" type="text" value="{{ old('employee_code', $editEmployee->employee_code ?? '') }}" required>
                    </div>

                    <div class="field full">
                        <label for="profile_photo">Foto Profil</label>
                        <input id="profile_photo" name="profile_photo" type="file" accept="image/*">
                        @if($editEmployee && $editEmployee->profile_photo_path)
                            <img src="{{ asset('storage/' . $editEmployee->profile_photo_path) }}" class="photo-preview" alt="Foto Profil">
                        @endif
                    </div>

                    <div class="field">
                        <label for="phone">Telepon</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $editEmployee->phone ?? '') }}">
                    </div>

                    <div class="field">
                        <label for="department">Departemen</label>
                        <input id="department" name="department" type="text" value="{{ old('department', $editEmployee->department ?? '') }}">
                    </div>

                    <div class="field">
                        <label for="position">Jabatan</label>
                        <input id="position" name="position" type="text" value="{{ old('position', $editEmployee->position ?? '') }}">
                    </div>

                    <div class="field">
                        <label for="hire_date">Tanggal Masuk</label>
                        <input id="hire_date" name="hire_date" type="date" value="{{ old('hire_date', isset($editEmployee) && $editEmployee->hire_date ? $editEmployee->hire_date->format('Y-m-d') : '') }}">
                    </div>

                    <div class="field full">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" rows="3">{{ old('address', $editEmployee->address ?? '') }}</textarea>
                    </div>
                </div>

                <div class="checkbox-row">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $editEmployee->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active">Karyawan aktif</label>
                </div>

                <div style="margin-top: 16px;">
                    <button type="submit" class="btn btn-success">{{ $editEmployee ? 'Update Karyawan' : 'Simpan Karyawan' }}</button>
                </div>
            </form>

            <div class="table-title" style="margin-top: 28px;">
                <div>
                    <h2>Daftar Karyawan</h2>
                    <p>Semua data karyawan yang tersimpan dan terhubung ke akun login user.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Telepon</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee_code }}</td>
                                <td>
                                    @if($employee->profile_photo_path)
                                        <img src="{{ asset('storage/' . $employee->profile_photo_path) }}" class="photo-thumb" alt="Foto Profil">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($employee->user)->name }}</td>
                                <td>{{ optional($employee->user)->email }}</td>
                                <td>{{ $employee->department ?: '-' }}</td>
                                <td>{{ $employee->position ?: '-' }}</td>
                                <td>{{ $employee->phone ?: '-' }}</td>
                                <td>{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $employee->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="inline-actions">
                                        <a href="{{ route('admin.employees.index', ['edit' => $employee->id]) }}" class="btn btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Hapus data karyawan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center; padding: 18px; color: var(--muted);">Belum ada data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $employees->links() }}</div>
        </div>
    </div>

    <script>
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Use type property instead of setAttribute
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    togglePasswordBtn.textContent = 'Hide';
                } else {
                    passwordInput.type = 'password';
                    togglePasswordBtn.textContent = 'Show';
                }
                // Ensure input stays focused
                passwordInput.focus();
            });
        }
    </script>
</body>
</html>