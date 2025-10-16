<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WhatsAppTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = WhatsAppMessageTemplate::orderBy('template_type')->get();
        
        return view('admin.whatsapp.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templateTypes = $this->getTemplateTypes();
        $defaultVariables = WhatsAppMessageTemplate::getDefaultVariables();
        
        return view('admin.whatsapp.templates.create', compact('templateTypes', 'defaultVariables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'template_type' => 'required|string|max:255|unique:whatsapp_message_templates,template_type',
            'message_template' => 'required|string|min:10',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'O nome do template é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'template_type.required' => 'O tipo do template é obrigatório.',
            'template_type.unique' => 'Já existe um template para este tipo.',
            'message_template.required' => 'O conteúdo do template é obrigatório.',
            'message_template.min' => 'O template deve ter pelo menos 10 caracteres.',
        ]);

        $template = WhatsAppMessageTemplate::create([
            'name' => $request->name,
            'template_type' => $request->template_type,
            'message_template' => $request->message_template,
            'variables' => $this->extractVariables($request->message_template),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.whatsapp.templates.index')
                       ->with('success', 'Template criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WhatsAppMessageTemplate $template)
    {
        $defaultVariables = WhatsAppMessageTemplate::getDefaultVariables();
        $preview = $template->getPreview();
        
        return view('admin.whatsapp.templates.show', compact('template', 'defaultVariables', 'preview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WhatsAppMessageTemplate $template)
    {
        $templateTypes = $this->getTemplateTypes();
        $defaultVariables = WhatsAppMessageTemplate::getDefaultVariables();
        $preview = $template->getPreview();
        
        return view('admin.whatsapp.templates.edit', compact('template', 'templateTypes', 'defaultVariables', 'preview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WhatsAppMessageTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'template_type' => 'required|string|max:255|unique:whatsapp_message_templates,template_type,' . $template->id,
            'message_template' => 'required|string|min:10',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'O nome do template é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'template_type.required' => 'O tipo do template é obrigatório.',
            'template_type.unique' => 'Já existe um template para este tipo.',
            'message_template.required' => 'O conteúdo do template é obrigatório.',
            'message_template.min' => 'O template deve ter pelo menos 10 caracteres.',
        ]);

        $template->update([
            'name' => $request->name,
            'template_type' => $request->template_type,
            'message_template' => $request->message_template,
            'variables' => $this->extractVariables($request->message_template),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.whatsapp.templates.index')
                       ->with('success', 'Template atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhatsAppMessageTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.whatsapp.templates.index')
                       ->with('success', 'Template deletado com sucesso!');
    }

    /**
     * Preview template with sample data
     */
    public function preview(Request $request): JsonResponse
    {
        $request->validate([
            'message_template' => 'required|string|min:10',
            'sample_data' => 'nullable|array',
        ]);

        $sampleData = $request->input('sample_data', []);
        
        $defaultData = [
            'customer_name' => 'João Silva',
            'order_number' => '#12345',
            'status' => 'Confirmado',
            'total' => 'R$ 150,00',
            'tracking_code' => 'BR123456789',
            'company_name' => 'Laser Link',
            'product_name' => 'Placa Personalizada',
            'quantity' => '2',
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i'),
            'customer_phone' => '(11) 99999-9999',
            'customer_email' => 'joao@exemplo.com',
            'shipping_address' => 'Rua das Flores, 123, Centro - São Paulo/SP - CEP: 01234-567',
        ];

        $data = array_merge($defaultData, $sampleData);
        $message = $request->message_template;
        
        foreach ($data as $key => $value) {
            $message = str_replace('{' . $key . '}', (string) $value, $message);
        }

        return response()->json([
            'success' => true,
            'preview' => $message,
            'variables_found' => $this->extractVariables($request->message_template),
        ]);
    }

    /**
     * Toggle template active status
     */
    public function toggleActive(WhatsAppMessageTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);

        $status = $template->is_active ? 'ativado' : 'desativado';

        return redirect()->back()
                       ->with('success', "Template {$status} com sucesso!");
    }

    /**
     * Duplicate template
     */
    public function duplicate(WhatsAppMessageTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Cópia)';
        $newTemplate->is_active = false;
        $newTemplate->save();

        return redirect()->route('admin.whatsapp.templates.edit', $newTemplate)
                       ->with('success', 'Template duplicado com sucesso!');
    }

    /**
     * Get available template types
     */
    private function getTemplateTypes(): array
    {
        return [
            'order_pending' => 'Pedido Pendente',
            'order_confirmed' => 'Pedido Confirmado',
            'order_processing' => 'Pedido Processando',
            'order_shipped' => 'Pedido Enviado',
            'order_delivered' => 'Pedido Entregue',
            'order_cancelled' => 'Pedido Cancelado',
            'promotion_general' => 'Promoção Geral',
            'cart_abandonment' => 'Carrinho Abandonado',
            'welcome_new_customer' => 'Boas-vindas Cliente Novo',
            'custom' => 'Personalizado',
        ];
    }

    /**
     * Extract variables from template message
     */
    private function extractVariables(string $message): array
    {
        preg_match_all('/\{([^}]+)\}/', $message, $matches);
        return array_unique($matches[1] ?? []);
    }
}

