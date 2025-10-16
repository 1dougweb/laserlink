<?php

declare(strict_types=1);

namespace App\Helpers;

class AssetHelper
{
    /**
     * Gera URL de asset usando a URL atual da requisição
     * Funciona tanto em localhost quanto em IP (para acesso mobile)
     */
    public static function asset(string $path): string
    {
        // Remove barras duplicadas
        $path = ltrim($path, '/');
        
        // Se for uma URL completa, retorna como está
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        
        // Usa url() que pega o host atual da requisição
        return url($path);
    }

    /**
     * Gera URL de storage usando a URL atual da requisição
     */
    public static function storage(string $path): string
    {
        $path = ltrim($path, '/');
        return self::asset("storage/{$path}");
    }

    /**
     * Gera URL absoluta usando o domínio atual
     */
    public static function absoluteUrl(string $path): string
    {
        return self::asset($path);
    }
}

