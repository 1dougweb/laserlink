@extends('layouts.store')

@section('title', 'Site em manutenção - 503 | ' . config('app.name'))

@section('meta_description', 'Estamos realizando uma manutenção programada para melhorar sua experiência. Voltaremos em breve!')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Ilustração do erro -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-black text-blue-200 select-none">503</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-tools text-6xl text-blue-600 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Mensagem -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Estamos em manutenção
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Estamos realizando melhorias para proporcionar uma experiência ainda melhor para você. 
            Voltaremos em breve!
        </p>

        <!-- Animação de loading -->
        <div class="mb-12">
            <div class="inline-flex items-center space-x-2">
                <div class="w-3 h-3 bg-primary rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
            <p class="text-sm text-gray-500 mt-4">Trabalhando nas melhorias...</p>
        </div>

        <!-- Informações -->
        <div class="bg-white rounded-xl shadow-md p-8 border border-gray-200 mb-8">
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <i class="bi bi-clock-history text-3xl text-blue-600 mb-3 block"></i>
                    <h3 class="font-semibold text-gray-900 mb-2">Tempo estimado</h3>
                    <p class="text-sm text-gray-600">Alguns minutos</p>
                </div>
                <div>
                    <i class="bi bi-shield-check text-3xl text-green-600 mb-3 block"></i>
                    <h3 class="font-semibold text-gray-900 mb-2">Seus dados</h3>
                    <p class="text-sm text-gray-600">Estão seguros</p>
                </div>
                <div>
                    <i class="bi bi-rocket text-3xl text-purple-600 mb-3 block"></i>
                    <h3 class="font-semibold text-gray-900 mb-2">Novidades</h3>
                    <p class="text-sm text-gray-600">Melhorias chegando</p>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">
                <i class="bi bi-info-circle mr-2"></i>
                Precisa falar conosco?
            </h2>
            <p class="text-gray-600 mb-4 text-sm">
                Se for urgente, você pode entrar em contato por:
            </p>
            <div class="flex flex-wrap gap-3 justify-center">
                @php
                    $whatsapp = \App\Models\Setting::get('whatsapp');
                    $email = \App\Models\Setting::get('email');
                @endphp
                
                @if($whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" 
                       target="_blank"
                       class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm">
                        <i class="bi bi-whatsapp mr-2"></i>
                        WhatsApp
                    </a>
                @endif
                
                @if($email)
                    <a href="mailto:{{ $email }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                        <i class="bi bi-envelope mr-2"></i>
                        E-mail
                    </a>
                @endif
            </div>
        </div>

        <!-- Auto-reload -->
        <div class="mt-8">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-6 py-2.5 bg-primary hover:bg-red-700 text-white rounded-lg transition-colors text-sm">
                <i class="bi bi-arrow-clockwise mr-2"></i>
                Verificar novamente
            </button>
        </div>
    </div>
</div>

<script>
    // Auto-reload a cada 30 segundos
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>

@endsection


