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
        Schema::create('product_dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('dynamic_field_id')->constrained()->onDelete('cascade');
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('field_settings')->nullable(); // Configurações específicas para este produto
            $table->timestamps();
            
            // Evitar duplicatas
            $table->unique(['product_id', 'dynamic_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_dynamic_fields');
    }
};