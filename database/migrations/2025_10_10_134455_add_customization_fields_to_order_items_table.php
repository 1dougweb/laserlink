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
        Schema::table('order_items', function (Blueprint $table) {
            $table->text('customization')->nullable()->after('product_image');
            $table->decimal('extra_cost', 10, 2)->nullable()->after('customization');
            $table->decimal('base_price', 10, 2)->nullable()->after('extra_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['customization', 'extra_cost', 'base_price']);
        });
    }
};
