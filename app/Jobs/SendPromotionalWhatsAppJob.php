<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendPromotionalWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300; // 5 minutos
    public int $tries = 3;

    private array $customerIds;
    private string $message;
    private ?string $mediaUrl;

    /**
     * Create a new job instance.
     */
    public function __construct(array $customerIds, string $message, ?string $mediaUrl = null)
    {
        $this->customerIds = $customerIds;
        $this->message = $message;
        $this->mediaUrl = $mediaUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppNotificationService $whatsappService): void
    {
        try {
            Log::info('Iniciando envio de promoção via WhatsApp', [
                'customers_count' => count($this->customerIds),
                'message_length' => strlen($this->message),
                'has_media' => !empty($this->mediaUrl)
            ]);

            // Processar em chunks para evitar sobrecarga
            $chunks = array_chunk($this->customerIds, 50);
            
            foreach ($chunks as $index => $chunk) {
                $this->processChunk($whatsappService, $chunk, $index + 1, count($chunks));
                
                // Delay entre chunks para evitar bloqueio
                if ($index < count($chunks) - 1) {
                    sleep(2);
                }
            }

            Log::info('Envio de promoção via WhatsApp concluído', [
                'total_customers' => count($this->customerIds)
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao processar envio de promoção via WhatsApp', [
                'error' => $e->getMessage(),
                'customers_count' => count($this->customerIds)
            ]);

            throw $e;
        }
    }

    /**
     * Process a chunk of customers
     */
    private function processChunk(WhatsAppNotificationService $whatsappService, array $customerIds, int $chunkNumber, int $totalChunks): void
    {
        try {
            Log::info("Processando chunk {$chunkNumber}/{$totalChunks}", [
                'chunk_size' => count($customerIds)
            ]);

            // Obter clientes válidos (com telefone)
            $customers = User::whereIn('id', $customerIds)
                           ->whereNotNull('phone')
                           ->where('phone', '!=', '')
                           ->get();

            if ($customers->isEmpty()) {
                Log::warning("Nenhum cliente válido encontrado no chunk {$chunkNumber}");
                return;
            }

            // Enviar para cada cliente no chunk
            foreach ($customers as $customer) {
                try {
                    $whatsappService->sendCustomMessage(
                        $customer->phone,
                        $this->message,
                        $this->mediaUrl
                    );

                    // Delay entre envios para evitar bloqueio
                    usleep(500000); // 0.5 segundos

                } catch (Exception $e) {
                    Log::warning('Erro ao enviar mensagem para cliente específico', [
                        'customer_id' => $customer->id,
                        'customer_phone' => $customer->phone,
                        'error' => $e->getMessage()
                    ]);
                    // Continuar com o próximo cliente
                }
            }

            Log::info("Chunk {$chunkNumber}/{$totalChunks} processado com sucesso", [
                'sent_to' => $customers->count()
            ]);

        } catch (Exception $e) {
            Log::error("Erro ao processar chunk {$chunkNumber}", [
                'error' => $e->getMessage(),
                'chunk_size' => count($customerIds)
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Falha ao processar envio de promoção via WhatsApp', [
            'error' => $exception->getMessage(),
            'customers_count' => count($this->customerIds),
            'attempts' => $this->attempts()
        ]);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [30, 60, 120]; // 30s, 1min, 2min
    }
}

