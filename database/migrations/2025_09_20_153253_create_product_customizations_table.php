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
        Schema::create('product_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customizable_product_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('thickness', 5, 2);
            $table->string('finish')->nullable();
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->string('text_content')->nullable();
            $table->string('font_family')->nullable();
            $table->integer('font_size')->nullable();
            $table->string('text_color')->nullable();
            $table->string('adhesive_printing')->nullable();
            $table->decimal('adhesive_area', 8, 4)->default(0);
            $table->json('extras')->nullable();
            $table->string('base_support')->nullable();
            $table->text('custom_notes')->nullable();
            $table->decimal('calculated_price', 10, 2);
            $table->json('price_breakdown')->nullable();
            $table->decimal('weight', 8, 3)->default(0);
            $table->decimal('area', 8, 4)->default(0);
            $table->decimal('volume', 10, 6)->default(0);
            $table->boolean('is_quote_request')->default(false);
            $table->string('status')->default('draft'); // draft, saved, quoted, ordered
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customizations');
    }
};
