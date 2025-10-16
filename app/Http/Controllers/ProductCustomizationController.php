<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CustomizableProduct;
use App\Models\ProductCustomization;
use App\Services\ProductCustomizationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ProductCustomizationController extends Controller
{
    public function __construct(
        private ProductCustomizationService $customizationService
    ) {}

    /**
     * Exibe a página de personalização do produto
     */
    public function show(string $slug): View
    {
        $product = CustomizableProduct::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $config = $product->getCustomizationConfig();

        return view('store.product-customization', compact('product', 'config'));
    }

    /**
     * Calcula preço da personalização via AJAX
     */
    public function calculatePrice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customizable_product_id' => 'required|exists:customizable_products,id',
            'material_id' => 'required|exists:materials,id',
            'thickness' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'finish' => 'nullable|string',
            'text_content' => 'nullable|string|max:500',
            'font_family' => 'nullable|string',
            'font_size' => 'nullable|integer|min:8|max:72',
            'text_color' => 'nullable|string',
            'adhesive_printing' => 'nullable|string',
            'adhesive_area' => 'nullable|numeric|min:0',
            'extras' => 'nullable|array',
            'extras.*' => 'string',
            'base_support' => 'nullable|string',
            'custom_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $priceBreakdown = $this->customizationService->calculateCustomizationPrice($request->all());
            
            return response()->json([
                'success' => true,
                'data' => $priceBreakdown
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Salva personalização
     */
    public function saveCustomization(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customizable_product_id' => 'required|exists:customizable_products,id',
            'material_id' => 'required|exists:materials,id',
            'thickness' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'finish' => 'nullable|string',
            'text_content' => 'nullable|string|max:500',
            'font_family' => 'nullable|string',
            'font_size' => 'nullable|integer|min:8|max:72',
            'text_color' => 'nullable|string',
            'adhesive_printing' => 'nullable|string',
            'adhesive_area' => 'nullable|numeric|min:0',
            'extras' => 'nullable|array',
            'extras.*' => 'string',
            'base_support' => 'nullable|string',
            'custom_notes' => 'nullable|string|max:1000',
            'is_quote_request' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sessionId = Session::getId();
            $userId = auth()->check() ? auth()->id() : null;
            
            $customization = $this->customizationService->saveCustomization(
                $request->all(),
                $sessionId,
                $userId
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'customization_id' => $customization->id,
                    'price' => $customization->calculated_price,
                    'summary' => $customization->getSummary()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém personalizações da sessão
     */
    public function getSessionCustomizations(): JsonResponse
    {
        try {
            $sessionId = Session::getId();
            $customizations = $this->customizationService->getCustomizationsBySession($sessionId);
            
            return response()->json([
                'success' => true,
                'data' => $customizations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém personalizações do usuário
     */
    public function getUserCustomizations(): JsonResponse
    {
        try {
            $userId = auth()->check() ? auth()->id() : null;
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $customizations = $this->customizationService->getCustomizationsByUser($userId);
            
            return response()->json([
                'success' => true,
                'data' => $customizations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém detalhes de uma personalização
     */
    public function getCustomizationDetails(int $id): JsonResponse
    {
        try {
            $customization = ProductCustomization::with(['customizableProduct', 'material'])
                ->findOrFail($id);

            // Verifica se o usuário tem acesso a esta personalização
            $sessionId = Session::getId();
            $userId = auth()->check() ? auth()->id() : null;
            
            if ($customization->session_id !== $sessionId && $customization->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'customization' => $customization,
                    'summary' => $customization->getSummary(),
                    'price_breakdown' => $customization->price_breakdown
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove uma personalização
     */
    public function deleteCustomization(int $id): JsonResponse
    {
        try {
            $customization = ProductCustomization::findOrFail($id);

            // Verifica se o usuário tem acesso a esta personalização
            $sessionId = Session::getId();
            $userId = auth()->check() ? auth()->id() : null;
            
            if ($customization->session_id !== $sessionId && $customization->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            $customization->delete();

            return response()->json([
                'success' => true,
                'message' => 'Personalização removida com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Converte personalização em pedido
     */
    public function convertToOrder(int $id): JsonResponse
    {
        try {
            $customization = ProductCustomization::findOrFail($id);

            // Verifica se o usuário tem acesso a esta personalização
            $sessionId = Session::getId();
            $userId = auth()->check() ? auth()->id() : null;
            
            if ($customization->session_id !== $sessionId && $customization->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            $orderData = $this->customizationService->convertToOrder($customization);

            return response()->json([
                'success' => true,
                'data' => $orderData,
                'message' => 'Personalização convertida em pedido com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista produtos personalizáveis
     */
    public function index(): View
    {
        $products = \App\Models\UniversalCustomizableProduct::active()
            ->ordered()
            ->get();

        return view('store.customizable-products', compact('products'));
    }

    /**
     * Obtém configuração de um produto personalizável
     */
    public function getProductConfig(string $slug): JsonResponse
    {
        try {
            $product = CustomizableProduct::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();

            $config = $product->getCustomizationConfig();

            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
