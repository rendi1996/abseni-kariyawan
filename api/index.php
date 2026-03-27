<?php

/**
 * Vercel Serverless Entry Point for Laravel
 */

define('LARAVEL_START', microtime(true));

$storageTmp = '/tmp/storage';
$dirs = [
    $storageTmp . '/app/public',
    $storageTmp . '/framework/cache/data',
    $storageTmp . '/framework/sessions',
    $storageTmp . '/framework/views',
    $storageTmp . '/logs',
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

putenv('VIEW_COMPILED_PATH=' . $storageTmp . '/framework/views');

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($storageTmp);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
