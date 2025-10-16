<?php

use App\Helpers\AssetHelper;

if (!function_exists('dynamic_asset')) {
    /**
     * Gera URL de asset usando o domínio atual (funciona com IP em mobile)
     */
    function dynamic_asset(string $path): string
    {
        return AssetHelper::asset($path);
    }
}

if (!function_exists('dynamic_storage')) {
    /**
     * Gera URL de storage usando o domínio atual
     */
    function dynamic_storage(string $path): string
    {
        return AssetHelper::storage($path);
    }
}

if (!function_exists('resolve_image_url')) {
    /**
     * Resolve o caminho correto da imagem baseado no novo sistema de file manager
     * Suporta: URLs completas, caminhos absolutos, storage/, images/, e caminhos relativos
     */
    function resolve_image_url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // Se já é uma URL completa, retorna como está
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Se é um caminho absoluto (começa com /), retorna como está
        if (str_starts_with($path, '/')) {
            return $path;
        }

        // Se começa com 'storage/', converte para 'images/'
        if (str_starts_with($path, 'storage/')) {
            $path = str_replace('storage/', 'images/', $path);
            return url($path);
        }

        // Se começa com 'images/', retorna URL completa
        if (str_starts_with($path, 'images/')) {
            return url($path);
        }

        // Caso contrário, assume que está em images/
        return url('images/' . $path);
    }
}

