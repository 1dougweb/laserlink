<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Adicionar apenas campos que não existem
            if (!Schema::hasColumn('products', 'stock_min')) {
                $table->integer('stock_min')->default(0)->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'stock_max')) {
                $table->integer('stock_max')->nullable()->after('stock_min');
            }
            if (!Schema::hasColumn('products', 'track_stock')) {
                $table->boolean('track_stock')->default(true)->after('stock_max');
            }
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->after('category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            }
            if (Schema::hasColumn('products', 'track_stock')) {
                $table->dropColumn('track_stock');
            }
            if (Schema::hasColumn('products', 'stock_max')) {
                $table->dropColumn('stock_max');
            }
            if (Schema::hasColumn('products', 'stock_min')) {
                $table->dropColumn('stock_min');
            }
        });
    }
};
