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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do material (ex: Acrílico, MDF, PET)
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('unit'); // Unidade de medida (m², kg, m³, unidade)
            $table->decimal('density', 8, 3)->nullable(); // Densidade (kg/m³)
            $table->decimal('base_price_per_unit', 10, 2); // Preço base por unidade
            $table->json('thickness_multipliers')->nullable(); // Multiplicadores por espessura
            $table->json('size_multipliers')->nullable(); // Multiplicadores por tamanho
            $table->json('processing_costs')->nullable(); // Custos de processamento (corte, gravação, etc.)
            $table->decimal('waste_percentage', 5, 2)->default(10); // Percentual de desperdício
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};