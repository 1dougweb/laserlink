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
        Schema::create('product_pricing_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome da configuração (ex: "Laser Link 2025")
            $table->string('slug')->unique(); // Slug único
            $table->text('description')->nullable(); // Descrição da configuração
            $table->json('pricing_rules'); // Regras de preços baseadas na planilha
            $table->json('categories_config'); // Configuração por categoria
            $table->json('product_types_config'); // Configuração por tipo de produto
            $table->decimal('base_margin', 5, 2)->default(0); // Margem base (%)
            $table->decimal('min_price', 10, 2)->nullable(); // Preço mínimo
            $table->decimal('max_price', 10, 2)->nullable(); // Preço máximo
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // Configuração padrão
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_pricing_configurations');
    }
};
