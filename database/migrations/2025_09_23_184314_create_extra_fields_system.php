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
        // Tabela principal de campos
        Schema::create('extra_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // text, textarea, select, radio, checkbox, number, date, etc.
            $table->json('settings')->nullable(); // Configurações específicas do campo
            $table->json('validation_rules')->nullable(); // Regras de validação
            $table->json('pricing_rules')->nullable(); // Regras de preço
            $table->json('conditional_logic')->nullable(); // Lógica condicional
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Tabela de opções dos campos
        Schema::create('extra_field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extra_field_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->string('label');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('price_type')->default('fixed'); // fixed, percentage, per_unit, per_area
            $table->json('settings')->nullable(); // Configurações específicas da opção
            $table->json('conditional_logic')->nullable(); // Lógica condicional
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Tabela de associação produto-campo
        Schema::create('product_extra_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('extra_field_id')->constrained()->onDelete('cascade');
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('field_settings')->nullable(); // Configurações específicas para este produto
            $table->timestamps();
            
            // Evitar duplicatas
            $table->unique(['product_id', 'extra_field_id']);
        });

        // Tabela de seções (para agrupar campos)
        Schema::create('extra_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Tabela de associação produto-seção
        Schema::create('product_extra_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('extra_section_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Evitar duplicatas
            $table->unique(['product_id', 'extra_section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_extra_sections');
        Schema::dropIfExists('extra_sections');
        Schema::dropIfExists('product_extra_fields');
        Schema::dropIfExists('extra_field_options');
        Schema::dropIfExists('extra_fields');
    }
};