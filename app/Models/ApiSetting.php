<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    protected $fillable = [
        'provider',
        'api_key',
        'model',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    /**
     * Obter configuração ativa de um provedor
     */
    public static function getActiveConfig(string $provider): ?self
    {
        return self::where('provider', $provider)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Ativar configuração de um provedor
     */
    public static function activateProvider(string $provider, string $apiKey, string $model = null, array $config = []): self
    {
        // Desativar todas as configurações do provedor
        self::where('provider', $provider)->update(['is_active' => false]);
        
        // Criar ou atualizar configuração ativa
        return self::updateOrCreate(
            ['provider' => $provider, 'is_active' => true],
            [
                'api_key' => $apiKey,
                'model' => $model,
                'config' => $config,
            ]
        );
    }

    /**
     * Verificar se um provedor está configurado e ativo
     */
    public static function isProviderActive(string $provider): bool
    {
        return self::where('provider', $provider)
                   ->where('is_active', true)
                   ->whereNotNull('api_key')
                   ->exists();
    }
}