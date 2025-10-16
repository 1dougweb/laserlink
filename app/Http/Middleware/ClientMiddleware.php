<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
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
        
        // Verificar se NÃO é admin
        $isAdmin = false;
        
        // Verificar se é admin por email ou ID
        if ($user->id === 1 || $user->email === 'admin@laserlink.com') {
            $isAdmin = true;
        }
        
        // Se o método hasRole existir, verificar roles de admin
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole(['admin', 'vendedor'])) {
                $isAdmin = true;
            }
        }

        // Se for admin, redirecionar para área admin
        if ($isAdmin) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
