<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerNotification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'link',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * Get the icon class based on notification type.
     */
    public function getIconClass(): string
    {
        return match($this->type) {
            'success' => 'fas fa-check-circle text-green-500',
            'warning' => 'fas fa-exclamation-circle text-yellow-500',
            'error' => 'fas fa-times-circle text-red-500',
            default => 'fas fa-info-circle text-blue-500'
        };
    }
}