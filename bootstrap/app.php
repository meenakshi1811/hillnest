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
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureUserNotBlocked::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\TrackMetaPageView::class);

        // Set by the Meta Pixel in the browser, not by Laravel, so they are never encrypted.
        $middleware->encryptCookies(except: ['_fbp', '_fbc']);

        $middleware->validateCsrfTokens(except: [
            'webhooks/razorpay',
            // Mail apps' one-click unsubscribe can't send a CSRF token; the URL is signed instead.
            'email/unsubscribe/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
