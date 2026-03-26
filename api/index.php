<?php

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

$dbTmp = '/tmp/database.sqlite';
if (!file_exists($dbTmp)) {
    $dbSource = __DIR__ . '/../database/database.sqlite';
    if (file_exists($dbSource)) {
        copy($dbSource, $dbTmp);
    } else {
        touch($dbTmp);
    }
}

putenv('APP_ENV=production');
putenv('APP_DEBUG=false');
putenv('APP_KEY=base64:Ss0YPE25VtW6i+vknKKMXnXLsxK/gWey835hWY0W0Hg=');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $dbTmp);
putenv('VIEW_COMPILED_PATH=' . $storageTmp . '/framework/views');

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    echo 'Missing vendor/autoload.php';
    return;
}

require $autoload;

$bootstrap = __DIR__ . '/../bootstrap/app.php';
if (!file_exists($bootstrap)) {
    http_response_code(500);
    echo 'Missing bootstrap/app.php';
    return;
}

$app = require_once $bootstrap;
$app->useStoragePath($storageTmp);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

$response->send();
$kernel->terminate($request, $response);