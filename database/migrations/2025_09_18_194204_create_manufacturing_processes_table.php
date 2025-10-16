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
        Schema::create('manufacturing_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do processo (ex: Corte a Laser, Gravação, Dobra)
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('unit'); // Unidade de cobrança (tempo, área, linear)
            $table->decimal('cost_per_unit', 10, 2); // Custo por unidade
            $table->decimal('setup_cost', 10, 2)->default(0); // Custo de setup
            $table->integer('min_time_minutes')->default(0); // Tempo mínimo
            $table->decimal('time_per_unit', 8, 2)->default(1); // Tempo por unidade (minutos)
            $table->json('material_compatibility')->nullable(); // Materiais compatíveis
            $table->json('complexity_multipliers')->nullable(); // Multiplicadores por complexidade
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
        Schema::dropIfExists('manufacturing_processes');
    }
};