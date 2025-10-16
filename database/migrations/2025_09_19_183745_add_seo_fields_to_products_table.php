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
        Schema::table('products', function (Blueprint $table) {
            // Campos de SEO
            $table->string('meta_title')->nullable()->after('description');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            
            // Campos de imagem
            $table->string('featured_image')->nullable()->after('images');
            $table->json('gallery_images')->nullable()->after('featured_image');
            
            // Campos de conteúdo gerado automaticamente
            $table->text('ai_generated_description')->nullable()->after('meta_keywords');
            $table->text('ai_generated_meta_description')->nullable()->after('ai_generated_description');
            $table->json('ai_generated_keywords')->nullable()->after('ai_generated_meta_description');
            $table->boolean('ai_content_enabled')->default(false)->after('ai_generated_keywords');
            
            // Campos de configuração de preço
            $table->boolean('auto_calculate_enabled')->default(false)->after('ai_content_enabled');
            $table->decimal('base_price_per_m2', 10, 2)->nullable()->after('auto_calculate_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description', 
                'meta_keywords',
                'featured_image',
                'gallery_images',
                'ai_generated_description',
                'ai_generated_meta_description',
                'ai_generated_keywords',
                'ai_content_enabled',
                'auto_calculate_enabled',
                'base_price_per_m2'
            ]);
        });
    }
};