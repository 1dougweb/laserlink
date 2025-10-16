<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Redirect to Google. Optionally store an intent (login|register)
    public function redirectToGoogle(Request $request): RedirectResponse
    {
        $intent = $request->query('intent', 'login');
        session(['oauth_intent' => $intent]);
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            // Verificar se é erro de configuração
            if (str_contains($e->getMessage(), 'Client ID')) {
                return Redirect::route('login')->with('error', 'Erro de configuração do Google OAuth. Entre em contato com o suporte.');
            }
            
            return Redirect::route('login')->with('error', 'Falha ao autenticar com Google. Tente novamente.');
        }

        try {
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (!$user) {
                // Create user on first sign-in
                $user = User::create([
                    'name' => $googleUser->getName() ?: ($googleUser->user['given_name'] ?? 'Usuário'),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(str()->random(32)),
                ]);
                
                // Atribuir role de cliente por padrão para novos usuários
                try {
                    $user->assignRole('cliente');
                } catch (\Throwable $e) {
                    Log::warning('Failed to assign role to user', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
                
                Log::info('New user created via Google OAuth', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            } else {
                // Link google_id if missing
                $updated = false;
                
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->getId();
                    $updated = true;
                }
                if (empty($user->google_avatar)) {
                    $user->google_avatar = $googleUser->getAvatar();
                    $updated = true;
                }
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $updated = true;
                }
                
                if ($updated) {
                    $user->save();
                    Log::info('User Google OAuth data updated', [
                        'user_id' => $user->id,
                    ]);
                }
            }

            Auth::login($user, true);

            $intent = session('oauth_intent', 'login');
            session()->forget('oauth_intent');

            if ($intent === 'register') {
                // After first Google sign-up, send to profile page to complete data
                return Redirect::route('profile.edit')->with('success', 'Bem-vindo! Complete seu cadastro.');
            }

            return Redirect::intended('/')->with('success', 'Login realizado com sucesso!');
            
        } catch (\Throwable $e) {
            Log::error('Error processing Google OAuth user', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return Redirect::route('login')->with('error', 'Erro ao processar autenticação. Tente novamente.');
        }
    }
}


