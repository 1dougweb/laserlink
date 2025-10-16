<?php

/**
 * Laravel Application Entry Point Redirect
 * 
 * Este arquivo redireciona todas as requisições para a pasta public,
 * que é o document root correto da aplicação Laravel.
 * 
 * Nota: O ideal é configurar o servidor web para apontar diretamente
 * para a pasta public. Este arquivo é uma solução alternativa.
 */

// Verifica se a requisição é para a pasta public
if (php_sapi_name() === 'cli-server') {
    // Para o servidor embutido do PHP
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . '/public' . $url['path'];
    
    if (is_file($file)) {
        return false;
    }
}

// Redireciona para o index.php da pasta public
require_once __DIR__ . '/public/index.php';

