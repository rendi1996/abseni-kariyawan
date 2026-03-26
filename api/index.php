<?php

/**
 * Vercel Serverless Entry Point for Laravel
 *
 * Vercel uses a read-only filesystem, so all writable paths
 * (views cache, sessions, logs) are redirected to /tmp.
 */

define('LARAVEL_START', microtime(true));

// Create writable directories in /tmp for Vercel's read-only filesystem
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

// Point compiled views to writable /tmp path
putenv('VIEW_COMPILED_PATH=' . $storageTmp . '/framework/views');

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override storage path so Laravel writes to /tmp
$app->useStoragePath($storageTmp);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
