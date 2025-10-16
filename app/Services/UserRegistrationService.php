<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Mail\WelcomeNewUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Setting;

class UserRegistrationService
{
    /**
     * Encontra usuário por email
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Cria novo usuário e envia credenciais por email
     */
    public function createUserAndSendCredentials(array $data): User
    {
        // Gerar senha temporária
        $temporaryPassword = Str::random(12);
        
        // Criar usuário
        $user = User::create([
            'name' => $data['customer_name'] ?? $data['name'],
            'email' => $data['customer_email'] ?? $data['email'],
            'phone' => $data['customer_phone'] ?? $data['phone'] ?? null,
            'cpf' => $data['customer_cpf'] ?? $data['cpf'] ?? null,
            'password' => Hash::make($temporaryPassword),
            'email_verified_at' => now(), // Auto-verificar email
        ]);

        // Atribuir role de cliente
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('cliente');
        }

        // Enviar email de boas-vindas com credenciais
        if (Setting::get('send_welcome_email', true)) {
            try {
                Mail::to($user->email)->send(new WelcomeNewUser($user, $temporaryPassword));
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email de boas-vindas: ' . $e->getMessage());
            }
        }

        return $user;
    }

    /**
     * Atualiza dados do endereço do usuário
     */
    public function updateUserAddress(User $user, array $addressData): void
    {
        $user->update([
            'address' => $addressData['street'] ?? null,
            'address_number' => $addressData['number'] ?? null,
            'address_complement' => $addressData['complement'] ?? null,
            'neighborhood' => $addressData['neighborhood'] ?? null,
            'city' => $addressData['city'] ?? null,
            'state' => $addressData['state'] ?? null,
            'zip_code' => $addressData['cep'] ?? null,
        ]);
    }

    /**
     * Verifica se o usuário precisa de senha temporária
     */
    public function needsPasswordReset(User $user): bool
    {
        // Se o usuário foi criado recentemente (nas últimas 24h) e nunca fez login
        return $user->created_at->isToday() && $user->last_login_at === null;
    }
}