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
        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do campo (ex: "Material", "Espessura")
            $table->string('slug')->unique();
            $table->string('type'); // select, radio, checkbox, text, number, textarea
            $table->text('description')->nullable();
            $table->json('settings')->nullable(); // Configurações específicas do campo
            $table->json('validation_rules')->nullable(); // Regras de validação
            $table->json('pricing_rules')->nullable(); // Regras de preço
            $table->json('dependencies')->nullable(); // Dependências de outros campos
            $table->boolean('is_required')->default(false);
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
        Schema::dropIfExists('dynamic_fields');
    }
};