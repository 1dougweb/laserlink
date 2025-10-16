@extends('admin.layout')

@section('title', 'Configurações do Sistema')
@section('page-title', 'Configurações')

@section('content')
<div class="space-y-6">
    
    <!-- Settings Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Configurações Gerais -->
        <a href="{{ route('admin.settings.general') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="bi bi-gear text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        Configurações Gerais
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Nome, descrição e contatos</p>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

        <!-- WhatsApp -->
        <a href="{{ route('admin.settings.whatsapp') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <i class="bi bi-whatsapp text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        WhatsApp
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Integração e mensagens</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-2 {{ \App\Models\Setting::get('whatsapp_enabled') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ \App\Models\Setting::get('whatsapp_enabled') ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

        <!-- Email/SMTP -->
        <a href="{{ route('admin.settings.email') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-purple-100 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <i class="bi bi-envelope-at text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        Email/SMTP
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Servidor e notificações</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-2 bg-purple-100 text-purple-800">
                        {{ strtoupper(\App\Models\Setting::get('mail_mailer', 'SMTP')) }}
                    </span>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

        <!-- Aparência -->
        <a href="{{ route('admin.settings.appearance') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-pink-100 text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                    <i class="bi bi-palette text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        Aparência
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Logo, cores e temas</p>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

        <!-- SEO -->
        <a href="{{ route('admin.settings.seo') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="bi bi-search text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        SEO
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Meta tags e otimizações</p>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

        <!-- Gemini AI -->
        <a href="{{ route('admin.settings.gemini') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-yellow-100 text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                    <i class="bi bi-robot text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        Gemini AI
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Inteligência Artificial</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-2 {{ \App\Models\Setting::get('gemini_enabled') ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ \App\Models\Setting::get('gemini_enabled') ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>


        <!-- Sitemap -->
        <a href="{{ route('admin.settings.sitemap') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-teal-100 text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="bi bi-diagram-3 text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        Sitemap
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Google Search Console</p>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

        <!-- Loja Virtual -->
        <a href="{{ route('admin.store-settings') }}" 
           class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6 group">
            <div class="flex items-center">
                <div class="selo rounded-full bg-orange-100 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i class="bi bi-shop text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        Loja Virtual
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Menu e banners</p>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
            </div>
        </a>

    </div>

</div>
@endsection
