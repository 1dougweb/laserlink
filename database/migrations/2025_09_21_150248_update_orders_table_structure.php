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
            // Add user_id column
            $table->foreignId('user_id')->nullable()->after('order_number')->constrained()->onDelete('set null');
            
            // Rename customer_email to make it required
            $table->string('customer_email')->nullable(false)->change();
            
            // Add new shipping address columns
            $table->text('shipping_address')->after('customer_email');
            $table->string('shipping_neighborhood')->after('shipping_address');
            $table->string('shipping_city')->after('shipping_neighborhood');
            $table->string('shipping_state', 2)->after('shipping_city');
            $table->string('shipping_zip', 10)->after('shipping_state');
            $table->string('shipping_complement')->nullable()->after('shipping_zip');
            
            // Rename total to total_amount by dropping and adding
            $table->dropColumn('total');
            $table->decimal('total_amount', 10, 2)->after('notes');
            
            // Add whatsapp_sent_at column
            $table->timestamp('whatsapp_sent_at')->nullable()->after('whatsapp_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove user_id column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            // Make customer_email nullable again
            $table->string('customer_email')->nullable()->change();
            
            // Remove shipping address columns
            $table->dropColumn([
                'shipping_address',
                'shipping_neighborhood', 
                'shipping_city',
                'shipping_state',
                'shipping_zip',
                'shipping_complement'
            ]);
            
            // Rename total_amount back to total by dropping and adding
            $table->dropColumn('total_amount');
            $table->decimal('total', 10, 2)->after('notes');
            
            // Remove whatsapp_sent_at column
            $table->dropColumn('whatsapp_sent_at');
        });
    }
};
