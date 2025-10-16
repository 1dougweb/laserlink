<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategoryPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar permissões para categorias
        $permissions = [
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Atribuir permissões ao role admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Atribuir permissões ao role vendedor (apenas view e create)
        $vendedorRole = Role::firstOrCreate(['name' => 'vendedor']);
        $vendedorRole->givePermissionTo(['categories.view', 'categories.create']);
    }
}