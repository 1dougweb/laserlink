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
        Schema::create('product_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nome da medida (ex: "Padrão", "Pequeno", "Grande")
            $table->string('description')->nullable(); // Descrição opcional
            $table->decimal('width', 10, 2)->nullable(); // Largura em cm
            $table->decimal('height', 10, 2)->nullable(); // Altura em cm
            $table->decimal('depth', 10, 2)->nullable(); // Profundidade em cm
            $table->decimal('thickness', 10, 2)->nullable(); // Espessura em mm
            $table->decimal('weight', 10, 3)->nullable(); // Peso em kg
            $table->decimal('area', 10, 4)->nullable(); // Área calculada em m²
            $table->decimal('volume', 10, 6)->nullable(); // Volume calculado em m³
            $table->json('custom_attributes')->nullable(); // Atributos customizados
            $table->boolean('is_default')->default(false); // Se é a medida padrão
            $table->boolean('is_active')->default(true); // Se está ativa
            $table->integer('sort_order')->default(0); // Ordem de exibição
            $table->timestamps();
            
            // Índices
            $table->index(['product_id', 'is_active']);
            $table->index(['product_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_measurements');
    }
};