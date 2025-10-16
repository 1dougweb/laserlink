<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@laserlink.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $admin->assignRole('admin');
        
        $seller = User::create([
            'name' => 'Vendedor',
            'email' => 'vendedor@laserlink.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $seller->assignRole('vendedor');
        
        // Usuário cliente de teste
        $client = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        // Atribuir role de cliente
        $client->assignRole('cliente');
        
        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@laserlink.com / password');
        $this->command->info('Vendedor: vendedor@laserlink.com / password');
        $this->command->info('Cliente: cliente@teste.com / password');
    }
}