<?php

/**
 * Vercel Serverless Entry Point for Laravel
 */

define('LARAVEL_START', microtime(true));

$storageTmp = '/tmp/storage';
$bootstrapCacheTmp = '/tmp/bootstrap/cache';
$dirs = [
    $storageTmp . '/app/public',
    $storageTmp . '/app/public/attendances',
    $storageTmp . '/app/public/night-reports',
    $storageTmp . '/app/public/profile-photos',
    $storageTmp . '/framework/cache/data',
    $storageTmp . '/framework/sessions',
    $storageTmp . '/framework/views',
    $storageTmp . '/logs',
    $bootstrapCacheTmp,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Copy SQLite database to /tmp (Vercel filesystem is read-only)
$dbTmp = '/tmp/database.sqlite';
if (!file_exists($dbTmp)) {
    $dbSource = __DIR__ . '/../database/database.sqlite';
    if (file_exists($dbSource)) {
        copy($dbSource, $dbTmp);
    } else {
        touch($dbTmp);
    }
}

// Pastikan Laravel menggunakan database SQLite yang sudah disalin ke /tmp
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $dbTmp);

// Arahkan seluruh file cache Laravel ke /tmp agar writable di Vercel.
putenv('APP_CONFIG_CACHE=' . $bootstrapCacheTmp . '/config.php');
putenv('APP_ROUTES_CACHE=' . $bootstrapCacheTmp . '/routes-v7.php');
putenv('APP_EVENTS_CACHE=' . $bootstrapCacheTmp . '/events.php');
putenv('APP_PACKAGES_CACHE=' . $bootstrapCacheTmp . '/packages.php');
putenv('APP_SERVICES_CACHE=' . $bootstrapCacheTmp . '/services.php');
putenv('VIEW_COMPILED_PATH=' . $storageTmp . '/framework/views');

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($storageTmp);

$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

// Always run pending migrations to apply any new tables
try {
    Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
} catch (Throwable $e) {
    // Log but don't block the request
    error_log('Migration error: ' . $e->getMessage());
}

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
