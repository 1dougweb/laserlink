<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Listar fornecedores
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro de status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $suppliers = $query->withCount('products')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Salvar fornecedor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:suppliers,cnpj',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:9',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    /**
     * Exibir fornecedor
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['products' => function($query) {
            $query->orderBy('name');
        }]);

        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Atualizar fornecedor
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:suppliers,cnpj,' . $supplier->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:9',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * Deletar fornecedor
     */
    public function destroy(Supplier $supplier)
    {
        // Verificar se tem produtos vinculados
        if ($supplier->products()->count() > 0) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Não é possível excluir um fornecedor com produtos vinculados.');
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Fornecedor excluído com sucesso!');
    }

    /**
     * Exclusão em massa de fornecedores
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:suppliers,id',
        ]);

        $ids = $request->input('ids');
        
        // Verificar se algum fornecedor tem produtos vinculados
        $suppliersWithProducts = Supplier::whereIn('id', $ids)
            ->has('products')
            ->pluck('name')
            ->toArray();

        if (!empty($suppliersWithProducts)) {
            $names = implode(', ', $suppliersWithProducts);
            return redirect()->route('admin.suppliers.index')
                ->with('error', "Não é possível excluir os seguintes fornecedores pois possuem produtos vinculados: {$names}");
        }

        try {
            DB::beginTransaction();
            
            $deletedCount = Supplier::whereIn('id', $ids)->delete();
            
            DB::commit();

            return redirect()->route('admin.suppliers.index')
                ->with('success', "{$deletedCount} fornecedor(es) excluído(s) com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Erro ao excluir fornecedores: ' . $e->getMessage());
        }
    }
}

