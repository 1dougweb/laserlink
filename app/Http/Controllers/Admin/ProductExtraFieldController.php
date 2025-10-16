<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ExtraField;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductExtraFieldController extends Controller
{
    /**
     * Mostrar campos extras do produto
     */
    public function index(Request $request, Product $product)
    {
        try {
            \Log::info('ProductExtraFieldController::index - Product ID: ' . $product->id);
            
            $product->load('extraFields.options');
            
            $availableFields = ExtraField::where('is_active', true)
                ->whereNotIn('id', $product->extraFields->pluck('id'))
                ->orderBy('name')
                ->get();
                
            \Log::info('Available fields count: ' . $availableFields->count());

            // Se for requisição AJAX, retornar apenas o conteúdo
            if ($request->ajax() || $request->wantsJson()) {
                $html = view('admin.products.extra-fields-content', compact('product', 'availableFields'))->render();
                
                // Retornar como resposta de texto simples para evitar problemas de encoding
                return response($html, 200, [
                    'Content-Type' => 'text/html; charset=utf-8',
                    'X-Success' => 'true'
                ]);
            }

            return view('admin.products.extra-fields', compact('product', 'availableFields'));
        } catch (\Exception $e) {
            \Log::error('Erro no ProductExtraFieldController: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro interno: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao carregar campos extras: ' . $e->getMessage());
        }
    }

    /**
     * Associar campo ao produto
     */
    public function store(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'extra_field_id' => 'required|exists:extra_fields,id',
                'is_required' => 'boolean',
                'field_settings' => 'nullable|array',
            ]);

            // Verificar se já não está associado
            if ($product->extraFields()->where('extra_field_id', $validated['extra_field_id'])->exists()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este campo já está associado ao produto!'
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', 'Este campo já está associado ao produto!');
            }

            // Get the next sort order
            $maxSortOrder = $product->extraFields()->max('product_extra_fields.sort_order') ?? 0;
            $sortOrder = $maxSortOrder + 1;

            $product->extraFields()->attach($validated['extra_field_id'], [
                'is_required' => $validated['is_required'] ?? false,
                'field_settings' => json_encode($validated['field_settings'] ?? []),
                'sort_order' => $sortOrder,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                // Buscar o campo adicionado com suas configurações
                $field = $product->extraFields()->where('extra_field_id', $validated['extra_field_id'])->first();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Campo associado ao produto com sucesso!',
                    'field' => [
                        'id' => $field->id,
                        'name' => $field->name,
                        'type' => $field->type,
                        'description' => $field->description,
                        'sort_order' => $sortOrder,
                        'is_required' => $validated['is_required'] ?? false,
                        'field_settings' => $validated['field_settings'] ?? []
                    ]
                ]);
            }

            return redirect()->back()
                ->with('success', 'Campo associado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao associar campo: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao associar campo: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao associar campo: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar configuração do campo no produto
     */
    public function update(Request $request, Product $product, int $fieldId): RedirectResponse
    {
        $validated = $request->validate([
            'is_required' => 'boolean',
            'field_settings' => 'nullable|array',
        ]);

        $product->extraFields()->updateExistingPivot($fieldId, [
            'is_required' => $validated['is_required'] ?? false,
            'field_settings' => json_encode($validated['field_settings'] ?? []),
        ]);

        return redirect()->back()
            ->with('success', 'Configuração atualizada com sucesso!');
    }

    /**
     * Remover campo do produto
     */
    public function destroy(Request $request, Product $product, int $fieldId)
    {
        try {
            $product->extraFields()->detach($fieldId);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Campo removido com sucesso!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Campo removido com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao remover campo: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao remover campo: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao remover campo: ' . $e->getMessage());
        }
    }

    /**
     * Reordenar campos do produto
     */
    public function reorder(Request $request, Product $product)
    {
        try {
            // Formato exato igual ao controller de extra-fields
            $validated = $request->validate([
                'order' => 'required|array',
                'order.*' => 'required|integer|exists:extra_fields,id',
            ]);

            foreach ($validated['order'] as $index => $fieldId) {
                $product->extraFields()->updateExistingPivot($fieldId, [
                    'sort_order' => $index + 1,
                ]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ordem atualizada com sucesso!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Ordem atualizada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao reordenar campos: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao reordenar campos: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao reordenar campos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar configurações do campo
     */
    public function settings(Request $request, Product $product, int $fieldId)
    {
        try {
            $field = $product->extraFields()->where('extra_field_id', $fieldId)->first();
            
            if (!$field) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Campo não encontrado'
                    ], 404);
                }
                return back()->with('error', 'Campo não encontrado');
            }

            // Carregar opções do campo se existirem
            $field->load('options');

            if ($request->ajax() || $request->wantsJson()) {
                $html = view('admin.products.field-settings-form', compact('product', 'field'))->render();
                return response()->json([
                    'success' => true,
                    'html' => $html
                ]);
            }

            return view('admin.products.field-settings', compact('product', 'field'));
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar configurações do campo: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao carregar configurações: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao carregar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Retornar opções salvas do campo no produto
     */
    public function getFieldOptions(Product $product, int $fieldId)
    {
        try {
            $field = $product->extraFields()->where('extra_field_id', $fieldId)->first();
            
            if (!$field) {
                return response()->json([
                    'success' => false,
                    'options' => []
                ]);
            }
            
            $fieldSettings = $field->pivot->field_settings ? json_decode($field->pivot->field_settings, true) : [];
            $customOptions = $fieldSettings['custom_options'] ?? null;
            
            if ($customOptions) {
                // Formatar opções para exibição
                $options = collect($customOptions)->map(function($opt) {
                    $formattedOption = [
                        'value' => $opt['value'],
                        'label' => $opt['label'],
                        'price' => number_format((float)$opt['price'], 2, ',', '.'),
                        'price_type' => $opt['price_type'],
                        'is_active' => $opt['is_active'] ?? true
                    ];
                    
                    // Incluir campos específicos se existirem
                    if (isset($opt['image_url'])) {
                        $formattedOption['image_url'] = $opt['image_url'];
                    }
                    if (isset($opt['color_hex'])) {
                        $formattedOption['color_hex'] = $opt['color_hex'];
                    }
                    
                    return $formattedOption;
                })->toArray();
                
                return response()->json([
                    'success' => true,
                    'options' => $options
                ]);
            }
            
            return response()->json([
                'success' => false,
                'options' => []
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar opções do campo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'options' => []
            ], 500);
        }
    }
    
    /**
     * Salvar configurações do campo
     */
    public function saveSettings(Request $request, Product $product, int $fieldId)
    {
        try {
            $validated = $request->validate([
                'is_required' => 'nullable|boolean',
                'field_settings' => 'nullable|array',
                'sort_order' => 'nullable|integer|min:1',
            ]);

            // Buscar o sort_order atual se não for fornecido
            $currentPivot = $product->extraFields()->where('extra_field_id', $fieldId)->first();
            $sortOrder = $validated['sort_order'] ?? ($currentPivot ? $currentPivot->pivot->sort_order : 1);

            $product->extraFields()->updateExistingPivot($fieldId, [
                'is_required' => $validated['is_required'] ?? false,
                'field_settings' => json_encode($validated['field_settings'] ?? []),
                'sort_order' => $sortOrder,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configurações salvas com sucesso!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Configurações salvas com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar configurações do campo: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao salvar configurações: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }
}
