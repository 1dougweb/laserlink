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
        // Remover foreign keys primeiro (se existir)
        if (Schema::hasTable('order_items')) {
            try {
                Schema::table('order_items', function (Blueprint $table) {
                    $table->dropForeign(['product_id']);
                });
            } catch (\Exception $e) {
                // Foreign key já foi removida ou não existe
            }
        }
        
        // Recriar tabela products simplificada
        Schema::dropIfExists('products');
        
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->unique();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->json('images')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('attributes')->nullable();
            $table->json('custom_attributes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Nota: Não adicionamos foreign key em order_items.product_id pois ele é string
        // e permite referências flexíveis (pode armazenar SKU ou outras identificações)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};