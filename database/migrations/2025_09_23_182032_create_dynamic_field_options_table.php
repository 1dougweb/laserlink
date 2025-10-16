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
        Schema::create('dynamic_field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_field_id')->constrained()->onDelete('cascade');
            $table->string('value'); // Valor da opção (ex: "2", "3", "5")
            $table->string('label'); // Label exibida (ex: "2mm", "3mm", "5mm")
            $table->text('description')->nullable();
            $table->decimal('price_adjustment', 10, 2)->default(0.00); // Ajuste de preço
            $table->string('price_type')->default('fixed'); // fixed, percentage, per_unit
            $table->json('settings')->nullable(); // Configurações específicas da opção
            $table->json('dependencies')->nullable(); // Dependências de outras opções
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
        Schema::dropIfExists('dynamic_field_options');
    }
};