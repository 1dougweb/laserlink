<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EvolutionApiService;

class WhatsAppSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'evolution_api_base_url' => Setting::get('evolution_api_base_url'),
            'evolution_api_key' => Setting::get('evolution_api_key'),
            'whatsapp_enabled' => Setting::get('whatsapp_enabled', true),
            'max_instances' => Setting::get('whatsapp_max_instances', 3),
            'default_timeout' => Setting::get('whatsapp_default_timeout', 30),
            'retry_attempts' => Setting::get('whatsapp_retry_attempts', 3),
        ];

        return view('admin.whatsapp.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'evolution_api_base_url' => 'required|url',
            'evolution_api_key' => 'required|string|min:10',
            'whatsapp_enabled' => 'boolean',
            'max_instances' => 'required|integer|min:1|max:10',
            'default_timeout' => 'required|integer|min:5|max:120',
            'retry_attempts' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Atualizar configurações
            Setting::set('evolution_api_base_url', $validated['evolution_api_base_url']);
            Setting::set('evolution_api_key', $validated['evolution_api_key']);
            Setting::set('whatsapp_enabled', $validated['whatsapp_enabled'] ?? false);
            Setting::set('whatsapp_max_instances', $validated['max_instances']);
            Setting::set('whatsapp_default_timeout', $validated['default_timeout']);
            Setting::set('whatsapp_retry_attempts', $validated['retry_attempts']);

            Log::info('Configurações WhatsApp atualizadas', ['user_id' => auth()->id()]);

            return redirect()->route('admin.whatsapp.settings')
                ->with('success', 'Configurações salvas com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao salvar configurações WhatsApp: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function testConnection(Request $request)
    {
        // Permitir testar usando os valores enviados ou os salvos nas configurações
        $baseUrl = $request->input('evolution_api_base_url', (string) Setting::get('evolution_api_base_url'));
        $apiKey = $request->input('evolution_api_key', (string) Setting::get('evolution_api_key'));

        if (empty($baseUrl) || empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Configuração inválida: informe Base URL e API Key.'
            ], 422);
        }

        try {
            $service = new EvolutionApiService($baseUrl, $apiKey);
            $result = $service->testConnection();
            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Erro ao testar conexão com Evolution API', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao conectar: ' . $e->getMessage()
            ], 500);
        }
    }
}
