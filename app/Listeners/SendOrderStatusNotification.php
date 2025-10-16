<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Services\WhatsAppNotificationService;
use App\Services\CustomerNotificationService;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification
{
    private WhatsAppNotificationService $whatsappService;
    private CustomerNotificationService $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(
        WhatsAppNotificationService $whatsappService,
        CustomerNotificationService $notificationService
    ) {
        $this->whatsappService = $whatsappService;
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $user = $order->user;

        // Create customer notification
        try {
            $this->notificationService->orderStatusUpdated($order, $event->oldStatus, $event->newStatus);
        } catch (\Exception $e) {
            Log::error('Failed to create customer notification: ' . $e->getMessage());
        }

        // Get template type for WhatsApp notification
        $templateType = $this->getTemplateType($event->newStatus);
        if (!$templateType) {
            Log::info('No template defined for status', [
                'order_id' => $order->id,
                'status' => $event->newStatus
            ]);
            return;
        }

        // Send WhatsApp notification if recipient phone available (prefer order's phone)
        $recipientPhone = $order->customer_phone ?: ($user?->phone ?? null);
        if ($recipientPhone) {
            try {
                $this->whatsappService->sendOrderNotification($order, $templateType);
                
                Log::info('Status change event processed', [
                    'order_id' => $order->id,
                    'old_status' => $event->oldStatus,
                    'new_status' => $event->newStatus,
                    'template_type' => $templateType
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to process status change event', [
                    'order_id' => $order->id,
                    'old_status' => $event->oldStatus,
                    'new_status' => $event->newStatus,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            Log::info('No phone number available for order ' . $order->id);
        }
    }

    /**
     * Determinar o tipo de template baseado no status
     */
    private function getTemplateType(string $status): ?string
    {
        return match($status) {
            'pending' => 'welcome_new_customer',
            'confirmed' => 'order_confirmed',
            'processing' => 'order_processing',
            'shipped' => 'order_shipped',
            'delivered' => 'order_delivered',
            'cancelled' => 'order_cancelled',
            default => null
        };
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderStatusChanged $event, \Throwable $exception): void
    {
        Log::error('Falha ao processar evento de mudança de status', [
            'order_id' => $event->order->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}

