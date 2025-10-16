<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableCsrfForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Desabilita CSRF para rotas API
        if ($request->is('api/*')) {
            // Remove o middleware de CSRF para rotas API
            $request->attributes->set('csrf.disabled', true);
        }
        
        return $next($request);
    }
}
