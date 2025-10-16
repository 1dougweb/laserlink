<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StoreMenuItem;

class StoreMenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultMenuItems = [
            [
                'name' => 'Início',
                'url' => '/',
                'icon' => 'bi bi-house',
                'is_active' => true,
                'is_external' => false,
                'sort_order' => 0
            ],
            [
                'name' => 'Categorias',
                'url' => '/categorias',
                'icon' => 'bi bi-grid',
                'is_active' => true,
                'is_external' => false,
                'sort_order' => 1
            ],
            [
                'name' => 'Favoritos',
                'url' => '/favoritos',
                'icon' => 'bi bi-heart',
                'is_active' => true,
                'is_external' => false,
                'sort_order' => 2
            ],
            [
                'name' => 'Carrinho',
                'url' => '/carrinho',
                'icon' => 'bi bi-cart',
                'is_active' => true,
                'is_external' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'Contato',
                'url' => 'https://wa.me/5511999999999',
                'icon' => 'bi bi-whatsapp',
                'is_active' => true,
                'is_external' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($defaultMenuItems as $item) {
            StoreMenuItem::create($item);
        }
    }
}
