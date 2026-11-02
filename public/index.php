<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Pages kept for guests answer before the application starts (kitloom/site,
// SITE_CACHE_STATIC); the folder is kitloom-site.cache.static.path's.
if (file_exists($cached = __DIR__.'/../vendor/kitloom/site/serve-cached.php')) {
    (require $cached)(__DIR__.'/../storage/framework/cache/kitloom-pages');
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
