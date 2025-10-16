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
        // Primeiro, simplificar tabela products e remover foreign keys
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Remover foreign key constraint se existir
                if (Schema::hasColumn('products', 'material_id')) {
                    try {
                        $table->dropForeign(['material_id']);
                    } catch (\Exception $e) {
                        // Foreign key pode já ter sido removida
                    }
                }
            });
            
            Schema::table('products', function (Blueprint $table) {
                // Remover as colunas complexas que existem
                $columns = ['material_id', 'manufacturing_processes', 'width', 'height', 'thickness', 
                           'weight', 'calculation_type', 'custom_formula', 'labor_cost', 'margin_percentage',
                           'auto_calculate_price', 'min_price', 'max_price', 'ai_generated_description',
                           'ai_generated_meta_description', 'ai_generated_keywords', 'ai_content_enabled',
                           'auto_calculate_enabled', 'base_price_per_m2'];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
        
        // Remover tabelas que têm FK para materials primeiro
        Schema::dropIfExists('product_dynamic_fields');
        Schema::dropIfExists('product_thickness_prices');
        Schema::dropIfExists('product_measurements');
        Schema::dropIfExists('product_pricing_configurations');
        Schema::dropIfExists('product_customizations'); // Tem FK para materials
        Schema::dropIfExists('customizable_products');
        Schema::dropIfExists('product_types'); // Tem FK para materials
        Schema::dropIfExists('material_thickness_prices'); // Tem FK para materials
        
        // Agora sim podemos remover materials
        Schema::dropIfExists('materials');
        Schema::dropIfExists('manufacturing_processes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não implementar rollback para simplificar
    }
};