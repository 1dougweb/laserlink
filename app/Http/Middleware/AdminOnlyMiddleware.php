<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();
        
        // Verificar se o usuário tem role de admin ou vendedor (Spatie Permission)
        if (!$user->hasRole(['admin', 'vendedor'])) {
            // Se não for admin nem vendedor, redirecionar para a loja
            return redirect()->route('store.index')->with('error', 'Você não tem permissão para acessar a área administrativa.');
        }

        return $next($request);
    }
}
