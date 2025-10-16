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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_number')->unique(); // Número do orçamento (ex: ORC-2025-001)
            $table->string('client_name'); // Nome do cliente
            $table->string('client_email')->nullable(); // Email do cliente
            $table->string('client_phone')->nullable(); // Telefone do cliente
            $table->string('client_company')->nullable(); // Empresa do cliente
            $table->text('client_address')->nullable(); // Endereço do cliente
            $table->text('description')->nullable(); // Descrição do orçamento
            $table->json('items'); // Itens do orçamento (produtos/serviços)
            $table->decimal('subtotal', 12, 2)->default(0); // Subtotal sem impostos
            $table->decimal('discount_percentage', 5, 2)->default(0); // Desconto percentual
            $table->decimal('discount_amount', 12, 2)->default(0); // Desconto em valor
            $table->decimal('tax_percentage', 5, 2)->default(0); // Imposto percentual
            $table->decimal('tax_amount', 12, 2)->default(0); // Imposto em valor
            $table->decimal('total', 12, 2)->default(0); // Valor total
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'expired'])->default('draft');
            $table->date('valid_until')->nullable(); // Data de validade
            $table->text('notes')->nullable(); // Observações
            $table->text('terms')->nullable(); // Termos e condições
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuário que criou
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
