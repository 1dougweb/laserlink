<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RawMaterialStockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawMaterialController extends Controller
{
    /**
     * Dashboard de matéria-prima
     */
    public function index(Request $request)
    {
        $query = RawMaterial::with(['supplier']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low_stock') {
                $query->whereColumn('stock_quantity', '<=', 'stock_min');
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $materials = $query->orderBy('name')->paginate(20)->withQueryString();

        // Estatísticas
        $lowStockCount = RawMaterial::lowStock()->count();
        $outOfStockCount = RawMaterial::outOfStock()->count();
        $totalValue = RawMaterial::sum(DB::raw('stock_quantity * unit_cost'));

        return view('admin.raw-materials.index', compact('materials', 'lowStockCount', 'outOfStockCount', 'totalValue'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        return view('admin.raw-materials.create', compact('suppliers'));
    }

    /**
     * Salvar material
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:raw_materials,code',
            'category' => 'required|string',
            'unit' => 'required|in:m2,kg,l,ml,g,un',
            'stock_quantity' => 'required|numeric|min:0',
            'stock_min' => 'required|numeric|min:0',
            'stock_max' => 'nullable|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        RawMaterial::create($validated);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Matéria-prima cadastrada com sucesso!');
    }

    /**
     * Exibir material
     */
    public function show(RawMaterial $rawMaterial)
    {
        $rawMaterial->load('supplier');
        
        $recentMovements = $rawMaterial->stockMovements()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.raw-materials.show', compact('rawMaterial', 'recentMovements'));
    }

    /**
     * Formulário de edição
     */
    public function edit(RawMaterial $rawMaterial)
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        return view('admin.raw-materials.edit', compact('rawMaterial', 'suppliers'));
    }

    /**
     * Atualizar material
     */
    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:raw_materials,code,' . $rawMaterial->id,
            'category' => 'required|string',
            'unit' => 'required|in:m2,kg,l,ml,g,un',
            'stock_min' => 'required|numeric|min:0',
            'stock_max' => 'nullable|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $rawMaterial->update($validated);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Matéria-prima atualizada com sucesso!');
    }

    /**
     * Deletar material
     */
    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Matéria-prima excluída com sucesso!');
    }

    /**
     * Página de movimentações
     */
    public function movements(Request $request)
    {
        $query = RawMaterialStockMovement::with(['rawMaterial', 'user']);

        if ($request->filled('material_id')) {
            $query->where('raw_material_id', $request->material_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(50);
        $materials = RawMaterial::active()->orderBy('name')->get(['id', 'name']);

        return view('admin.raw-materials.movements', compact('movements', 'materials'));
    }

    /**
     * Formulário de movimentação
     */
    public function createMovement()
    {
        $materials = RawMaterial::active()->orderBy('name')->get();
        return view('admin.raw-materials.create-movement', compact('materials'));
    }

    /**
     * Processar movimentação
     */
    public function storeMovement(Request $request)
    {
        $validated = $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'type' => 'required|in:entrada,saida,ajuste,producao',
            'quantity' => 'required|numeric|min:0.001',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $material = RawMaterial::findOrFail($validated['raw_material_id']);

        DB::transaction(function() use ($material, $validated) {
            if ($validated['type'] === 'entrada') {
                $material->addStock(
                    $validated['quantity'],
                    'entrada',
                    $validated['unit_cost'] ?? null,
                    $validated['reference'] ?? null,
                    $validated['notes'] ?? null
                );
            } elseif (in_array($validated['type'], ['saida', 'producao'])) {
                if ($material->stock_quantity < $validated['quantity']) {
                    throw new \Exception('Estoque insuficiente!');
                }
                $material->removeStock(
                    $validated['quantity'],
                    $validated['type'],
                    $validated['reference'] ?? null,
                    $validated['notes'] ?? null
                );
            } else { // ajuste
                $difference = $validated['quantity'] - $material->stock_quantity;
                if ($difference > 0) {
                    $material->addStock(
                        $difference,
                        'ajuste',
                        $validated['unit_cost'] ?? null,
                        $validated['reference'] ?? null,
                        $validated['notes'] ?? null
                    );
                } elseif ($difference < 0) {
                    $material->removeStock(
                        abs($difference),
                        'ajuste',
                        $validated['reference'] ?? null,
                        $validated['notes'] ?? null
                    );
                }
            }
        });

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Movimentação realizada com sucesso!');
    }
}
