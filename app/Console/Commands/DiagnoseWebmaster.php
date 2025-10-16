<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Changelog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DiagnoseWebmaster extends Command
{
    protected $signature = 'webmaster:diagnose';
    protected $description = 'Diagnostica configuração do Webmaster e Changelog';

    public function handle()
    {
        $this->info('🔍 DIAGNÓSTICO DO WEBMASTER E CHANGELOG');
        $this->newLine();

        // 1. Verificar Role
        $role = Role::where('name', 'webmaster')->first();
        $this->info('1️⃣  Role Webmaster:');
        if ($role) {
            $this->line('   ✅ Existe');
            $this->line('   📊 Permissões: ' . $role->permissions->count());
        } else {
            $this->error('   ❌ NÃO EXISTE - Execute: php artisan db:seed --class=WebmasterRoleSeeder');
        }
        $this->newLine();

        // 2. Verificar Permissões de Changelog
        $permissions = Permission::where('name', 'like', 'changelogs.%')->get();
        $this->info('2️⃣  Permissões de Changelog:');
        if ($permissions->count() > 0) {
            $this->line('   ✅ ' . $permissions->count() . ' permissões criadas');
            foreach ($permissions as $perm) {
                $this->line('      • ' . $perm->name);
            }
        } else {
            $this->error('   ❌ NENHUMA - Execute: php artisan db:seed --class=WebmasterRoleSeeder');
        }
        $this->newLine();

        // 3. Verificar Usuário
        $user = User::where('email', 'webmaster@laserlink.com.br')->first();
        $this->info('3️⃣  Usuário Webmaster:');
        if ($user) {
            $this->line('   ✅ Existe');
            $this->line('   👤 Nome: ' . $user->name);
            $this->line('   📧 Email: ' . $user->email);
            $this->line('   🔐 Roles: ' . $user->getRoleNames()->implode(', '));
            $this->line('   ✅ Tem role webmaster: ' . ($user->hasRole('webmaster') ? 'SIM' : 'NÃO'));
        } else {
            $this->error('   ❌ NÃO EXISTE - Execute: php artisan db:seed --class=WebmasterUserSeeder');
        }
        $this->newLine();

        // 4. Verificar Changelogs
        $changelogs = Changelog::count();
        $this->info('4️⃣  Changelogs:');
        $this->line('   📝 Total: ' . $changelogs);
        if ($changelogs === 0) {
            $this->warn('   ⚠️  Nenhum changelog - Execute: php artisan db:seed --class=ChangelogSeeder');
        }
        $this->newLine();

        // 5. Rotas
        $this->info('5️⃣  Rotas:');
        $this->line('   🌐 /admin/atualizacoes (listagem)');
        $this->line('   ➕ /admin/atualizacoes/criar (criar)');
        $this->newLine();

        // 6. Instruções
        $this->info('📋 COMO TESTAR:');
        $this->line('   1️⃣  Faça LOGOUT do usuário atual');
        $this->line('   2️⃣  Acesse: /admin/login');
        $this->line('   3️⃣  Login: webmaster@laserlink.com.br');
        $this->line('   4️⃣  Senha: webmaster123');
        $this->line('   5️⃣  No sidebar, verá: 🚀 Atualizações');
        $this->newLine();

        $this->info('✅ Diagnóstico concluído!');
    }
}

