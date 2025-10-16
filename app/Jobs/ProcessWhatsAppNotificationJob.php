<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\WhatsAppNotification;
use App\Models\WhatsAppInstance;
use App\Services\EvolutionApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120; // 2 minutos
    public int $tries = 3;

    private int $notificationId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $notification = WhatsAppNotification::find($this->notificationId);
            
            if (!$notification) {
                Log::warning('Notificação WhatsApp não encontrada', [
                    'notification_id' => $this->notificationId
                ]);
                return;
            }

            // Verificar se já foi processada
            if ($notification->status !== 'pending') {
                Log::info('Notificação já foi processada', [
                    'notification_id' => $this->notificationId,
                    'status' => $notification->status
                ]);
                return;
            }

            $instance = $notification->instance;
            
            if (!$instance || !$instance->isConnected()) {
                Log::warning('Instância WhatsApp não disponível', [
                    'notification_id' => $this->notificationId,
                    'instance_status' => $instance?->status
                ]);

                $notification->update([
                    'status' => 'failed',
                    'error_message' => 'Instância WhatsApp não disponível'
                ]);
                return;
            }

            // Enviar mensagem via Evolution API
            $evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
            
            $response = $evolutionApi->sendTextMessage(
                $instance->instance_name,
                $notification->recipient_phone,
                $notification->message
            );

            // Atualizar status para enviado
            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info('Notificação WhatsApp enviada com sucesso', [
                'notification_id' => $this->notificationId,
                'recipient' => $notification->recipient_phone,
                'instance' => $instance->name
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao processar notificação WhatsApp', [
                'notification_id' => $this->notificationId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // Atualizar status para falhou
            if (isset($notification)) {
                $notification->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Falha ao processar notificação WhatsApp', [
            'notification_id' => $this->notificationId,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Atualizar status final para falhou
        $notification = WhatsAppNotification::find($this->notificationId);
        if ($notification && $notification->status === 'pending') {
            $notification->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [15, 30, 60]; // 15s, 30s, 1min
    }
}

