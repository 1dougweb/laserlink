<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormulaField;
use App\Services\FormulaCalculationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FormulaFieldController extends Controller
{
    protected FormulaCalculationService $formulaService;

    public function __construct(FormulaCalculationService $formulaService)
    {
        $this->formulaService = $formulaService;
    }

    /**
     * Listar campos de fórmula
     */
    public function index(): View
    {
        $formulaFields = FormulaField::orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.formula-fields.index', compact('formulaFields'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create(): View
    {
        $availableVariables = (new FormulaField())->getAvailableVariables();
        return view('admin.formula-fields.create', compact('availableVariables'));
    }

    /**
     * Salvar novo campo de fórmula
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formula' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Validar fórmula
        $validationErrors = $this->formulaService->validateFormula($request->formula);
        if (!empty($validationErrors)) {
            return back()->withErrors(['formula' => implode(', ', $validationErrors)])->withInput();
        }

        // Testar fórmula
        $testResult = $this->formulaService->testFormula($request->formula);
        if (!$testResult['success']) {
            return back()->withErrors(['formula' => 'Fórmula inválida: ' . $testResult['error']])->withInput();
        }

        FormulaField::create([
            'name' => $request->name,
            'description' => $request->description,
            'formula' => $request->formula,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.formula-fields.index')
                        ->with('success', 'Campo de fórmula criado com sucesso!');
    }

    /**
     * Mostrar campo de fórmula
     */
    public function show(FormulaField $formulaField): View
    {
        $formulaField->load('products');
        $availableVariables = $formulaField->getAvailableVariables();
        $usedVariables = $formulaField->getUsedVariables();
        
        return view('admin.formula-fields.show', compact('formulaField', 'availableVariables', 'usedVariables'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(FormulaField $formulaField): View
    {
        $availableVariables = $formulaField->getAvailableVariables();
        return view('admin.formula-fields.edit', compact('formulaField', 'availableVariables'));
    }

    /**
     * Atualizar campo de fórmula
     */
    public function update(Request $request, FormulaField $formulaField): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formula' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Validar fórmula
        $validationErrors = $this->formulaService->validateFormula($request->formula);
        if (!empty($validationErrors)) {
            return back()->withErrors(['formula' => implode(', ', $validationErrors)])->withInput();
        }

        // Testar fórmula
        $testResult = $this->formulaService->testFormula($request->formula);
        if (!$testResult['success']) {
            return back()->withErrors(['formula' => 'Fórmula inválida: ' . $testResult['error']])->withInput();
        }

        $formulaField->update([
            'name' => $request->name,
            'description' => $request->description,
            'formula' => $request->formula,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.formula-fields.index')
                        ->with('success', 'Campo de fórmula atualizado com sucesso!');
    }

    /**
     * Excluir campo de fórmula
     */
    public function destroy(FormulaField $formulaField): RedirectResponse
    {
        $formulaField->delete();
        
        return redirect()->route('admin.formula-fields.index')
                        ->with('success', 'Campo de fórmula excluído com sucesso!');
    }

    /**
     * Testar fórmula
     */
    public function testFormula(Request $request)
    {
        $request->validate([
            'formula' => 'required|string',
            'test_variables' => 'nullable|array',
        ]);

        $testResult = $this->formulaService->testFormula(
            $request->formula,
            $request->test_variables ?? []
        );

        return response()->json($testResult);
    }

    /**
     * Validar fórmula
     */
    public function validateFormula(Request $request)
    {
        $request->validate([
            'formula' => 'required|string',
        ]);

        $errors = $this->formulaService->validateFormula($request->formula);
        
        return response()->json([
            'valid' => empty($errors),
            'errors' => $errors
        ]);
    }

    /**
     * Reordenar campos
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:formula_fields,id',
        ]);

        foreach ($request->order as $index => $id) {
            FormulaField::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}