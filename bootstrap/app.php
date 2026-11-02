<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Kitloom\Modules\Modules;
use Kitloom\Sandbox\Bootstrap as SandboxBootstrap;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Modules append their own middleware themselves (redirects — to the web group).
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// A demo: the request's sandbox is the database — named before the modules read it.
// kitloom/sandbox is a dev dependency the demo server requires for real; a site without it skips this.
if (class_exists(SandboxBootstrap::class)) {
    SandboxBootstrap::bootstrap($app);
}

// Modules deactivated on the Modules screen are not loaded (kitloom/modules).
Modules::bootstrap($app);

return $app;
