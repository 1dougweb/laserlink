<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WebmasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garantir que a role webmaster existe
        $webmasterRole = Role::firstOrCreate(
            ['name' => 'webmaster'],
            ['guard_name' => 'web']
        );

        // Criar usuário webmaster
        $webmaster = User::firstOrCreate(
            ['email' => 'webmaster@laserlink.com.br'],
            [
                'name' => 'Webmaster',
                'password' => Hash::make('webmaster123'),
                'is_active' => true,
            ]
        );

        // Atribuir role webmaster
        if (!$webmaster->hasRole('webmaster')) {
            $webmaster->assignRole('webmaster');
        }

        $this->command->info('✓ Usuário Webmaster criado com sucesso!');
        $this->command->info('  Email: webmaster@laserlink.com.br');
        $this->command->info('  Senha: webmaster123');
        $this->command->warn('  ⚠️  IMPORTANTE: Altere a senha após o primeiro acesso!');
    }
}
