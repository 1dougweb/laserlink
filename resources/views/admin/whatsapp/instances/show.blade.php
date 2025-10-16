@extends('admin.layout')

@section('title', 'Detalhes da Instância')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $instance->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $instance->purpose_label }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.whatsapp.instances.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
                <i class="bi bi-arrow-left mr-2"></i> Voltar
            </a>
            @if($instance->status === 'disconnected')
                <button onclick="refreshQRCode()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all">
                    <i class="bi bi-arrow-clockwise mr-2"></i> Atualizar QR
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações da Instância -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações da Instância</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nome</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $instance->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Finalidade</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $instance->purpose_label }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $instance->status_color }} mt-1">
                            {{ $instance->status_label }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Ativa</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $instance->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} mt-1">
                            {{ $instance->is_active ? 'Sim' : 'Não' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Criada em</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $instance->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Última atualização</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $instance->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $instance->notifications_count }}</div>
                        <div class="text-sm text-gray-600">Total de Mensagens</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $instance->sent_count }}</div>
                        <div class="text-sm text-gray-600">Enviadas</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $instance->delivered_count }}</div>
                        <div class="text-sm text-gray-600">Entregues</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $instance->failed_count }}</div>
                        <div class="text-sm text-gray-600">Falharam</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code e Ações -->
        <div class="space-y-6">
            @if($instance->status === 'disconnected')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Conectar WhatsApp</h3>
                    
                    <div class="text-center">
                        <div id="qrcode-container" class="mb-4">
                            @if($instance->qr_code)
                                <img src="{{ $instance->qr_code }}" 
                                     alt="QR Code" 
                                     class="mx-auto border border-gray-300 rounded-lg">
                            @else
                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8">
                                    <i class="bi bi-qr-code text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500">QR Code não disponível</p>
                                </div>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4">
                            Escaneie este QR Code com seu WhatsApp para conectar a instância.
                        </p>
                        
                        <button onclick="refreshQRCode()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all text-sm">
                            <i class="bi bi-arrow-clockwise mr-2"></i> Atualizar QR Code
                        </button>
                    </div>
                </div>
            @elseif($instance->status === 'connected')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="bi bi-check-circle text-6xl text-green-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">WhatsApp Conectado</h3>
                        <p class="text-sm text-gray-600">
                            A instância está conectada e pronta para enviar mensagens.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.whatsapp.instances.toggle', $instance) }}">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $instance->is_active ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-4 py-2 rounded-lg transition-all text-sm font-medium">
                            <i class="bi bi-power mr-2"></i> 
                            {{ $instance->is_active ? 'Desativar' : 'Ativar' }} Instância
                        </button>
                    </form>
                    
                    @if($instance->status === 'disconnected')
                        <button onclick="refreshQRCode()" 
                                class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg transition-all text-sm font-medium">
                            <i class="bi bi-arrow-clockwise mr-2"></i> Atualizar QR Code
                        </button>
                    @endif
                    
                    <button onclick="checkStatus()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-all text-sm font-medium">
                        <i class="bi bi-arrow-clockwise mr-2"></i> Verificar Status
                    </button>
                    
                    <form method="POST" action="{{ route('admin.whatsapp.instances.destroy', $instance) }}" 
                          onsubmit="return confirm('Tem certeza que deseja deletar esta instância? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg transition-all text-sm font-medium">
                            <i class="bi bi-trash mr-2"></i> Deletar Instância
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshQRCode() {
    const container = document.getElementById('qrcode-container');
    container.innerHTML = '<div class="text-center"><i class="bi bi-arrow-clockwise animate-spin text-2xl text-blue-500"></i><p class="text-sm text-gray-600 mt-2">Atualizando QR Code...</p></div>';
    
    fetch('{{ route("admin.whatsapp.instances.refresh-qr", $instance) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            container.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><p class="text-sm">Erro ao atualizar QR Code</p></div>';
        }
    })
    .catch(error => {
        container.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><p class="text-sm">Erro de conexão</p></div>';
    });
}

function checkStatus() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin mr-2"></i> Verificando...';
    button.disabled = true;
    
    fetch('{{ route("admin.whatsapp.instances.status", $instance) }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao verificar status: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        alert('Erro de conexão ao verificar status');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endsection