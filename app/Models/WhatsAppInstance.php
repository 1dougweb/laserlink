<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsAppInstance extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_instances';

    protected $fillable = [
        'name',
        'instance_name',
        'purpose',
        'api_key',
        'base_url',
        'qr_code',
        'status',
        'is_active',
        'connected_at',
        'disconnected_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'connected_at' => 'datetime',
        'disconnected_at' => 'datetime',
    ];

    /**
     * Relacionamento com notificações
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(WhatsAppNotification::class, 'whatsapp_instance_id');
    }

    /**
     * Verificar se a instância está conectada
     */
    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }

    /**
     * Obter label da finalidade
     */
    public function getPurposeLabelAttribute(): string
    {
        return match($this->purpose) {
            'orders' => 'Pedidos',
            'promotions' => 'Promoções',
            'support' => 'Suporte',
            default => 'Não definido'
        };
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'connected' => 'bg-green-100 text-green-800',
            'connecting' => 'bg-yellow-100 text-yellow-800',
            'disconnected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Obter label do status
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'connected' => 'Conectado',
            'connecting' => 'Conectando',
            'disconnected' => 'Desconectado',
            default => 'Desconhecido'
        };
    }

    /**
     * Contar instâncias ativas por finalidade
     */
    public static function countActiveByPurpose(string $purpose): int
    {
        return self::where('purpose', $purpose)
                   ->where('is_active', true)
                   ->count();
    }

    /**
     * Verificar se pode criar nova instância (máximo 3)
     */
    public static function canCreateNew(): bool
    {
        return self::where('is_active', true)->count() < 3;
    }

    /**
     * Scope para instâncias ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para instâncias conectadas
     */
    public function scopeConnected($query)
    {
        return $query->where('status', 'connected');
    }

    /**
     * Scope por finalidade
     */
    public function scopeByPurpose($query, string $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    /**
     * Accessor para contar notificações
     */
    public function getNotificationsCountAttribute(): int
    {
        return $this->notifications()->count();
    }

    /**
     * Accessor para contar notificações enviadas
     */
    public function getSentCountAttribute(): int
    {
        return $this->notifications()->where('status', 'sent')->count();
    }

    /**
     * Accessor para contar notificações entregues
     */
    public function getDeliveredCountAttribute(): int
    {
        return $this->notifications()->where('status', 'delivered')->count();
    }

    /**
     * Accessor para contar notificações que falharam
     */
    public function getFailedCountAttribute(): int
    {
        return $this->notifications()->where('status', 'failed')->count();
    }
}
