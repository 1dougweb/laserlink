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
        Schema::table('materials', function (Blueprint $table) {
            // Remove campos antigos que não usaremos mais
            $table->dropColumn(['thickness_multipliers', 'size_multipliers', 'processing_costs']);
            
            // Novos campos para sistema dinâmico
            $table->json('available_thicknesses')->after('density'); // Lista de espessuras disponíveis em mm
            $table->json('weight_per_m2_by_thickness')->nullable()->after('available_thicknesses'); // Peso por m² calculado para cada espessura
            $table->json('config_extras')->nullable()->after('weight_per_m2_by_thickness'); // Configurações extras (corte laser, impressão UV, etc.)
            $table->decimal('density_g_cm3', 8, 3)->after('density'); // Densidade em g/cm³ para cálculos
            $table->text('technical_specs')->nullable()->after('config_extras'); // Especificações técnicas do material
            
            // Atualizar o campo density para ser opcional (alguns materiais podem não ter densidade)
            $table->decimal('density', 8, 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Restaurar campos antigos
            $table->json('thickness_multipliers')->nullable();
            $table->json('size_multipliers')->nullable();
            $table->json('processing_costs')->nullable();
            
            // Remover novos campos
            $table->dropColumn([
                'available_thicknesses',
                'weight_per_m2_by_thickness', 
                'config_extras',
                'density_g_cm3',
                'technical_specs'
            ]);
        });
    }
};