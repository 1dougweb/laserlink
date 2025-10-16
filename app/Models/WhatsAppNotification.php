<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsAppNotification extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_notifications';

    protected $fillable = [
        'whatsapp_instance_id',
        'recipient_phone',
        'recipient_name',
        'notification_type',
        'related_type',
        'related_id',
        'message',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Relacionamento com instância WhatsApp
     */
    public function instance(): BelongsTo
    {
        return $this->belongsTo(WhatsAppInstance::class, 'whatsapp_instance_id');
    }

    /**
     * Relacionamento polimórfico com modelo relacionado
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Obter label do tipo de notificação
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->notification_type) {
            'order_status' => 'Status do Pedido',
            'promotion' => 'Promoção',
            'cart_abandonment' => 'Carrinho Abandonado',
            'custom' => 'Personalizada',
            default => 'Desconhecido'
        };
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'sent' => 'bg-green-100 text-green-800',
            'delivered' => 'bg-blue-100 text-blue-800',
            'read' => 'bg-purple-100 text-purple-800',
            'failed' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Obter label do status
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'sent' => 'Enviada',
            'delivered' => 'Entregue',
            'read' => 'Lida',
            'failed' => 'Falhou',
            'pending' => 'Pendente',
            default => 'Desconhecido'
        };
    }

    /**
     * Verificar se foi entregue
     */
    public function isDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'read']);
    }

    /**
     * Verificar se foi lida
     */
    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    /**
     * Verificar se falhou
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Scope por tipo de notificação
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Scope por status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope por período
     */
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope por instância
     */
    public function scopeByInstance($query, int $instanceId)
    {
        return $query->where('whatsapp_instance_id', $instanceId);
    }

    /**
     * Scope para notificações falhadas
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope para notificações pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
