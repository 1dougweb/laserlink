<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Por favor, informe um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'subject.required' => 'O assunto é obrigatório.',
            'subject.max' => 'O assunto não pode ter mais de 255 caracteres.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.max' => 'A mensagem não pode ter mais de 5000 caracteres.',
        ];
    }
}

