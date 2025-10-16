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
        Schema::create('customizable_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->string('sku')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->json('images')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('calculation_type')->default('area'); // area, unit, custom
            $table->decimal('margin_percentage', 5, 2)->default(30.00);
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->json('customization_options')->nullable();
            $table->json('available_materials')->nullable(); // Array de IDs dos materiais
            $table->json('available_finishes')->nullable(); // Array de acabamentos
            $table->json('available_extras')->nullable(); // Array de extras
            $table->json('text_customization')->nullable(); // Configurações de texto
            $table->json('font_options')->nullable(); // Opções de fonte
            $table->json('color_options')->nullable(); // Opções de cor
            $table->json('adhesive_printing')->nullable(); // Configurações de impressão adesiva
            $table->json('base_support_options')->nullable(); // Opções de base/suporte
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customizable_products');
    }
};
