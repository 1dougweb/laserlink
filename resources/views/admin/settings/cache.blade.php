@extends('admin.layout')

@section('title', 'Configurações - Cache')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gerenciamento de Cache</h1>
        <p class="text-gray-600 mt-1">Controle o cache do sistema para melhorar a performance</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Status do Cache -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-speedometer2 text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Status do Cache</h3>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Cache do Sistema</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $cacheEnabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="bi {{ $cacheEnabled ? 'bi-check-circle' : 'bi-x-circle' }} mr-1"></i>{{ $cacheEnabled ? 'Ativado' : 'Desativado' }}
                    </span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Cache de Configuração</span>
                    <span class="text-sm text-gray-900">{{ file_exists(base_path('bootstrap/cache/config.php')) ? 'Ativo' : 'Inativo' }}</span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Cache de Rotas</span>
                    <span class="text-sm text-gray-900">{{ file_exists(base_path('bootstrap/cache/routes-v7.php')) ? 'Ativo' : 'Inativo' }}</span>
                </div>

                <div class="flex items-center justify-between py-3">
                    <span class="text-sm font-medium text-gray-700">Cache de Views</span>
                    <span class="text-sm text-gray-900">{{ count(glob(storage_path('framework/views').'/*')) > 0 ? 'Ativo' : 'Inativo' }}</span>
                </div>
            </div>

            <!-- Ativar/Desativar Cache -->
            <form action="{{ route('admin.settings.cache.toggle') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="enabled" value="{{ $cacheEnabled ? '0' : '1' }}">
                <button type="submit" 
                        class="w-full px-6 py-3 {{ $cacheEnabled ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors font-semibold shadow-md hover:shadow-lg">
                    <i class="bi {{ $cacheEnabled ? 'bi-x-circle' : 'bi-check-circle' }} mr-2"></i>{{ $cacheEnabled ? 'Desativar Cache' : 'Ativar Cache' }}
                </button>
            </form>

            <!-- Limpar Cache -->
            <form action="{{ route('admin.settings.cache.clear') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" 
                        class="w-full px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                    <i class="bi bi-trash mr-2"></i>Limpar Todo o Cache
                </button>
            </form>
        </div>

        <!-- Informações e Dicas -->
        <div class="space-y-6">
            
            <!-- O que é Cache -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <i class="bi bi-info-circle text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">O que é Cache?</h3>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">
                    O cache armazena informações processadas para acelerar o carregamento do site. 
                    Com o cache ativado, seu site ficará mais rápido, mas pode não refletir mudanças imediatamente.
                </p>

                <div class="space-y-3 text-sm text-gray-700">
                    <h4 class="font-semibold text-gray-900">Tipos de Cache:</h4>
                    <ul class="space-y-2 ml-2">
                        <li class="flex items-start">
                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                            <span><strong>Configuração:</strong> Armazena as configurações do sistema</span>
                        </li>
                        <li class="flex items-start">
                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                            <span><strong>Rotas:</strong> Acelera o roteamento de URLs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                            <span><strong>Views:</strong> Compila as páginas em HTML otimizado</span>
                        </li>
                        <li class="flex items-start">
                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                            <span><strong>Aplicação:</strong> Armazena dados temporários</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quando Limpar -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="text-sm font-semibold text-blue-900 mb-3">
                    <i class="bi bi-clock-history mr-1"></i>Quando limpar o cache:
                </h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i class="bi bi-arrow-right-short text-blue-600 mr-1 mt-0.5"></i>
                        <span>Após alterar configurações do sistema</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-arrow-right-short text-blue-600 mr-1 mt-0.5"></i>
                        <span>Quando mudanças não aparecem no site</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-arrow-right-short text-blue-600 mr-1 mt-0.5"></i>
                        <span>Após atualizar produtos ou categorias</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-arrow-right-short text-blue-600 mr-1 mt-0.5"></i>
                        <span>Se o site apresentar comportamento inesperado</span>
                    </li>
                </ul>
            </div>

            <!-- Recomendações -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h4 class="text-sm font-semibold text-green-900 mb-3">
                    <i class="bi bi-lightbulb mr-1"></i>Recomendações:
                </h4>
                <ul class="space-y-2 text-sm text-green-800">
                    <li class="flex items-start">
                        <i class="bi bi-check2-circle text-green-600 mr-1 mt-0.5"></i>
                        <span><strong>Desenvolvimento:</strong> Desative o cache para ver mudanças em tempo real</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-check2-circle text-green-600 mr-1 mt-0.5"></i>
                        <span><strong>Produção:</strong> Mantenha o cache ativado para melhor performance</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-check2-circle text-green-600 mr-1 mt-0.5"></i>
                        <span>Limpe o cache regularmente após grandes alterações</span>
                    </li>
                </ul>
            </div>

            <!-- Aviso -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h4 class="text-sm font-semibold text-yellow-900 mb-3">
                    <i class="bi bi-exclamation-triangle mr-1"></i>Atenção:
                </h4>
                <p class="text-sm text-yellow-800">
                    Limpar o cache pode deixar o site um pouco mais lento temporariamente, 
                    pois os dados precisarão ser processados novamente na primeira visita após a limpeza.
                </p>
            </div>

        </div>
    </div>

</div>
@endsection

