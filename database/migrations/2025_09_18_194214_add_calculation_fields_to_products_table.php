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
        Schema::table('products', function (Blueprint $table) {
            // Campos para cálculo de preços
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null');
            $table->json('manufacturing_processes')->nullable(); // IDs dos processos de fabricação
            $table->decimal('width', 8, 2)->nullable(); // Largura em cm
            $table->decimal('height', 8, 2)->nullable(); // Altura em cm
            $table->decimal('thickness', 8, 2)->nullable(); // Espessura em mm
            $table->decimal('weight', 8, 3)->nullable(); // Peso em kg
            $table->string('calculation_type')->default('manual'); // manual, area, volume, weight, custom
            $table->json('custom_formula')->nullable(); // Fórmula customizada para cálculos
            $table->decimal('labor_cost', 10, 2)->default(0); // Custo de mão de obra
            $table->decimal('margin_percentage', 5, 2)->default(30); // Margem de lucro
            $table->boolean('auto_calculate_price')->default(false); // Se deve calcular preço automaticamente
            $table->decimal('min_price', 10, 2)->nullable(); // Preço mínimo
            $table->decimal('max_price', 10, 2)->nullable(); // Preço máximo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->dropColumn([
                'material_id',
                'manufacturing_processes',
                'width',
                'height',
                'thickness',
                'weight',
                'calculation_type',
                'custom_formula',
                'labor_cost',
                'margin_percentage',
                'auto_calculate_price',
                'min_price',
                'max_price'
            ]);
        });
    }
};