<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Lista todos os usuários
     */
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Filtrar usuários webmaster se o usuário logado não for webmaster
        if (!auth()->user()->hasRole('webmaster')) {
            $users->getCollection()->transform(function ($users) {
                return $users->filter(function($user) {
                    return !$user->hasRole('webmaster');
                });
            });
        }
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Exibe formulário de criação
     */
    public function create()
    {
        $roles = Role::all();
        
        // Filtrar role webmaster se o usuário não for webmaster
        if (!auth()->user()->hasRole('webmaster')) {
            $roles = $roles->filter(function($role) {
                return $role->name !== 'webmaster';
            });
        }
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Salva novo usuário
     */
    public function store(Request $request)
    {
        // Proteger criação com role webmaster
        if ($request->input('role') === 'webmaster' && !auth()->user()->hasRole('webmaster')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Apenas webmaster pode criar usuários com role webmaster.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'cpf' => $validated['cpf'],
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Exibe formulário de edição
     */
    public function edit(User $user)
    {
        // Proteger edição de usuário webmaster
        if ($user->hasRole('webmaster') && !auth()->user()->hasRole('webmaster')) {
            abort(403, 'Apenas webmaster pode editar usuários webmaster.');
        }
        
        $roles = Role::all();
        
        // Filtrar role webmaster se o usuário não for webmaster
        if (!auth()->user()->hasRole('webmaster')) {
            $roles = $roles->filter(function($role) {
                return $role->name !== 'webmaster';
            });
        }
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Atualiza usuário
     */
    public function update(Request $request, User $user)
    {
        // Proteger edição de usuário webmaster
        if ($user->hasRole('webmaster') && !auth()->user()->hasRole('webmaster')) {
            abort(403, 'Apenas webmaster pode editar usuários webmaster.');
        }
        
        // Proteger atribuição de role webmaster
        if ($request->input('role') === 'webmaster' && !auth()->user()->hasRole('webmaster')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Apenas webmaster pode atribuir a role webmaster.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'role' => 'required|exists:roles,name',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:10',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'cpf' => $validated['cpf'],
            'address' => $validated['address'],
            'address_number' => $validated['address_number'],
            'neighborhood' => $validated['neighborhood'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip_code' => $validated['zip_code'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Log para debug
        \Log::info('Atualizando role do usuário', [
            'user_id' => $user->id,
            'role_antes' => $user->getRoleNames()->toArray(),
            'role_nova' => $validated['role']
        ]);
        
        $user->syncRoles([$validated['role']]);
        
        \Log::info('Role atualizada', [
            'user_id' => $user->id,
            'role_depois' => $user->fresh()->getRoleNames()->toArray()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove usuário
     */
    public function destroy(User $user)
    {
        // Não permitir deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode deletar sua própria conta!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário removido com sucesso!');
    }

    /**
     * Exibe detalhes do usuário
     */
    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }
}

