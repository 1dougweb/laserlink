<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWhatsAppInstanceRequest;
use App\Models\WhatsAppInstance;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instances = WhatsAppInstance::orderBy('created_at', 'desc')->get();
        $canCreateNew = WhatsAppInstance::canCreateNew();
        
        return view('admin.whatsapp.instances.index', compact('instances', 'canCreateNew'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $canCreateNew = WhatsAppInstance::canCreateNew();
        
        if (!$canCreateNew) {
            return redirect()->route('admin.whatsapp.instances.index')
                           ->with('error', 'Limite máximo de 3 instâncias atingido.');
        }

        return view('admin.whatsapp.instances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWhatsAppInstanceRequest $request)
    {
        try {
            $canCreateNew = WhatsAppInstance::canCreateNew();
            
            if (!$canCreateNew) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Limite máximo de 3 instâncias atingido.');
            }

            $data = $request->validated();
            
            // Obter configurações da API das settings
            $baseUrl = \App\Models\Setting::get('evolution_api_base_url');
            $apiKey = \App\Models\Setting::get('evolution_api_key');
            
            if (!$baseUrl || !$apiKey) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Configure a Evolution API primeiro em Configurações > WhatsApp.');
            }
            
            // Gerar nome único para a instância
            $instanceName = 'instance_' . uniqid();
            
            // Criar instância na Evolution API
            $evolutionApi = new EvolutionApiService($baseUrl, $apiKey);
            $apiResponse = $evolutionApi->createInstance($instanceName, ['purpose' => $data['purpose']]);

            if (!$apiResponse) {
                throw new Exception('Falha ao criar instância na Evolution API');
            }

            // Criar registro no banco
            $instance = WhatsAppInstance::create([
                'name' => $data['name'],
                'instance_name' => $instanceName,
                'purpose' => $data['purpose'],
                'api_key' => $apiKey,
                'base_url' => $baseUrl,
                'status' => 'disconnected',
                'is_active' => $data['is_active'] ?? true,
            ]);

            // QR Code será obtido quando necessário (não imediatamente após criação)

            Log::info('Instância WhatsApp criada', [
                'instance_id' => $instance->id,
                'instance_name' => $instance->instance_name,
                'purpose' => $instance->purpose
            ]);

            return redirect()->route('admin.whatsapp.instances.show', $instance)
                           ->with('success', 'Instância criada com sucesso! Agora você pode escanear o QR Code para conectar.');

        } catch (Exception $e) {
            Log::error('Erro ao criar instância WhatsApp', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro ao criar instância: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WhatsAppInstance $instance)
    {
        try {
            // Atualizar status da instância
            $this->updateInstanceStatus($instance);
            
            // Se a instância está desconectada e não tem QR Code, tentar obter
            if ($instance->status === 'disconnected' && !$instance->qr_code) {
                try {
                    $evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
                    $qrCode = $evolutionApi->getQrCode($instance->instance_name);
                    
                    if ($qrCode) {
                        $instance->update(['qr_code' => $qrCode]);
                    }
                } catch (Exception $e) {
                    Log::warning('Erro ao obter QR Code na exibição', [
                        'instance_id' => $instance->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Obter estatísticas
            $stats = [
                'total_notifications' => $instance->notifications()->count(),
                'sent_notifications' => $instance->notifications()->where('status', 'sent')->count(),
                'delivered_notifications' => $instance->notifications()->whereIn('status', ['delivered', 'read'])->count(),
                'failed_notifications' => $instance->notifications()->where('status', 'failed')->count(),
            ];

            return view('admin.whatsapp.instances.show', compact('instance', 'stats'));

        } catch (Exception $e) {
            Log::error('Erro ao exibir instância WhatsApp', [
                'instance_id' => $instance->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.whatsapp.instances.index')
                           ->with('error', 'Erro ao carregar instância: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhatsAppInstance $instance)
    {
        try {
            // Deletar instância da Evolution API
            $evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
            $deleted = $evolutionApi->deleteInstance($instance->instance_name);

            if (!$deleted) {
                Log::warning('Falha ao deletar instância da Evolution API', [
                    'instance_id' => $instance->id,
                    'instance_name' => $instance->instance_name
                ]);
            }

            // Deletar do banco de dados
            $instance->delete();

            Log::info('Instância WhatsApp deletada', [
                'instance_id' => $instance->id,
                'instance_name' => $instance->instance_name
            ]);

            return redirect()->route('admin.whatsapp.instances.index')
                           ->with('success', 'Instância deletada com sucesso!');

        } catch (Exception $e) {
            Log::error('Erro ao deletar instância WhatsApp', [
                'instance_id' => $instance->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                           ->with('error', 'Erro ao deletar instância: ' . $e->getMessage());
        }
    }

    /**
     * Refresh QR Code for the instance
     */
    public function refreshQrCode(WhatsAppInstance $instance)
    {
        try {
            $evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
            $qrCode = $evolutionApi->getQrCode($instance->instance_name);

            if ($qrCode) {
                $instance->update(['qr_code' => $qrCode]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'QR Code atualizado com sucesso!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter novo QR Code.'
            ], 400);

        } catch (Exception $e) {
            Log::error('Erro ao atualizar QR Code', [
                'instance_id' => $instance->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check instance status (AJAX)
     */
    public function checkStatus(WhatsAppInstance $instance): JsonResponse
    {
        try {
            $evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
            $status = $evolutionApi->getInstanceStatus($instance->instance_name);

            $newStatus = 'disconnected';
            $connectedAt = null;
            $disconnectedAt = null;

            if (isset($status['instance']['state'])) {
                switch ($status['instance']['state']) {
                    case 'open':
                        $newStatus = 'connected';
                        if ($instance->status !== 'connected') {
                            $connectedAt = now();
                        }
                        break;
                    case 'connecting':
                        $newStatus = 'connecting';
                        break;
                    default:
                        $newStatus = 'disconnected';
                        if ($instance->status === 'connected') {
                            $disconnectedAt = now();
                        }
                        break;
                }
            }

            // Atualizar status no banco
            $updateData = ['status' => $newStatus];
            if ($connectedAt) {
                $updateData['connected_at'] = $connectedAt;
            }
            if ($disconnectedAt) {
                $updateData['disconnected_at'] = $disconnectedAt;
            }

            $instance->update($updateData);

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'status_label' => $instance->status_label,
                'status_color' => $instance->status_color,
                'is_connected' => $instance->isConnected(),
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao verificar status da instância', [
                'instance_id' => $instance->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle instance active status
     */
    public function toggleActive(WhatsAppInstance $instance)
    {
        try {
            $instance->update(['is_active' => !$instance->is_active]);

            $status = $instance->is_active ? 'ativada' : 'desativada';

            Log::info('Status da instância WhatsApp alterado', [
                'instance_id' => $instance->id,
                'is_active' => $instance->is_active
            ]);

            return redirect()->back()
                           ->with('success', "Instância {$status} com sucesso!");

        } catch (Exception $e) {
            Log::error('Erro ao alterar status da instância', [
                'instance_id' => $instance->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                           ->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Update instance status
     */
    private function updateInstanceStatus(WhatsAppInstance $instance): void
    {
        try {
            $evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
            $isConnected = $evolutionApi->checkConnection($instance->instance_name);

            $newStatus = $isConnected ? 'connected' : 'disconnected';
            $updateData = ['status' => $newStatus];

            if ($isConnected && $instance->status !== 'connected') {
                $updateData['connected_at'] = now();
            } elseif (!$isConnected && $instance->status === 'connected') {
                $updateData['disconnected_at'] = now();
            }

            $instance->update($updateData);

        } catch (Exception $e) {
            Log::warning('Erro ao atualizar status da instância', [
                'instance_id' => $instance->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
