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
            if (!Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->constrained()->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null')->after('order_id');
            }
            
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'product_description')) {
                $table->text('product_description')->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('order_items', 'product_price')) {
                $table->decimal('product_price', 10, 2)->after('product_description');
            }
            if (!Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->after('product_price');
            }
            if (!Schema::hasColumn('order_items', 'total_price')) {
                $table->decimal('total_price', 10, 2)->after('quantity');
            }
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('total_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
        });
    }
};
