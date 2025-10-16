<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('code')->unique(); // código/SKU do material
            $table->string('category')->nullable(); // Acrílico, MDF, Tintas, etc
            $table->enum('unit', ['m2', 'kg', 'l', 'un', 'ml', 'g'])->default('un'); // unidade de medida
            $table->decimal('stock_quantity', 10, 3)->default(0); // quantidade em estoque (com 3 decimais para precisão)
            $table->decimal('stock_min', 10, 3)->default(0); // estoque mínimo
            $table->decimal('stock_max', 10, 3)->nullable(); // estoque máximo
            $table->decimal('unit_cost', 10, 2)->default(0); // custo por unidade
            $table->text('description')->nullable();
            $table->text('specifications')->nullable(); // especificações técnicas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};