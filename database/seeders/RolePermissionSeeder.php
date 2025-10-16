<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'dashboard.view',
            'view-dashboard',
            
            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            
            // Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Orders
            'orders.view',
            'orders.edit',
            'orders.delete',
            'view-orders',
            'edit-orders',
            'delete-orders',
            
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Roles & Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            
            // Settings
            'settings.view',
            'settings.edit',
            'view-settings',
            'edit-settings',
            
            // Reports
            'reports.view',
            'view-reports',
            
            // Extra Fields (Campos Extras)
            'extra-fields.view',
            'extra-fields.create',
            'extra-fields.edit',
            'extra-fields.delete',
            
            // Budgets (Orçamentos)
            'budgets.view',
            'budgets.create',
            'budgets.edit',
            'budgets.delete',
            
            // Stock (Estoque)
            'stock.view',
            'stock.create',
            'stock.edit',
            'stock.delete',
            
            // Raw Materials (Matéria Prima)
            'raw-materials.view',
            'raw-materials.create',
            'raw-materials.edit',
            'raw-materials.delete',
            
            // Suppliers (Fornecedores)
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            
            // Blog
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
            
            // Formula Fields
            'formula-fields.view',
            'formula-fields.create',
            'formula-fields.edit',
            'formula-fields.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $sellerRole = Role::firstOrCreate(['name' => 'vendedor']);
        $sellerRole->syncPermissions([
            'view-dashboard',
            'view-categories',
            'create-categories',
            'edit-categories',
            'view-products',
            'create-products',
            'edit-products',
            'view-orders',
            'edit-orders',
            'view-reports',
        ]);

        $clientRole = Role::firstOrCreate(['name' => 'cliente']);
        $clientRole->syncPermissions([
            'view-dashboard',
        ]);
    }
}