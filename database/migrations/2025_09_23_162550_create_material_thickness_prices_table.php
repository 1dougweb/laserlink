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
        Schema::create('material_thickness_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('thickness', 8, 2); // Espessura em mm
            $table->decimal('price_per_m2', 10, 2); // Preço por m² para esta espessura
            $table->decimal('minimum_area', 8, 4)->default(0); // Área mínima para este preço
            $table->decimal('setup_cost', 10, 2)->default(0); // Custo fixo de setup/preparação
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices
            $table->unique(['material_id', 'thickness']);
            $table->index(['material_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_thickness_prices');
    }
};