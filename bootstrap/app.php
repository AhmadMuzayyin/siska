<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\VerifyRfidDeviceKey;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'rfid.device' => VerifyRfidDeviceKey::class,
        ]);

        // Tambahkan Security Headers ke semua web request
        $middleware->web(append: [
            SecurityHeaders::class,
        ]);

        // Trusted proxies: '*' untuk cloud/shared hosting.
        // Di production dengan IP proxy tetap, set TRUSTED_PROXIES=10.0.0.1,10.0.0.2
        $trustedProxies = (string) env('TRUSTED_PROXIES', '*');
        $middleware->trustProxies(
            at: $trustedProxies === '*' ? '*' : explode(',', $trustedProxies),
            headers: Request::HEADER_X_FORWARDED_FOR |
                     Request::HEADER_X_FORWARDED_HOST |
                     Request::HEADER_X_FORWARDED_PORT |
                     Request::HEADER_X_FORWARDED_PROTO
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
