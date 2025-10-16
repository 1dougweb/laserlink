<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar configurações de redes sociais
        $socialMedia = [
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_linkedin' => '',
            'social_youtube' => '',
            'social_tiktok' => '',
            'social_pinterest' => '',
        ];

        foreach ($socialMedia as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'social_facebook',
            'social_instagram',
            'social_twitter',
            'social_linkedin',
            'social_youtube',
            'social_tiktok',
            'social_pinterest',
        ];

        Setting::whereIn('key', $keys)->delete();
    }
};

