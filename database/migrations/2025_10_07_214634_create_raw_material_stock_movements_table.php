<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_material_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entrada', 'saida', 'ajuste', 'producao', 'devolucao']);
            $table->decimal('quantity', 10, 3); // quantidade (pode ser negativa)
            $table->decimal('stock_before', 10, 3);
            $table->decimal('stock_after', 10, 3);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable(); // quantity * unit_cost
            $table->string('reference')->nullable(); // NF, Pedido, Produção, etc
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_material_stock_movements');
    }
};