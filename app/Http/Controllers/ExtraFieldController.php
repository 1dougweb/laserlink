<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ExtraField;
use App\Services\ExtraFieldService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExtraFieldController extends Controller
{
    public function __construct(
        private ExtraFieldService $extraFieldService
    ) {}

    /**
     * Obter configuração dos campos extras
     */
    public function getConfig(Request $request): JsonResponse
    {
        try {
            $config = $this->extraFieldService->getConfig();
            return response()->json($config);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obter opções de um campo específico
     */
    public function getFieldOptions(string $fieldSlug): JsonResponse
    {
        try {
            $field = ExtraField::where('slug', $fieldSlug)->firstOrFail();
            $options = $this->extraFieldService->getFieldOptions($field);
            return response()->json($options);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Calcular preço baseado nas seleções
     */
    public function calculatePrice(Request $request): JsonResponse
    {
        try {
            $selections = $request->input('selections', []);
            $context = $request->input('context', []);
            
            $price = $this->extraFieldService->calculatePrice($selections, $context);
            
            return response()->json([
                'price' => $price,
                'breakdown' => $this->extraFieldService->getPriceBreakdown($selections, $context)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Validar seleções
     */
    public function validateSelections(Request $request): JsonResponse
    {
        try {
            $selections = $request->input('selections', []);
            $errors = $this->extraFieldService->validateSelections($selections);
            
            return response()->json([
                'valid' => empty($errors),
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
