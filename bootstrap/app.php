<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.only' => \App\Http\Middleware\AdminOnlyMiddleware::class,
            'client' => \App\Http\Middleware\ClientMiddleware::class,
            'refresh.csrf' => \App\Http\Middleware\RefreshCsrfToken::class,
            'disable.csrf.api' => \App\Http\Middleware\DisableCsrfForApi::class,
            'webmaster' => \App\Http\Middleware\EnsureUserIsWebmaster::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
        
        // API routes não precisam de CSRF protection
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        
        // Desabilita CSRF para rotas API
        $middleware->web(append: [
            \App\Http\Middleware\DisableCsrfForApi::class,
        ]);
        
        // Exceções CSRF para rotas AJAX específicas
        $middleware->validateCsrfTokens(except: [
            'checkout/processar',
            'api/*',
        ]);
        
        // (removido) Rastrear usuários online
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
