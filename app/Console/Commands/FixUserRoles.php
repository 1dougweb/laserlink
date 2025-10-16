<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class FixUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atribui a role "cliente" para todos os usuários que não possuem nenhuma role';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Verificando usuários sem roles...');
        
        // Garantir que a role 'cliente' existe
        $clientRole = Role::firstOrCreate(['name' => 'cliente']);
        
        // Buscar usuários sem roles
        $usersWithoutRoles = User::doesntHave('roles')->get();
        
        if ($usersWithoutRoles->isEmpty()) {
            $this->info('✅ Todos os usuários já possuem roles atribuídas!');
            return self::SUCCESS;
        }
        
        $this->info("📋 Encontrados {$usersWithoutRoles->count()} usuário(s) sem roles");
        
        $bar = $this->output->createProgressBar($usersWithoutRoles->count());
        $bar->start();
        
        foreach ($usersWithoutRoles as $user) {
            // Atribuir role de cliente
            $user->assignRole('cliente');
            $this->newLine();
            $this->line("   ✓ Role 'cliente' atribuída para: {$user->name} ({$user->email})");
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Processo concluído com sucesso!');
        
        return self::SUCCESS;
    }
}

