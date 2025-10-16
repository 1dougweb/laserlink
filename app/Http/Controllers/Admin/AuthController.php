<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    protected $redirectTo = '/admin';

    public function __construct()
    {
        // Aplicar middleware guest apenas para métodos específicos
        $this->middleware('guest')->only(['showLoginForm', 'login', 'showRegisterForm', 'register']);
    }

    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Processar login (APENAS PARA ADMINS)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Verificar se o usuário existe
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Não conseguimos encontrar uma conta de administrador com este e-mail.',
            ])->onlyInput('email');
        }
        
        // Verificar se o usuário tem permissão de admin (via Spatie Permissions)
        $isAdmin = method_exists($user, 'hasRole') && 
                   $user->hasRole(['admin', 'vendedor', 'gerente']);
        
        if (!$isAdmin) {
            return back()->withErrors([
                'email' => 'Esta conta não possui permissões administrativas. Por favor, use o login de clientes em /login',
            ])->onlyInput('email');
        }

        // Tentar autenticar
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Log de sucesso
            \Log::info('Admin login bem-sucedido', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);
            
            return redirect()->intended($this->redirectTo);
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas estão incorretas.',
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulário de registro
     */
    public function showRegisterForm()
    {
        // Verificar se o registro está habilitado
        if (!Setting::get('admin_register_enabled', false)) {
            abort(404, 'Registro não está habilitado');
        }

        return view('admin.auth.register');
    }

    /**
     * Processar registro
     */
    public function register(Request $request)
    {
        // Verificar se o registro está habilitado
        if (!Setting::get('admin_register_enabled', false)) {
            abort(404, 'Registro não está habilitado');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        Auth::login($user);

        return redirect($this->redirectTo);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

}
