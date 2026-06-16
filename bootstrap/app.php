<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAttendanceLocation;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Đăng ký middleware aliases
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            
            // Middleware của bạn
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'employee' => \App\Http\Middleware\CheckEmployee::class,
            'role' => \App\Http\Middleware\CheckRoleAccess::class,
            'attendance.location' => \App\Http\Middleware\CheckAttendanceLocation::class, // THÊM DÒNG NÀY
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();