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
        Schema::table('extra_field_options', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('label');
            $table->string('color_hex')->nullable()->after('image_url'); // Para swatches de cores
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extra_field_options', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'color_hex']);
        });
    }
};
