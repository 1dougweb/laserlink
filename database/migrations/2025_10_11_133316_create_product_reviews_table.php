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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('customer_name')->nullable(); // Para clientes não autenticados
            $table->string('customer_email')->nullable(); // Para clientes não autenticados
            $table->integer('rating')->unsigned(); // 1-5 estrelas
            $table->string('title')->nullable(); // Título da avaliação
            $table->text('comment'); // Comentário da avaliação
            $table->json('images')->nullable(); // Imagens da avaliação
            $table->boolean('is_verified_purchase')->default(false); // Compra verificada
            $table->boolean('is_approved')->default(false); // Aprovada pelo admin
            $table->integer('helpful_count')->default(0); // Quantas pessoas acharam útil
            $table->timestamps();
            
            // Índices para performance
            $table->index('product_id');
            $table->index('user_id');
            $table->index('is_approved');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
