<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Lista todas as roles
     */
    public function indexRoles()
    {
        $roles = Role::withCount('users')->get();
        
        // Filtrar role webmaster se o usuário não for webmaster
        if (!auth()->user()->hasRole('webmaster')) {
            $roles = $roles->filter(function($role) {
                return $role->name !== 'webmaster';
            });
        }
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Exibe formulário de criação de role
     */
    public function createRole()
    {
        $permissions = Permission::all();
        
        // Filtrar permissões de webmaster se o usuário não for webmaster
        if (!auth()->user()->hasRole('webmaster')) {
            $permissions = $permissions->filter(function($permission) {
                return !str_starts_with($permission->name, 'changelogs.');
            });
        }
        
        $permissions = $permissions->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Salva nova role
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'display_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->givePermissionTo($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Função criada com sucesso!');
    }

    /**
     * Exibe formulário de edição de role
     */
    public function editRole(Role $role)
    {
        // Proteger role webmaster
        if ($role->name === 'webmaster' && !auth()->user()->hasRole('webmaster')) {
            abort(403, 'Apenas webmaster pode editar esta role.');
        }
        
        $permissions = Permission::all();
        
        // Filtrar permissões de webmaster se o usuário não for webmaster
        if (!auth()->user()->hasRole('webmaster')) {
            $permissions = $permissions->filter(function($permission) {
                return !str_starts_with($permission->name, 'changelogs.');
            });
        }
        
        $permissions = $permissions->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Atualiza role
     */
    public function updateRole(Request $request, Role $role)
    {
        // Proteger role webmaster
        if ($role->name === 'webmaster' && !auth()->user()->hasRole('webmaster')) {
            abort(403, 'Apenas webmaster pode editar esta role.');
        }
        
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Função atualizada com sucesso!');
    }

    /**
     * Remove role
     */
    public function destroyRole(Role $role)
    {
        // Não permitir deletar roles do sistema e webmaster
        if (in_array($role->name, ['admin', 'vendedor', 'cliente', 'webmaster'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Não é possível deletar funções do sistema!');
        }
        
        // Proteger role webmaster adicionalmente
        if ($role->name === 'webmaster' && !auth()->user()->hasRole('webmaster')) {
            abort(403, 'Apenas webmaster pode excluir esta role.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Função removida com sucesso!');
    }

    /**
     * Lista todas as permissões
     */
    public function indexPermissions()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });
        
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Criar permissões padrão do sistema
     */
    public function seedPermissions()
    {
        $permissions = [
            // Produtos
            'products.view' => 'Ver produtos',
            'products.create' => 'Criar produtos',
            'products.edit' => 'Editar produtos',
            'products.delete' => 'Deletar produtos',
            
            // Categorias
            'categories.view' => 'Ver categorias',
            'categories.create' => 'Criar categorias',
            'categories.edit' => 'Editar categorias',
            'categories.delete' => 'Deletar categorias',
            
            // Pedidos
            'orders.view' => 'Ver pedidos',
            'orders.edit' => 'Editar pedidos',
            'orders.delete' => 'Deletar pedidos',
            
            // Usuários
            'users.view' => 'Ver usuários',
            'users.create' => 'Criar usuários',
            'users.edit' => 'Editar usuários',
            'users.delete' => 'Deletar usuários',
            
            // Configurações
            'settings.view' => 'Ver configurações',
            'settings.edit' => 'Editar configurações',
            
            // Relatórios
            'reports.view' => 'Ver relatórios',
            'reports.export' => 'Exportar relatórios',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissões padrão criadas com sucesso!');
    }
}

