<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CustomerNotification;
use App\Models\User;
use App\Models\Order;

class CustomerNotificationService
{
    /**
     * Criar uma nova notificação para o cliente
     */
    public function create(User $user, string $title, string $message, string $type = 'info', ?string $link = null): CustomerNotification
    {
        return CustomerNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'icon' => $this->getIconForType($type)
        ]);
    }

    /**
     * Criar notificação de pedido recebido
     */
    public function orderReceived(Order $order): void
    {
        if (!$order->user_id) return;

        $this->create(
            user: User::find($order->user_id),
            title: 'Pedido Recebido',
            message: "Seu pedido #{$order->order_number} foi recebido e está sendo processado.",
            type: 'success',
            link: route('store.order-details', $order->id)
        );
    }

    /**
     * Criar notificação de atualização de status do pedido
     */
    public function orderStatusUpdated(Order $order, string $oldStatus, string $newStatus): void
    {
        if (!$order->user_id) return;

        $statusMessages = [
            'confirmed' => 'confirmado',
            'processing' => 'em processamento',
            'shipped' => 'enviado',
            'delivered' => 'entregue',
            'cancelled' => 'cancelado'
        ];

        $message = "Seu pedido #{$order->order_number} foi " . ($statusMessages[$newStatus] ?? $newStatus);
        
        $this->create(
            user: User::find($order->user_id),
            title: 'Status do Pedido Atualizado',
            message: $message,
            type: $this->getTypeForStatus($newStatus),
            link: route('store.order-details', $order->id)
        );
    }

    /**
     * Obter ícone baseado no tipo de notificação
     */
    private function getIconForType(string $type): string
    {
        return match($type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-circle',
            'error' => 'fas fa-times-circle',
            default => 'fas fa-info-circle'
        };
    }

    /**
     * Obter tipo de notificação baseado no status do pedido
     */
    private function getTypeForStatus(string $status): string
    {
        return match($status) {
            'confirmed', 'delivered' => 'success',
            'processing', 'shipped' => 'info',
            'cancelled' => 'error',
            default => 'info'
        };
    }
}