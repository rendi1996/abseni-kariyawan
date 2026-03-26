<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Absensi BKK') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 20%, #ccfbf1 0, transparent 40%),
                radial-gradient(circle at 85% 80%, #dbeafe 0, transparent 40%),
                #f0f7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        @media (max-width: 640px) {
            body {
                align-items: flex-start;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>
