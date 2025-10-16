<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraField;
use App\Models\ExtraFieldOption;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ExtraFieldController extends Controller
{
    /**
     * Listar campos extras
     */
    public function index(Request $request): View
    {
        $query = ExtraField::withCount('options');
        
        // Filtro por nome
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filtro por status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filtro por obrigatório
        if ($request->filled('required')) {
            $query->where('is_required', $request->required === '1');
        }
        
        // Ordenação
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'sort_order') {
            $query->orderBy('sort_order', $sortOrder)->orderBy('name', 'asc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $fields = $query->paginate(20)->withQueryString();

        return view('admin.extra-fields.index', compact('fields'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create(): View
    {
        return view('admin.extra-fields.create');
    }

    /**
     * Salvar novo campo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,select,radio,checkbox,image,color,number,date,file',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Gerar slug único
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $count = 1;
        
        // Verificar se o slug já existe
        while (ExtraField::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }
        
        $validated['slug'] = $slug;
        
        // Se não foi informado sort_order, usar o próximo disponível
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = ExtraField::max('sort_order') + 1;
        }

        ExtraField::create($validated);

        return redirect()->route('admin.extra-fields.index')
            ->with('success', 'Campo criado com sucesso!');
    }

    /**
     * Mostrar campo específico
     */
    public function show(ExtraField $extraField): View
    {
        $extraField->load('options');
        return view('admin.extra-fields.show', compact('extraField'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(ExtraField $extraField): View
    {
        return view('admin.extra-fields.edit', compact('extraField'));
    }

    /**
     * Atualizar campo
     */
    public function update(Request $request, ExtraField $extraField): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,select,radio,checkbox,image,color,number,date,file',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Gerar slug único apenas se o nome foi alterado
        if ($validated['name'] !== $extraField->name) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $count = 1;
            
            // Verificar se o slug já existe (excluindo o campo atual)
            while (ExtraField::where('slug', $slug)->where('id', '!=', $extraField->id)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            
            $validated['slug'] = $slug;
        }

        $extraField->update($validated);

        return redirect()->route('admin.extra-fields.index')
            ->with('success', 'Campo atualizado com sucesso!');
    }

    /**
     * Excluir campo
     */
    public function destroy(ExtraField $extraField): RedirectResponse
    {
        $extraField->delete();

        return redirect()->route('admin.extra-fields.index')
            ->with('success', 'Campo excluído com sucesso!');
    }

    /**
     * Gerenciar opções do campo
     */
    public function options(ExtraField $extraField)
    {
        $options = $extraField->options()->orderBy('sort_order')->get();
        
        
        // Se for uma requisição AJAX, retornar JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'options' => $options->map(function ($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'price' => number_format((float)$option->price, 2, ',', '.'),
                        'price_type' => $option->price_type,
                        'is_active' => $option->is_active
                    ];
                })
            ]);
        }
        
        return view('admin.extra-fields.options', compact('extraField', 'options'));
    }

    /**
     * Salvar opções do campo
     */
    public function saveOptions(Request $request, ExtraField $extraField)
    {
        $validated = $request->validate([
            'options' => 'required|array',
            'options.*.value' => 'required|string|max:255',
            'options.*.label' => 'required|string|max:255',
            'options.*.image_url' => 'nullable|string|max:500',
            'options.*.color_hex' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'options.*.price' => 'required|numeric|min:0',
            'options.*.price_type' => 'required|string|in:fixed,percentage,per_unit,per_area',
            'options.*.is_active' => 'boolean',
        ]);

        // Limpar opções existentes
        $extraField->options()->delete();

        // Criar novas opções
        foreach ($validated['options'] as $index => $optionData) {
            $extraField->options()->create([
                'value' => $optionData['value'],
                'label' => $optionData['label'],
                'image_url' => $optionData['image_url'] ?? null,
                'color_hex' => $optionData['color_hex'] ?? null,
                'price' => $optionData['price'],
                'price_type' => $optionData['price_type'],
                'sort_order' => $index + 1,
                'is_active' => $optionData['is_active'] ?? true,
            ]);
        }

        // Se for uma requisição AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Opções salvas com sucesso!'
            ]);
        }

        return redirect()->route('admin.extra-fields.options', $extraField)
            ->with('success', 'Opções salvas com sucesso!');
    }

    /**
     * Alternar status do campo
     */
    public function toggle(ExtraField $extraField): RedirectResponse
    {
        $extraField->update(['is_active' => !$extraField->is_active]);

        $status = $extraField->is_active ? 'ativado' : 'desativado';
        return redirect()->back()
            ->with('success', "Campo {$status} com sucesso!");
    }

    /**
     * Reordenar campos
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:extra_fields,id',
        ]);

        foreach ($validated['order'] as $index => $fieldId) {
            ExtraField::where('id', $fieldId)->update(['sort_order' => $index + 1]);
        }

        // Se for uma requisição AJAX, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ordem atualizada com sucesso!'
            ]);
        }

        // Se for uma requisição normal, redirecionar
        return redirect()->back()
            ->with('success', 'Ordem atualizada com sucesso!');
    }
}
