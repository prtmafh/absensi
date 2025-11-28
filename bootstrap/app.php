<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'elapsed_time' => \App\Http\Middleware\RenderTimeMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {

        $schedule->command('absensi:mark-absent')->dailyAt('23:00');

        $schedule->command('absensi:mark-absent-monthly')->monthlyOn(1, '01:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
