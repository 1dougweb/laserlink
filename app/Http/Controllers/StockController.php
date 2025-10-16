<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Dashboard de estoque
     */
    public function index(Request $request)
    {
        // Produtos com estoque baixo (que têm stock_min configurado)
        $lowStockProducts = Product::where('track_stock', true)
            ->where('is_active', true)
            ->whereNotNull('stock_min')
            ->where('stock_min', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'stock_min')
            ->where('stock_quantity', '>', 0) // Exclui os que estão zerados
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        // Produtos sem estoque (zerados)
        $outOfStockProducts = Product::where('track_stock', true)
            ->where('is_active', true)
            ->where('stock_quantity', '<=', 0)
            ->count();

        // Valor total do estoque
        $totalStockValue = Product::where('track_stock', true)
            ->where('is_active', true)
            ->sum(DB::raw('stock_quantity * price'));

        // Total de produtos em estoque
        $totalProducts = Product::where('track_stock', true)
            ->where('is_active', true)
            ->count();

        // Todos os produtos com controle de estoque
        $allProducts = Product::where('track_stock', true)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        // Movimentações recentes
        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.stock.index', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'totalStockValue',
            'totalProducts',
            'allProducts',
            'recentMovements'
        ));
    }

    /**
     * Histórico de movimentações
     */
    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // Filtro por produto
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por período
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        $products = Product::where('track_stock', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.stock.movements', compact('movements', 'products'));
    }

    /**
     * Formulário de entrada/saída de estoque
     */
    public function create()
    {
        $products = Product::where('track_stock', true)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'stock_quantity']);

        return view('admin.stock.create', compact('products'));
    }

    /**
     * Processar movimentação de estoque
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entrada,saida,ajuste',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->track_stock) {
            return redirect()->back()
                ->with('error', 'Este produto não controla estoque.');
        }

        DB::transaction(function() use ($product, $validated) {
            if ($validated['type'] === 'entrada') {
                $product->addStock(
                    $validated['quantity'],
                    'entrada',
                    $validated['unit_cost'] ?? null,
                    $validated['reference'] ?? null,
                    $validated['notes'] ?? null
                );
            } elseif ($validated['type'] === 'saida') {
                if ($product->stock_quantity < $validated['quantity']) {
                    throw new \Exception('Estoque insuficiente!');
                }
                $product->removeStock(
                    $validated['quantity'],
                    'saida',
                    $validated['reference'] ?? null,
                    $validated['notes'] ?? null
                );
            } else { // ajuste
                $difference = $validated['quantity'] - $product->stock_quantity;
                if ($difference > 0) {
                    $product->addStock(
                        $difference,
                        'ajuste',
                        $validated['unit_cost'] ?? null,
                        $validated['reference'] ?? null,
                        $validated['notes'] ?? null
                    );
                } elseif ($difference < 0) {
                    $product->removeStock(
                        abs($difference),
                        'ajuste',
                        $validated['reference'] ?? null,
                        $validated['notes'] ?? null
                    );
                }
            }
        });

        return redirect()->route('admin.stock.index')
            ->with('success', 'Movimentação de estoque realizada com sucesso!');
    }
}

