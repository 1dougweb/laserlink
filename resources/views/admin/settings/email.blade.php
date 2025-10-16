@extends('admin.layout')

@section('title', 'Configurações - Email/SMTP')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Configurações - Email/SMTP</h1>
                <p class="text-gray-600 mt-1">Configure o servidor SMTP para envio de emails automáticos</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.email.update') }}" x-data="emailSettings()">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Configurações SMTP -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <i class="bi bi-envelope-at text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Servidor SMTP</h3>
                </div>
                
                <div class="space-y-6">
                    <!-- Driver -->
                    <div>
                        <label for="mail_mailer" class="block text-sm font-medium text-gray-700 mb-2">
                            Driver de Email <span class="text-red-500">*</span>
                        </label>
                        <select id="mail_mailer" 
                                name="mail_mailer" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="smtp" {{ $settings['mail_mailer'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ $settings['mail_mailer'] === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ $settings['mail_mailer'] === 'log' ? 'selected' : '' }}>Log (apenas desenvolvimento)</option>
                        </select>
                    </div>

                    <!-- Host -->
                    <div>
                        <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">
                            Host SMTP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="mail_host" 
                               name="mail_host" 
                               value="{{ old('mail_host', $settings['mail_host']) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="smtp.gmail.com">
                        <p class="text-xs text-gray-500 mt-1">
                            Exemplos: smtp.gmail.com, smtp.office365.com
                        </p>
                    </div>

                    <!-- Porta -->
                    <div>
                        <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">
                            Porta SMTP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="mail_port" 
                               name="mail_port" 
                               value="{{ old('mail_port', $settings['mail_port']) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="587">
                        <p class="text-xs text-gray-500 mt-1">
                            Comum: 587 (TLS), 465 (SSL), 25 (sem criptografia)
                        </p>
                    </div>

                    <!-- Encriptação -->
                    <div>
                        <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-2">
                            Encriptação <span class="text-red-500">*</span>
                        </label>
                        <select id="mail_encryption" 
                                name="mail_encryption" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="tls" {{ $settings['mail_encryption'] === 'tls' ? 'selected' : '' }}>TLS (Recomendado)</option>
                            <option value="ssl" {{ $settings['mail_encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ $settings['mail_encryption'] === '' ? 'selected' : '' }}>Nenhuma</option>
                        </select>
                    </div>

                    <!-- Usuário -->
                    <div>
                        <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">
                            Usuário SMTP
                        </label>
                        <input type="text" 
                               id="mail_username" 
                               name="mail_username" 
                               value="{{ old('mail_username', $settings['mail_username']) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="seu-email@gmail.com"
                               autocomplete="off">
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha SMTP
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   id="mail_password" 
                                   name="mail_password" 
                                   value="{{ old('mail_password', $settings['mail_password']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary pr-10"
                                   placeholder="Digite a senha SMTP"
                                   autocomplete="off">
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i :class="showPassword ? 'bi-eye-slash' : 'bi-eye'" class="text-gray-400"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Para Gmail, use uma Senha de App (não sua senha normal)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Configurações de Remetente e Notificações -->
            <div class="space-y-6">
                <!-- Remetente -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-6">
                        <i class="bi bi-person-badge text-primary text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Informações do Remetente</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Email Remetente -->
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Remetente <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="mail_from_address" 
                                   name="mail_from_address" 
                                   value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   placeholder="contato@seudominio.com.br"
                                   required>
                            <p class="text-xs text-yellow-700 bg-yellow-50 border border-yellow-200 rounded p-2 mt-2">
                                ⚠️ <strong>Importante:</strong> O email remetente DEVE ser o mesmo que o usuário SMTP. A maioria dos servidores rejeita emails de remetentes diferentes.
                            </p>
                        </div>

                        <!-- Nome Remetente -->
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome Remetente <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="mail_from_name" 
                                   name="mail_from_name" 
                                   value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   placeholder="Laser Link"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Notificações -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-6">
                        <i class="bi bi-bell text-primary text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Preferências de Notificações</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Enviar Email de Boas-Vindas -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="send_welcome_email" 
                                       name="send_welcome_email" 
                                       value="1"
                                       {{ $settings['send_welcome_email'] ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="send_welcome_email" class="text-sm font-medium text-gray-700">
                                    Enviar email de boas-vindas
                                </label>
                                <p class="text-xs text-gray-500">
                                    Envia credenciais de acesso para novos usuários
                                </p>
                            </div>
                        </div>

                        <!-- Confirmar Pedido -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="send_order_confirmation" 
                                       name="send_order_confirmation" 
                                       value="1"
                                       {{ $settings['send_order_confirmation'] ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="send_order_confirmation" class="text-sm font-medium text-gray-700">
                                    Enviar confirmação de pedido
                                </label>
                                <p class="text-xs text-gray-500">
                                    Envia email ao cliente após finalizar pedido
                                </p>
                            </div>
                        </div>

                        <!-- Notificar Novo Usuário -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="notify_new_user" 
                                       name="notify_new_user" 
                                       value="1"
                                       {{ $settings['notify_new_user'] ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="notify_new_user" class="text-sm font-medium text-gray-700">
                                    Notificar admin sobre novos usuários
                                </label>
                                <p class="text-xs text-gray-500">
                                    Envia notificação quando um novo cliente se cadastra
                                </p>
                            </div>
                        </div>

                        <!-- Notificar Novo Pedido -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="notify_new_order" 
                                       name="notify_new_order" 
                                       value="1"
                                       {{ $settings['notify_new_order'] ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="notify_new_order" class="text-sm font-medium text-gray-700">
                                    Notificar admin sobre novos pedidos
                                </label>
                                <p class="text-xs text-gray-500">
                                    Envia notificação quando um novo pedido é realizado
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testar Conexão -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow p-6 border border-blue-200">
                    <div class="flex items-center mb-4">
                        <i class="bi bi-send-check text-blue-600 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Testar Configuração</h3>
                    </div>
                    
                    <p class="text-sm text-gray-700 mb-4">
                        Envie um email de teste para verificar se suas configurações estão corretas.
                    </p>
                    
                    <div class="mb-4">
                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email de Teste
                        </label>
                        <input type="email" 
                               id="test_email" 
                               x-model="testEmail"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="seu-email@exemplo.com">
                    </div>
                    
                    <button type="button" 
                            @click="testEmailConnection()"
                            :disabled="testing || !testEmail"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <span x-show="!testing">
                            <i class="bi bi-send mr-2"></i>Enviar Email de Teste
                        </span>
                        <span x-show="testing">
                            <i class="bi bi-arrow-clockwise animate-spin mr-2"></i>Enviando...
                        </span>
                    </button>
                    
                    <div x-show="testResult" 
                         class="mt-4 p-3 rounded-lg"
                         :class="testSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                        <p class="text-sm" x-text="testMessage"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Úteis -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="bi bi-info-circle text-blue-600 text-2xl mr-3 mt-1"></i>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Configurações Comuns de SMTP</h4>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div>
                            <strong>Gmail:</strong>
                            <ul class="list-disc list-inside ml-4 mt-1">
                                <li>Host: smtp.gmail.com</li>
                                <li>Porta: 587 (TLS) ou 465 (SSL)</li>
                                <li>Encriptação: TLS</li>
                                <li>Use uma <a href="https://support.google.com/accounts/answer/185833" target="_blank" class="underline">Senha de App</a></li>
                            </ul>
                        </div>
                        <div class="mt-3">
                            <strong>Outlook/Office365:</strong>
                            <ul class="list-disc list-inside ml-4 mt-1">
                                <li>Host: smtp.office365.com</li>
                                <li>Porta: 587</li>
                                <li>Encriptação: TLS</li>
                            </ul>
                        </div>
                        <div class="mt-3">
                            <strong>Hostinger:</strong>
                            <ul class="list-disc list-inside ml-4 mt-1">
                                <li>Host: smtp.hostinger.com</li>
                                <li>Porta: 587 (TLS recomendado) ou 465 (SSL)</li>
                                <li>Encriptação: TLS</li>
                                <li>⚠️ Email remetente = Email de login (mesmo email)</li>
                                <li>Use sua senha de email normal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('admin.settings') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-save mr-2"></i>Salvar Configurações
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function emailSettings() {
    return {
        showPassword: false,
        testing: false,
        testResult: false,
        testSuccess: false,
        testMessage: '',
        testEmail: '{{ $settings['mail_from_address'] ?? '' }}',
        
        async testEmailConnection() {
            if (!this.testEmail) {
                alert('Por favor, informe um email para teste');
                return;
            }
            
            this.testing = true;
            this.testResult = false;
            
            try {
                const response = await fetch('{{ route('admin.settings.email.test') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        test_email: this.testEmail
                    })
                });
                
                const result = await response.json();
                
                this.testResult = true;
                this.testSuccess = result.success;
                this.testMessage = result.message;
                
            } catch (error) {
                this.testResult = true;
                this.testSuccess = false;
                this.testMessage = 'Erro ao testar conexão: ' + error.message;
            } finally {
                this.testing = false;
            }
        }
    }
}
</script>
@endpush
@endsection

