<?php

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
        // ============================================================
        // ALIAS de middlewares customizados
        // ============================================================
        $middleware->alias([
            'tenant'       => \App\Modules\Core\Middleware\EnsureTenant::class,
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,
        ]);

        // Aplica o middleware tenant em todas as rotas web autenticadas
        $middleware->web(append: [
            // ... middlewares padrão do Laravel
        ]);

        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
