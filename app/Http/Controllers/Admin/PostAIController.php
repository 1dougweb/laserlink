<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostAIController extends Controller
{
    /**
     * Debug das configurações do Gemini
     */
    public function debugConfig(): JsonResponse
    {
        $geminiEnabled = Setting::get('gemini_enabled', false);
        $apiKey = Setting::get('gemini_api_key');
        
        return response()->json([
            'gemini_enabled' => $geminiEnabled,
            'api_key_set' => !empty($apiKey),
            'api_key_length' => strlen($apiKey ?? ''),
            'user_authenticated' => auth()->check(),
            'user_is_admin' => auth()->user()?->is_admin ?? false,
        ]);
    }
    /**
     * Gerar conteúdo do post com IA
     */
    public function generateContent(Request $request): JsonResponse
    {
        // Log simples para debug
        file_put_contents(storage_path('logs/post_ai_debug.log'), 
            date('Y-m-d H:i:s') . " - PostAIController@generateContent CHAMADO\n", 
            FILE_APPEND
        );
        
        // Log de autenticação
        file_put_contents(storage_path('logs/post_ai_debug.log'), 
            date('Y-m-d H:i:s') . " - Auth: " . (auth()->check() ? 'OK' : 'FAILED') . 
            ", User: " . (auth()->user() ? auth()->user()->name : 'NULL') . 
            ", IsAdmin: " . (auth()->user() ? (auth()->user()->is_admin ? 'YES' : 'NO') : 'NULL') . "\n", 
            FILE_APPEND
        );
        
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'category' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                file_put_contents(storage_path('logs/post_ai_debug.log'), 
                    date('Y-m-d H:i:s') . " - VALIDAÇÃO FALHOU: " . json_encode($validator->errors()) . "\n", 
                    FILE_APPEND
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();
            
            file_put_contents(storage_path('logs/post_ai_debug.log'), 
                date('Y-m-d H:i:s') . " - DADOS VALIDADOS: " . json_encode($validated) . "\n", 
                FILE_APPEND
            );

            // Generate AI-powered content based on post title
            $aiData = $this->generateAIContent($validated);

            if (!$aiData) {
                file_put_contents(storage_path('logs/post_ai_debug.log'), 
                    date('Y-m-d H:i:s') . " - aiData É NULL - RETORNANDO ERRO 422\n", 
                    FILE_APPEND
                );
                return response()->json([
                    'success' => false,
                    'message' => 'IA indisponível. Configure a chave do Gemini e habilite a IA em Configurações > Gemini AI.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'excerpt' => $aiData['excerpt'] ?? '',
                'content' => $aiData['content'] ?? '',
                'meta_title' => $aiData['meta_title'] ?? '',
                'meta_description' => $aiData['meta_description'] ?? '',
                'meta_keywords' => $aiData['meta_keywords'] ?? '',
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar conteúdo do post:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar conteúdo. Tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno',
            ], 500);
        }
    }

    /**
     * Gerar conteúdo com Gemini AI
     */
    private function generateAIContent(array $data): ?array
    {
        $title = $data['title'];
        
        try {
            // Verificar se Gemini está habilitado
            $geminiEnabled = Setting::get('gemini_enabled', false);
            $apiKey = Setting::get('gemini_api_key');
            
            // Log detalhado para debug
            file_put_contents(storage_path('logs/post_ai_debug.log'), 
                date('Y-m-d H:i:s') . " - generateAIContent: geminiEnabled=" . ($geminiEnabled ? 'true' : 'false') . 
                ", apiKey=" . (!empty($apiKey) ? 'SET' : 'EMPTY') . "\n", 
                FILE_APPEND
            );
            
            if ($geminiEnabled && !empty($apiKey)) {
                // Integração com Gemini AI
                $geminiResponse = $this->callGeminiAI($title, $data);
                
                if ($geminiResponse) {
                    return [
                        'excerpt' => $geminiResponse['excerpt'] ?? '',
                        'content' => $geminiResponse['content'] ?? '',
                        'meta_title' => $geminiResponse['meta_title'] ?? $title,
                        'meta_description' => $geminiResponse['meta_description'] ?? '',
                        'meta_keywords' => $geminiResponse['meta_keywords'] ?? '',
                    ];
                }
            } else {
                file_put_contents(storage_path('logs/post_ai_debug.log'), 
                    date('Y-m-d H:i:s') . " - generateAIContent: CONDIÇÃO FALHOU - geminiEnabled: " . 
                    ($geminiEnabled ? 'true' : 'false') . ", apiKey: " . (!empty($apiKey) ? 'SET' : 'EMPTY') . "\n", 
                    FILE_APPEND
                );
            }
        } catch (\Exception $e) {
            Log::error('Erro ao gerar conteúdo com Gemini:', [
                'message' => $e->getMessage()
            ]);
            
            file_put_contents(storage_path('logs/post_ai_debug.log'), 
                date('Y-m-d H:i:s') . " - generateAIContent EXCEPTION: " . $e->getMessage() . "\n", 
                FILE_APPEND
            );
        }

        return null;
    }

    /**
     * Chamar API do Gemini
     */
    private function callGeminiAI(string $title, array $data = []): ?array
    {
        // Verificar se Gemini está habilitado
        $geminiEnabled = Setting::get('gemini_enabled', false);
        if (!$geminiEnabled) {
            return null;
        }

        $apiKey = Setting::get('gemini_api_key');
        if (!$apiKey) {
            return null;
        }

        $category = $data['category'] ?? '';

        $prompt = "Você é um especialista em criação de conteúdo para blogs sobre comunicação visual, sinalização, troféus e premiações.

Crie um artigo de blog completo e profissional com base no título: \"{$title}\"" . 
($category ? " (Categoria: {$category})" : '') . "

IMPORTANTE: Retorne APENAS um JSON válido, sem markdown, sem texto adicional, sem explicações. O JSON deve seguir EXATAMENTE este formato:

{
    \"excerpt\": \"Um resumo atrativo de 2-3 linhas que desperte curiosidade\",
    \"content\": \"<h2>Título da Seção</h2><p>Conteúdo rico em HTML formatado com parágrafos, listas, subtítulos. Mínimo 500 palavras. Use tags HTML como <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>. Crie pelo menos 3 seções principais.</p>\",
    \"meta_title\": \"Título otimizado para SEO (máximo 60 caracteres)\",
    \"meta_description\": \"Descrição para motores de busca que inclua palavra-chave principal (máximo 160 caracteres)\",
    \"meta_keywords\": \"palavra1, palavra2, palavra3, palavra4, palavra5\"
}

O conteúdo deve:
- Ser informativo e profissional
- Usar HTML para formatação
- Ter parágrafos bem estruturados
- Incluir listas quando apropriado
- Ser otimizado para SEO
- Ser relevante para o mercado de comunicação visual brasileiro";

        try {
            // Chamar API do Gemini
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [[
                    'parts' => [[
                        'text' => $prompt
                    ]]
                ]],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Erro na API do Gemini (Posts):', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $result = $response->json();
            
            if (empty($result['candidates'][0]['content']['parts'][0]['text'])) {
                return null;
            }

            $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
            
            // Limpar possível markdown
            $aiText = preg_replace('/^```json\s*/i', '', $aiText);
            $aiText = preg_replace('/\s*```$/i', '', $aiText);
            $aiText = trim($aiText);

            $aiData = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Erro ao decodificar JSON do Gemini (Posts):', [
                    'error' => json_last_error_msg(),
                    'text' => substr($aiText, 0, 500)
                ]);
                return null;
            }

            return $aiData;

        } catch (\Exception $e) {
            Log::error('Erro ao chamar Gemini API (Posts):', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}
