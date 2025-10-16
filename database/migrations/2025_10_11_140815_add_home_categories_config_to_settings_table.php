<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar configuração para categorias da home
        \App\Models\Setting::updateOrCreate(
            ['key' => 'home_categories'],
            [
                'value' => json_encode([
                    [
                        'category_id' => 1,
                        'title' => 'Comunicação Visual',
                        'image' => null,
                        'order' => 1
                    ],
                    [
                        'category_id' => 2,
                        'title' => 'Premiações',
                        'image' => null,
                        'order' => 2
                    ],
                    [
                        'category_id' => 3,
                        'title' => 'Brindes Corporativos',
                        'image' => null,
                        'order' => 3
                    ],
                    [
                        'category_id' => 4,
                        'title' => 'Displays',
                        'image' => null,
                        'order' => 4
                    ],
                    [
                        'category_id' => 5,
                        'title' => 'Caixas',
                        'image' => null,
                        'order' => 5
                    ],
                    [
                        'category_id' => 6,
                        'title' => 'Projetos Sob Medida',
                        'image' => null,
                        'order' => 6
                    ]
                ]),
                'description' => 'Configuração das categorias exibidas na página inicial'
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Setting::where('key', 'home_categories')->delete();
    }
};
