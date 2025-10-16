<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppearanceSettingsRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sidebar_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:4096',
            'blog_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'footer_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|file|mimes:png,jpg,jpeg,gif,svg,ico|max:1024',
            'image_url' => 'nullable|string',
            'sidebar_image_url' => 'nullable|string',
            'site_image_url' => 'nullable|string',
            'blog_image_url' => 'nullable|string',
            'footer_image_url' => 'nullable|string',
            'favicon_image_url' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            // Redes Sociais
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_pinterest' => 'nullable|url|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'logo.file' => 'O arquivo da logo deve ser um arquivo válido.',
            'logo.mimes' => 'A logo deve ser um arquivo do tipo: jpeg, png, jpg, gif ou svg.',
            'logo.max' => 'A logo não pode ser maior que 2MB.',
            'sidebar_logo.file' => 'O arquivo da logo do sidebar deve ser um arquivo válido.',
            'sidebar_logo.mimes' => 'A logo do sidebar deve ser um arquivo do tipo: jpeg, png, jpg, gif ou svg.',
            'sidebar_logo.max' => 'A logo do sidebar não pode ser maior que 2MB.',
            'blog_logo.file' => 'O arquivo da logo do blog deve ser um arquivo válido.',
            'blog_logo.mimes' => 'A logo do blog deve ser um arquivo do tipo: jpeg, png, jpg, gif, svg ou webp.',
            'blog_logo.max' => 'A logo do blog não pode ser maior que 2MB.',
            'footer_logo.file' => 'O arquivo da logo do rodapé deve ser um arquivo válido.',
            'footer_logo.mimes' => 'A logo do rodapé deve ser um arquivo do tipo: jpeg, png, jpg, gif, svg ou webp.',
            'footer_logo.max' => 'A logo do rodapé não pode ser maior que 2MB.',
            'favicon.file' => 'O arquivo do favicon deve ser um arquivo válido.',
            'favicon.mimes' => 'O favicon deve ser um arquivo do tipo: png, jpg, jpeg, gif, svg ou ico.',
            'favicon.max' => 'O favicon não pode ser maior que 1MB.',
            'image_url.string' => 'A URL da imagem deve ser uma string válida.',
            'sidebar_image_url.string' => 'A URL da imagem do sidebar deve ser uma string válida.',
            'site_image_url.string' => 'A URL da imagem do site deve ser uma string válida.',
            'blog_image_url.string' => 'A URL da imagem do blog deve ser uma string válida.',
            'footer_image_url.string' => 'A URL da imagem do rodapé deve ser uma string válida.',
            'favicon_image_url.string' => 'A URL do favicon deve ser uma string válida.',
            'primary_color.max' => 'A cor primária deve ter no máximo 7 caracteres.',
            'secondary_color.max' => 'A cor secundária deve ter no máximo 7 caracteres.',
            'accent_color.max' => 'A cor de destaque deve ter no máximo 7 caracteres.',
            // Redes Sociais
            'social_facebook.url' => 'O URL do Facebook deve ser válido.',
            'social_instagram.url' => 'O URL do Instagram deve ser válido.',
            'social_twitter.url' => 'O URL do Twitter deve ser válido.',
            'social_linkedin.url' => 'O URL do LinkedIn deve ser válido.',
            'social_youtube.url' => 'O URL do YouTube deve ser válido.',
            'social_tiktok.url' => 'O URL do TikTok deve ser válido.',
            'social_pinterest.url' => 'O URL do Pinterest deve ser válido.',
        ];
    }
}
