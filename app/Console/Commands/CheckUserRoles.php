<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserRoles extends Command
{
    protected $signature = 'user:check-roles {email?}';
    protected $description = 'Verificar roles de um usuário';

    public function handle(): int
    {
        $email = $this->argument('email');
        
        if ($email) {
            // Verificar usuário específico
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("❌ Usuário não encontrado: {$email}");
                return Command::FAILURE;
            }
            
            $this->displayUserInfo($user);
        } else {
            // Listar todos os admins
            $this->info('👥 Usuários Administrativos:');
            $this->newLine();
            
            $users = User::all();
            $adminCount = 0;
            
            foreach ($users as $user) {
                if (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'vendedor', 'gerente'])) {
                    $this->displayUserInfo($user);
                    $adminCount++;
                }
            }
            
            if ($adminCount === 0) {
                $this->warn('⚠️  Nenhum usuário administrativo encontrado!');
            } else {
                $this->newLine();
                $this->info("✅ Total: {$adminCount} usuário(s) administrativo(s)");
            }
        }
        
        return Command::SUCCESS;
    }
    
    private function displayUserInfo(User $user): void
    {
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->implode(', ') : 'Nenhuma';
        
        $this->line("📧 {$user->email}");
        $this->line("   Nome: {$user->name}");
        $this->line("   ID: {$user->id}");
        $this->line("   Roles: {$roles}");
        $this->line("   Pode acessar /admin: " . ($user->hasRole(['admin', 'vendedor', 'gerente']) ? '✅ SIM' : '❌ NÃO'));
        $this->newLine();
    }
}

