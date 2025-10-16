<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class WebmasterRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar permissões do Changelog
        $changelogPermissions = [
            'changelogs.view' => 'Visualizar atualizações',
            'changelogs.create' => 'Criar atualizações',
            'changelogs.edit' => 'Editar atualizações',
            'changelogs.delete' => 'Excluir atualizações',
        ];

        foreach ($changelogPermissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web']
            );
        }

        // Criar role Webmaster
        $webmaster = Role::firstOrCreate(
            ['name' => 'webmaster'],
            ['guard_name' => 'web']
        );

        // Dar TODAS as permissões para o webmaster
        $allPermissions = Permission::all();
        $webmaster->syncPermissions($allPermissions);

        $this->command->info('✓ Role Webmaster criada com sucesso!');
        $this->command->info('  Total de permissões atribuídas: ' . $allPermissions->count());
    }
}

