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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'kiemtraadmin' => \App\Http\Middleware\Kiemtraadmin::class,
            'kiemtravaitro' => \App\Http\Middleware\Kiemtravaitro::class,
            'kiemtrakhachhang' => \App\Http\Middleware\Kiemtrakhachhang::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
