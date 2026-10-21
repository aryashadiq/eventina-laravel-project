<?php

use Illuminate\Http\Request;

/*
 * Vercel serverless entry point.
 *
 * Only /tmp is writable on Vercel, and the "env" block of vercel.json is not
 * guaranteed to reach the function, so the production defaults live here.
 * Anything set in the Vercel dashboard (secrets, overrides) still wins.
 */
$defaults = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'LOG_CHANNEL' => 'stderr',
    'DB_CONNECTION' => 'supabase',
    'SUPABASE_DB_PORT' => '6543',
    'SUPABASE_DB_EMULATE_PREPARES' => 'true',
    'SESSION_DRIVER' => 'database',
    'SESSION_SECURE_COOKIE' => 'true',
    'CACHE_STORE' => 'database',
    'QUEUE_CONNECTION' => 'sync',
    'FILESYSTEM_DISK' => 'supabase',
    'LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK' => 'supabase',
];

foreach ($defaults as $key => $value) {
    if (getenv($key) === false && ! isset($_ENV[$key]) && ! isset($_SERVER[$key])) {
        putenv("{$key}={$value}");
        $_ENV[$key] = $_SERVER[$key] = $value;
    }
}

foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'logs', 'app'] as $dir) {
    if (! is_dir("/tmp/storage/{$dir}")) {
        @mkdir("/tmp/storage/{$dir}", 0755, true);
    }
}

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath('/tmp/storage');
$app->handleRequest(Request::capture());
