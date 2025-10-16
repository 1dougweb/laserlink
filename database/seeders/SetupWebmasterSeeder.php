<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SetupWebmasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder configura completamente a role Webmaster e cria o usuário
     */
    public function run(): void
    {
        $this->command->info('🚀 Configurando Webmaster...');
        $this->command->newLine();
        
        // 1. Criar permissões e role
        $this->call(WebmasterRoleSeeder::class);
        $this->command->newLine();
        
        // 2. Criar usuário webmaster
        $this->call(WebmasterUserSeeder::class);
        $this->command->newLine();
        
        $this->command->info('✅ Webmaster configurado com sucesso!');
        $this->command->info('📧 Acesse com: webmaster@laserlink.com.br / webmaster123');
    }
}

