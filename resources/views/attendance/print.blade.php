<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        h1 { text-align: center; }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <h1>Data Absensi</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->id }}</td>
                <td>{{ $attendance->attended_at ? $attendance->attended_at->toDateTimeString() : '-' }}</td>
                <td>{{ $attendance->status }}</td>
                <td>{{ $attendance->latitude ?: '-' }}</td>
                <td>{{ $attendance->longitude ?: '-' }}</td>
                <td>{{ $attendance->notes ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>