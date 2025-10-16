<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\WhatsAppInstance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWhatsAppInstanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Será validado pelo middleware de admin
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'purpose' => [
                'required',
                'string',
                Rule::in(['orders', 'promotions', 'support']),
                function ($attribute, $value, $fail) {
                    // Verificar se já existe uma instância ativa para esta finalidade
                    $existingCount = WhatsAppInstance::where('purpose', $value)
                                                   ->where('is_active', true)
                                                   ->count();
                    
                    if ($existingCount > 0) {
                        $fail('Já existe uma instância ativa para a finalidade: ' . $this->getPurposeLabel($value));
                    }
                },
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da instância é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'purpose.required' => 'A finalidade da instância é obrigatória.',
            'purpose.in' => 'A finalidade deve ser: Pedidos, Promoções ou Suporte.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Verificar limite máximo de instâncias
            if (!WhatsAppInstance::canCreateNew()) {
                $validator->errors()->add('limit', 'Limite máximo de 3 instâncias atingido.');
            }
        });
    }

    /**
     * Get the purpose label for error messages
     */
    private function getPurposeLabel(string $purpose): string
    {
        return match($purpose) {
            'orders' => 'Pedidos',
            'promotions' => 'Promoções',
            'support' => 'Suporte',
            default => $purpose
        };
    }
}
