<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Tạm thời tắt CSRF check cho toàn bộ app để test (chỉ dùng trong dev)
        // TODO: Bật lại CSRF và fix đúng cách bằng cách setup CSRF token trong axios
        $middleware->validateCsrfTokens(except: [
            '*', // Tắt tất cả
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
