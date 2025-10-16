<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Product;
use App\Models\ProductPricingConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class BudgetController extends Controller
{
    /**
     * Lista todos os orçamentos
     */
    public function index(Request $request)
    {
        // Página em desenvolvimento - sem necessidade de carregar dados
        return view('admin.budgets.index');
    }

    /**
     * Mostra o formulário para criar novo orçamento
     */
    public function create()
    {
        // Página em desenvolvimento - sem necessidade de carregar dados
        return view('admin.budgets.create');
    }

    /**
     * Armazena novo orçamento
     */
    public function store(Request $request)
    {
        try {
            // Log the request data for debugging
            Log::info('Budget Store Request', $request->all());
            
            $request->validate([
                'client_name' => 'required|string|max:255',
                'client_email' => 'nullable|email|max:255',
                'client_phone' => 'nullable|string|max:20',
                'client_company' => 'nullable|string|max:255',
                'client_address' => 'nullable|string',
                'description' => 'nullable|string',
                'valid_until' => 'nullable|date|after_or_equal:today',
                'notes' => 'nullable|string',
                'terms' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required_with:items|string',
                'items.*.quantity' => 'required_with:items|numeric|min:0.01',
                'items.*.unit_price' => 'required_with:items|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'tax_percentage' => 'nullable|numeric|min:0|max:100',
            ]);

            // Log the data before creation
            Log::info('Budget Data to Create', $request->all());

            $budget = null;
            DB::transaction(function () use ($request, &$budget) {
                // Processar itens e calcular totais antes de criar o orçamento
                $processedItems = [];
                $subtotal = 0;
                
                foreach ($request->items as $item) {
                    // Se for um template, usar o ID como nome temporariamente
                    if (strpos($item['product_id'], 'template_') === 0) {
                        $productName = 'Produto Template'; // Nome padrão para templates
                    } else {
                        // Buscar o produto real
                        $product = Product::find($item['product_id']);
                        $productName = $product ? $product->name : 'Produto não encontrado';
                    }
                    
                    $processedItem = [
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $productName,
                        'quantity' => $item['quantity'] ?? 0,
                        'unit_price' => $item['unit_price'] ?? 0,
                        'total' => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0),
                    ];
                    
                    // Adicionar descrição se existir
                    if (isset($item['description'])) {
                        $processedItem['description'] = $item['description'];
                    }
                    
                    $processedItems[] = $processedItem;
                    $subtotal += $processedItem['total'];
                }
                
                $discountPercentage = $request->discount_percentage ?? 0;
                $taxPercentage = $request->tax_percentage ?? 0;
                
                $discountAmount = $subtotal * ($discountPercentage / 100);
                $afterDiscount = $subtotal - $discountAmount;
                $taxAmount = $afterDiscount * ($taxPercentage / 100);
                $total = $afterDiscount + $taxAmount;

                $budget = Budget::create([
                    'client_name' => $request->client_name,
                    'client_email' => $request->client_email,
                    'client_phone' => $request->client_phone,
                    'client_company' => $request->client_company,
                    'client_address' => $request->client_address,
                    'description' => $request->description,
                    'valid_until' => $request->valid_until,
                    'notes' => $request->notes,
                    'terms' => $request->terms,
                    'items' => $processedItems,
                    'subtotal' => $subtotal,
                    'discount_percentage' => $discountPercentage,
                    'discount_amount' => $discountAmount,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'user_id' => Auth::id(),
                ]);
            });

            // Log successful creation
            if ($budget) {
                Log::info('Budget Created Successfully', ['id' => $budget->id]);
            }

            return redirect()->route('admin.budgets.index')
                ->with('success', 'Orçamento criado com sucesso!');
                
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Budget Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Mostra um orçamento específico
     */
    public function show(Request $request, Budget $budget)
    {
        // Página em desenvolvimento - sem necessidade de carregar dados
        return view('admin.budgets.show');
    }

    /**
     * Mostra o formulário para editar orçamento
     */
    public function edit(Budget $budget)
    {
        // Página em desenvolvimento - sem necessidade de carregar dados
        return view('admin.budgets.edit');
    }

    /**
     * Atualiza orçamento
     */
    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_company' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'description' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $budget) {
            // Processar itens e calcular totais antes de atualizar o orçamento
            $processedItems = [];
            $subtotal = 0;
            
            foreach ($request->items as $item) {
                // Se for um template, usar o ID como nome temporariamente
                if (strpos($item['product_id'], 'template_') === 0) {
                    $productName = 'Produto Template'; // Nome padrão para templates
                } else {
                    // Buscar o produto real
                    $product = Product::find($item['product_id']);
                    $productName = $product ? $product->name : 'Produto não encontrado';
                }
                
                $processedItem = [
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $productName,
                    'quantity' => $item['quantity'] ?? 0,
                    'unit_price' => $item['unit_price'] ?? 0,
                    'total' => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0),
                ];
                
                // Adicionar descrição se existir
                if (isset($item['description'])) {
                    $processedItem['description'] = $item['description'];
                }
                
                $processedItems[] = $processedItem;
                $subtotal += $processedItem['total'];
            }
            
            $discountPercentage = $request->discount_percentage ?? 0;
            $taxPercentage = $request->tax_percentage ?? 0;
            
            $discountAmount = $subtotal * ($discountPercentage / 100);
            $afterDiscount = $subtotal - $discountAmount;
            $taxAmount = $afterDiscount * ($taxPercentage / 100);
            $total = $afterDiscount + $taxAmount;

            $budget->update([
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'client_company' => $request->client_company,
                'client_address' => $request->client_address,
                'description' => $request->description,
                'valid_until' => $request->valid_until,
                'notes' => $request->notes,
                'terms' => $request->terms,
                'items' => $processedItems,
                'subtotal' => $subtotal,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'total' => $total,
            ]);
        });

        return redirect()->route('admin.budgets.index')
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    /**
     * Remove orçamento
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('admin.budgets.index')
            ->with('success', 'Orçamento removido com sucesso!');
    }


    /**
     * Envia orçamento por email
     */
    public function send(Budget $budget)
    {
        if (!$budget->client_email) {
            return redirect()->back()
                ->with('error', 'Email do cliente não informado.');
        }

        $budget->markAsSent();

        return redirect()->back()
            ->with('success', 'Orçamento enviado por email com sucesso!');
    }

    /**
     * Marca orçamento como aprovado
     */
    public function approve(Budget $budget)
    {
        $budget->markAsApproved();

        return redirect()->back()
            ->with('success', 'Orçamento aprovado com sucesso!');
    }

    /**
     * Marca orçamento como rejeitado
     */
    public function reject(Budget $budget)
    {
        $budget->markAsRejected();

        return redirect()->back()
            ->with('success', 'Orçamento rejeitado.');
    }

    /**
     * Duplica orçamento
     */
    public function duplicate(Budget $budget)
    {
        $newBudget = $budget->replicate();
        $newBudget->budget_number = null; // Será gerado automaticamente
        $newBudget->status = 'draft';
        $newBudget->user_id = Auth::id();
        $newBudget->save();

        return redirect()->route('admin.budgets.edit', $newBudget)
            ->with('success', 'Orçamento duplicado com sucesso!');
    }

    /**
     * Calcula preços baseado na configuração selecionada
     */
    public function calculatePrices(Request $request)
    {
        $request->validate([
            'configuration_id' => 'required|exists:product_pricing_configurations,id',
            'items' => 'required|array',
        ]);

        $configuration = ProductPricingConfiguration::findOrFail($request->configuration_id);
        $calculatedItems = [];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $calculatedPrice = $configuration->calculatePrice($product, $item['unit_price'] ?? $product->price);
                $calculatedItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $calculatedPrice,
                    'total' => $item['quantity'] * $calculatedPrice,
                ];
            }
        }

        return response()->json([
            'items' => $calculatedItems,
            'configuration' => $configuration
        ]);
    }

    /**
     * Busca produtos para autocomplete
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->with(['category', 'productType'])
            ->limit(10)
            ->get();

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'category' => $product->category->name,
                'product_type' => $product->productType->name ?? 'N/A',
            ];
        }));
    }

    /**
     * Gerar PDF do orçamento
     */
    public function pdf(Budget $budget)
    {
        try {
            // Ensure data integrity before generating PDF
            $budget->ensureItemsIntegrity();
            
            $pdf = Pdf::loadView('admin.budgets.pdf', compact('budget'))
                     ->setPaper('A4', 'portrait')
                     ->setOptions([
                         'isHtml5ParserEnabled' => true,
                         'isRemoteEnabled' => true,
                         'defaultFont' => 'Arial'
                     ]);

            $filename = 'Orcamento_' . $budget->budget_number . '_' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF do orçamento', [
                'budget_id' => $budget->id,
                'error' => $e->getMessage(),
                'items' => $budget->items
            ]);
            
            return redirect()->back()
                ->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Visualizar PDF do orçamento no navegador
     */
    public function pdfView(Budget $budget)
    {
        try {
            // Ensure data integrity before generating PDF
            $budget->ensureItemsIntegrity();
            
            $pdf = Pdf::loadView('admin.budgets.pdf', compact('budget'))
                     ->setPaper('A4', 'portrait')
                     ->setOptions([
                         'isHtml5ParserEnabled' => true,
                         'isRemoteEnabled' => true,
                         'defaultFont' => 'Arial'
                     ]);

            return $pdf->stream('Orcamento_' . $budget->budget_number . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar PDF do orçamento', [
                'budget_id' => $budget->id,
                'error' => $e->getMessage(),
                'items' => $budget->items
            ]);
            
            return redirect()->back()
                ->with('error', 'Erro ao visualizar PDF: ' . $e->getMessage());
        }
    }
}
