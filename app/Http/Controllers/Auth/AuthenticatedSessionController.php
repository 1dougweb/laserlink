<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autenticar o usuário
        $request->authenticate();

        // Verificar se o usuário autenticado tem role administrativa
        $user = Auth::user();
        $isAdmin = method_exists($user, 'hasRole') && 
                   $user->hasRole(['admin', 'vendedor', 'gerente']);
        
        if ($isAdmin) {
            // Se tiver role administrativa, fazer logout e redirecionar
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'Contas administrativas devem usar o painel em /admin/login',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        
        // Log de sucesso
        \Log::info('Cliente login bem-sucedido', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
