<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppInstance;
use App\Models\WhatsAppNotification;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppNotificationController extends Controller
{
    private WhatsAppNotificationService $whatsappService;

    public function __construct(WhatsAppNotificationService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $query = WhatsAppNotification::with('instance')
                                   ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('type')) {
            $query->where('notification_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instance_id')) {
            $query->where('whatsapp_instance_id', $request->instance_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(20);
        $instances = WhatsAppInstance::active()->get();

        // Estatísticas
        $stats = [
            'total' => WhatsAppNotification::count(),
            'sent' => WhatsAppNotification::where('status', 'sent')->count(),
            'delivered' => WhatsAppNotification::whereIn('status', ['delivered', 'read'])->count(),
            'failed' => WhatsAppNotification::where('status', 'failed')->count(),
        ];

        return view('admin.whatsapp.notifications.index', compact(
            'notifications', 
            'instances', 
            'stats'
        ));
    }

    /**
     * Display the specified notification.
     */
    public function show(WhatsAppNotification $notification)
    {
        $notification->load(['instance', 'related']);
        
        return view('admin.whatsapp.notifications.show', compact('notification'));
    }

    /**
     * Show form for sending promotional messages.
     */
    public function sendPromotion()
    {
        $instances = WhatsAppInstance::active()->connected()->get();
        
        if ($instances->isEmpty()) {
            return redirect()->route('admin.whatsapp.instances.index')
                           ->with('error', 'Nenhuma instância WhatsApp conectada encontrada. Configure uma instância primeiro.');
        }

        return view('admin.whatsapp.notifications.send-promotion', compact('instances'));
    }

    /**
     * Process promotional message sending.
     */
    public function storePromotion(Request $request)
    {
        $request->validate([
            'customer_selection' => 'required|in:all,custom,with_orders,without_orders',
            'custom_customers' => 'required_if:customer_selection,custom|array',
            'custom_customers.*' => 'exists:users,id',
            'message' => 'required|string|min:10|max:1000',
            'media_url' => 'nullable|url|max:500',
        ], [
            'customer_selection.required' => 'Selecione o tipo de cliente.',
            'customer_selection.in' => 'Tipo de seleção inválido.',
            'custom_customers.required_if' => 'Selecione pelo menos um cliente.',
            'custom_customers.array' => 'Lista de clientes inválida.',
            'custom_customers.*.exists' => 'Cliente selecionado não existe.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem deve ter pelo menos 10 caracteres.',
            'message.max' => 'A mensagem não pode ter mais de 1000 caracteres.',
            'media_url.url' => 'URL da mídia deve ser válida.',
            'media_url.max' => 'URL da mídia não pode ter mais de 500 caracteres.',
        ]);

        try {
            $customerIds = $this->getCustomerIds($request);

            if (empty($customerIds)) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Nenhum cliente válido encontrado para envio.');
            }

            // Enviar mensagens promocionais
            $this->whatsappService->sendPromotionalMessage(
                $customerIds,
                $request->message,
                $request->media_url
            );

            $count = count($customerIds);
            
            Log::info('Promoção enviada via WhatsApp', [
                'customers_count' => $count,
                'message_length' => strlen($request->message),
                'has_media' => !empty($request->media_url)
            ]);

            return redirect()->route('admin.whatsapp.notifications.index')
                           ->with('success', "Promoção enviada para {$count} cliente(s) com sucesso!");

        } catch (Exception $e) {
            Log::error('Erro ao enviar promoção via WhatsApp', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['message', 'media_url'])
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro ao enviar promoção: ' . $e->getMessage());
        }
    }

    /**
     * Resend failed notification.
     */
    public function resend(WhatsAppNotification $notification)
    {
        try {
            if ($notification->status !== 'failed') {
                return redirect()->back()
                               ->with('error', 'Apenas notificações falhadas podem ser reenviadas.');
            }

            // Obter instância
            $instance = $notification->instance;
            if (!$instance || !$instance->isConnected()) {
                return redirect()->back()
                               ->with('error', 'Instância WhatsApp não disponível para reenvio.');
            }

            // Enviar mensagem novamente
            $this->whatsappService->sendCustomMessage(
                $notification->recipient_phone,
                $notification->message
            );

            Log::info('Notificação reenviada via WhatsApp', [
                'notification_id' => $notification->id,
                'recipient' => $notification->recipient_phone
            ]);

            return redirect()->back()
                           ->with('success', 'Notificação reenviada com sucesso!');

        } catch (Exception $e) {
            Log::error('Erro ao reenviar notificação', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                           ->with('error', 'Erro ao reenviar notificação: ' . $e->getMessage());
        }
    }

    /**
     * Get customer IDs based on selection type.
     */
    private function getCustomerIds(Request $request): array
    {
        switch ($request->customer_selection) {
            case 'all':
                return User::whereNotNull('phone')
                          ->where('phone', '!=', '')
                          ->pluck('id')
                          ->toArray();

            case 'with_orders':
                return User::whereHas('orders')
                          ->whereNotNull('phone')
                          ->where('phone', '!=', '')
                          ->pluck('id')
                          ->toArray();

            case 'without_orders':
                return User::whereDoesntHave('orders')
                          ->whereNotNull('phone')
                          ->where('phone', '!=', '')
                          ->pluck('id')
                          ->toArray();

            case 'custom':
                return $request->input('custom_customers', []);

            default:
                return [];
        }
    }

    /**
     * Get customers for custom selection (AJAX).
     */
    public function getCustomers(Request $request)
    {
        $query = User::whereNotNull('phone')
                    ->where('phone', '!=', '');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->select('id', 'name', 'email', 'phone')
                          ->limit(50)
                          ->get();

        return response()->json($customers);
    }

    /**
     * Export notifications to CSV.
     */
    public function export(Request $request)
    {
        $query = WhatsAppNotification::with('instance')
                                   ->orderBy('created_at', 'desc');

        // Aplicar mesmos filtros da index
        if ($request->filled('type')) {
            $query->where('notification_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instance_id')) {
            $query->where('whatsapp_instance_id', $request->instance_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $notifications = $query->get();

        $filename = 'whatsapp_notifications_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($notifications) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho
            fputcsv($file, [
                'ID',
                'Data',
                'Instância',
                'Destinatário',
                'Nome',
                'Tipo',
                'Status',
                'Mensagem',
                'Enviado em',
                'Entregue em',
                'Lido em',
                'Erro'
            ]);

            // Dados
            foreach ($notifications as $notification) {
                fputcsv($file, [
                    $notification->id,
                    $notification->created_at->format('d/m/Y H:i'),
                    $notification->instance->name ?? 'N/A',
                    $notification->recipient_phone,
                    $notification->recipient_name,
                    $notification->type_label,
                    $notification->status_label,
                    $notification->message,
                    $notification->sent_at?->format('d/m/Y H:i') ?? 'N/A',
                    $notification->delivered_at?->format('d/m/Y H:i') ?? 'N/A',
                    $notification->read_at?->format('d/m/Y H:i') ?? 'N/A',
                    $notification->error_message ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

