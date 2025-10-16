@extends('layouts.store')

@section('title', 'Contato - LaserLink')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <i class="bi bi-envelope-heart-fill text-red-600 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Entre em Contato</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Estamos aqui para ajudar! Entre em contato conosco através do formulário abaixo ou pelos nossos canais de atendimento.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações de Contato -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Telefone -->
                @if($contactInfo['phone'])
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                <i class="bi bi-telephone-fill text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Telefone</h3>
                            <a href="tel:{{ preg_replace('/\D/', '', $contactInfo['phone']) }}" class="text-red-600 hover:text-red-700 mt-1 block font-medium">
                                {{ $contactInfo['phone'] }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- WhatsApp -->
                @if($contactInfo['whatsapp'])
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                <i class="bi bi-whatsapp text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">WhatsApp</h3>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contactInfo['whatsapp']) }}" target="_blank" class="text-green-600 hover:text-green-700 mt-1 block font-medium">
                                {{ $contactInfo['whatsapp'] }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- E-mail -->
                @if($contactInfo['email'])
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                <i class="bi bi-envelope-fill text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">E-mail</h3>
                            <a href="mailto:{{ $contactInfo['email'] }}" class="text-red-600 hover:text-red-700 mt-1 block break-all font-medium">
                                {{ $contactInfo['email'] }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Endereço -->
                @if($contactInfo['address'])
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                <i class="bi bi-geo-alt-fill text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Endereço</h3>
                            <p class="text-gray-600 mt-1">{{ $contactInfo['address'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Horário de Funcionamento -->
                @if($contactInfo['business_hours'])
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                <i class="bi bi-clock-fill text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Horário de Funcionamento</h3>
                            <p class="text-gray-600 mt-1 whitespace-pre-line">{{ $contactInfo['business_hours'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Redes Sociais -->
                @if(count($socialMedia) > 0)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Redes Sociais</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($socialMedia as $name => $social)
                            <a href="{{ $social['url'] }}" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="flex items-center justify-center w-12 h-12 {{ $social['color'] }} text-white rounded-lg transition-all duration-300 hover:scale-110 hover:shadow-lg"
                               title="{{ $social['name'] }}">
                                <i class="bi {{ $social['icon'] }} text-xl"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Formulário de Contato -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Envie sua Mensagem</h2>

                    <!-- Mensagens de Feedback -->
                    @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <i class="bi bi-check-circle-fill text-lg mr-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                        <i class="bi bi-exclamation-circle-fill text-lg mr-2"></i>
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome Completo <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="Seu nome completo"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- E-mail -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    E-mail <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                    placeholder="seu@email.com"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Telefone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telefone
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                    placeholder="(00) 00000-0000"
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assunto -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assunto <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="subject" 
                                    name="subject" 
                                    value="{{ old('subject') }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                                    placeholder="Assunto da mensagem"
                                >
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Mensagem -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('message') border-red-500 @enderror"
                                placeholder="Escreva sua mensagem aqui..."
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botão de Envio -->
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                <span class="text-red-500">*</span> Campos obrigatórios
                            </p>
                            <button 
                                type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors duration-200 flex items-center shadow-md hover:shadow-lg"
                            >
                                <i class="bi bi-send-fill mr-2"></i>
                                Enviar Mensagem
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Informação Adicional -->
                <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-info-circle-fill text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Tempo de Resposta</h3>
                            <p class="text-red-800">
                                Responderemos sua mensagem em até 24 horas úteis. Para atendimento mais rápido, entre em contato pelo WhatsApp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mapa de Localização -->
        @if($contactInfo['map_embed_url'])
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Nossa Localização</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="w-full h-96 lg:h-[500px]">
                    <iframe 
                        src="{{ $contactInfo['map_embed_url'] }}" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full">
                    </iframe>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Perguntas Frequentes -->
        @if(count($faqs) > 0)
        <div class="mt-12">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                    <i class="bi bi-question-circle-fill text-red-600 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Perguntas Frequentes</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Encontre respostas para as dúvidas mais comuns sobre nossos produtos e serviços
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto space-y-4" x-data="{ activeAccordion: null }">
                @foreach($faqs as $index => $faq)
                <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                    <button 
                        @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                        type="button"
                        class="w-full px-6 py-5 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-start flex-1">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mr-4">
                                <i class="bi bi-question-lg text-red-600 font-bold"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $faq['question'] ?? '' }}</h3>
                            </div>
                        </div>
                        <i class="bi text-red-600 text-xl ml-4 transition-transform duration-200"
                           :class="activeAccordion === {{ $index }} ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    
                    <div x-show="activeAccordion === {{ $index }}" 
                         x-collapse
                         class="px-6 pb-5">
                        <div class="pl-12 text-gray-700 leading-relaxed">
                            {{ $faq['answer'] ?? '' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Máscara de telefone
    document.getElementById('phone')?.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
        }
        e.target.value = value;
    });
</script>
@endpush
@endsection

