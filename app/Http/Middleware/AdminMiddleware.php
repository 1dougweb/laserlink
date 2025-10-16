<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Verificar se o usuário tem role de admin ou vendedor (Spatie Permission)
        if (!$user->hasRole(['admin', 'vendedor'])) {
            abort(403, 'Acesso negado. Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}