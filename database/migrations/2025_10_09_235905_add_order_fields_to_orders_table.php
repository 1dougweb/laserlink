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
        Schema::table('orders', function (Blueprint $table) {
            // Verificar se as colunas já existem antes de adicionar
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->unique()->after('id');
            }
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('order_number');
            }
            
            // Dados do cliente
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->after('customer_email');
            }
            if (!Schema::hasColumn('orders', 'customer_cpf')) {
                $table->string('customer_cpf')->nullable()->after('customer_phone');
            }
            
            // Endereço de entrega
            if (!Schema::hasColumn('orders', 'shipping_cep')) {
                $table->string('shipping_cep')->after('customer_cpf');
            }
            if (!Schema::hasColumn('orders', 'shipping_street')) {
                $table->string('shipping_street')->after('shipping_cep');
            }
            if (!Schema::hasColumn('orders', 'shipping_number')) {
                $table->string('shipping_number')->after('shipping_street');
            }
            if (!Schema::hasColumn('orders', 'shipping_complement')) {
                $table->string('shipping_complement')->nullable()->after('shipping_number');
            }
            if (!Schema::hasColumn('orders', 'shipping_neighborhood')) {
                $table->string('shipping_neighborhood')->after('shipping_complement');
            }
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->after('shipping_neighborhood');
            }
            if (!Schema::hasColumn('orders', 'shipping_state')) {
                $table->string('shipping_state')->after('shipping_city');
            }
            
            // Valores
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->after('shipping_state');
            }
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->after('shipping_cost');
            }
            
            // Status e observações
            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('total');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'whatsapp_message')) {
                $table->text('whatsapp_message')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
