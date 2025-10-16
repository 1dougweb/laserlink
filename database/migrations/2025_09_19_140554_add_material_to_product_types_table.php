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
        Schema::table('product_types', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable()->after('description')->constrained()->onDelete('set null');
            $table->decimal('base_price_per_m2', 10, 2)->nullable()->after('material_id'); // Preço base por m²
            $table->json('thickness_prices')->nullable()->after('base_price_per_m2'); // Preços específicos por espessura
            $table->decimal('labor_cost', 10, 2)->default(0)->after('thickness_prices'); // Custo de mão de obra padrão
            $table->decimal('margin_percentage', 5, 2)->default(40)->after('labor_cost'); // Margem padrão
            $table->text('pricing_notes')->nullable()->after('margin_percentage'); // Observações sobre preços
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->dropColumn([
                'material_id',
                'base_price_per_m2',
                'thickness_prices',
                'labor_cost',
                'margin_percentage',
                'pricing_notes'
            ]);
        });
    }
};