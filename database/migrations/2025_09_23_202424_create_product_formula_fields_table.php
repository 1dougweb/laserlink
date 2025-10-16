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
        Schema::create('product_formula_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('formula_field_id')->constrained()->onDelete('cascade');
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('field_settings')->nullable(); // Configurações específicas do campo
            $table->timestamps();
            
            $table->unique(['product_id', 'formula_field_id']);
            $table->index(['product_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_formula_fields');
    }
};