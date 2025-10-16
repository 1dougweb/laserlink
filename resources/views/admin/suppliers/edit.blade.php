@extends('admin.layout')

@section('title', 'Editar Fornecedor')
@section('page-title', 'Editar Fornecedor')

@section('content')
<div class="space-y-6" x-data="supplierForm()">
    <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Informações Básicas</h3>
            
            <div class="mb-6">
                <label for="cnpj" class="block text-sm font-medium text-gray-700 mb-2">
                    CNPJ
                    <span x-show="loading" class="ml-2 text-xs text-blue-600">
                        <i class="bi bi-arrow-repeat animate-spin"></i> Buscando dados...
                    </span>
                </label>
                <input type="text" name="cnpj" id="cnpj" 
                       x-model="cnpj"
                       @input="formatCNPJ()"
                       @blur="fetchCNPJData()"
                       value="{{ old('cnpj', $supplier->cnpj) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('cnpj') border-red-500 @enderror"
                       placeholder="00.000.000/0000-00"
                       maxlength="18">
                @error('cnpj')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Preencha o CNPJ para buscar os dados automaticamente</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome Fantasia *</label>
                    <input type="text" name="name" id="name" x-model="name" value="{{ old('name', $supplier->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Razão Social</label>
                    <input type="text" name="company_name" id="company_name" x-model="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('company_name') border-red-500 @enderror">
                    @error('company_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                    <input type="email" name="email" id="email" x-model="email" value="{{ old('email', $supplier->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $supplier->website) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('website') border-red-500 @enderror"
                           placeholder="https://...">
                    @error('website')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="text" name="phone" id="phone" 
                           x-model="phone"
                           @input="formatPhone()"
                           value="{{ old('phone', $supplier->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('phone') border-red-500 @enderror"
                           placeholder="(00) 0000-0000"
                           maxlength="15">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" 
                           x-model="whatsapp"
                           @input="formatWhatsApp()"
                           value="{{ old('whatsapp', $supplier->whatsapp) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('whatsapp') border-red-500 @enderror"
                           placeholder="(00) 00000-0000"
                           maxlength="16">
                    @error('whatsapp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Endereço</h3>
            
            <div class="mb-6">
                <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                    CEP
                    <span x-show="loadingCEP" class="ml-2 text-xs text-blue-600">
                        <i class="bi bi-arrow-repeat animate-spin"></i> Buscando...
                    </span>
                </label>
                <input type="text" name="zip_code" id="zip_code" 
                       x-model="zip_code"
                       @input="formatCEP()"
                       @blur="fetchCEPData()"
                       value="{{ old('zip_code', $supplier->zip_code) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('zip_code') border-red-500 @enderror"
                       placeholder="00000-000"
                       maxlength="9">
                @error('zip_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                <textarea name="address" id="address" x-model="address" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('address') border-red-500 @enderror">{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                    <input type="text" name="city" id="city" x-model="city" value="{{ old('city', $supplier->city) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('city') border-red-500 @enderror">
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <input type="text" name="state" id="state" x-model="state" value="{{ old('state', $supplier->state) }}" maxlength="2"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('state') border-red-500 @enderror"
                           placeholder="UF">
                    @error('state')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Observações</h3>
            
            <textarea name="notes" id="notes" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('notes') border-red-500 @enderror"
                      placeholder="Informações adicionais sobre o fornecedor...">{{ old('notes', $supplier->notes) }}</textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-sm text-gray-700">Fornecedor ativo</span>
            </label>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.suppliers.index') }}" 
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                Atualizar Fornecedor
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function supplierForm() {
        return {
            loading: false,
            loadingCEP: false,
            cnpj: '{{ old('cnpj', $supplier->cnpj) }}',
            name: '{{ old('name', $supplier->name) }}',
            company_name: '{{ old('company_name', $supplier->company_name) }}',
            email: '{{ old('email', $supplier->email) }}',
            phone: '{{ old('phone', $supplier->phone) }}',
            whatsapp: '{{ old('whatsapp', $supplier->whatsapp) }}',
            address: `{{ old('address', $supplier->address) }}`,
            city: '{{ old('city', $supplier->city) }}',
            state: '{{ old('state', $supplier->state) }}',
            zip_code: '{{ old('zip_code', $supplier->zip_code) }}',

            formatCNPJ() {
                let value = this.cnpj.replace(/\D/g, '');
                if (value.length <= 14) {
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                }
                this.cnpj = value;
            },

            formatPhone() {
                let value = this.phone.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                this.phone = value.substring(0, 15);
            },

            formatWhatsApp() {
                let value = this.whatsapp.replace(/\D/g, '');
                value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                this.whatsapp = value.substring(0, 16);
            },

            formatCEP() {
                let value = this.zip_code.replace(/\D/g, '');
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
                this.zip_code = value.substring(0, 9);
            },

            async fetchCNPJData() {
                const cnpjClean = this.cnpj.replace(/\D/g, '');
                
                if (cnpjClean.length !== 14) {
                    return;
                }

                this.loading = true;

                try {
                    const response = await fetch(`https://brasilapi.com.br/api/cnpj/v1/${cnpjClean}`);
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        // Preencher campos automaticamente (mantém valores existentes se API não retornar)
                        this.company_name = data.razao_social || this.company_name;
                        this.name = data.nome_fantasia || data.razao_social || this.name;
                        this.email = data.email || this.email;
                        this.phone = data.ddd_telefone_1 ? this.formatPhoneFromAPI(data.ddd_telefone_1) : this.phone;
                        this.zip_code = data.cep ? data.cep.replace(/\D/g, '').replace(/^(\d{5})(\d)/, '$1-$2') : this.zip_code;
                        this.address = this.buildAddress(data) || this.address;
                        this.city = data.municipio || this.city;
                        this.state = data.uf || this.state;

                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Dados do CNPJ carregados com sucesso!', 'success');
                        }
                    } else {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('CNPJ não encontrado na base de dados', 'warning');
                        }
                    }
                } catch (error) {
                    console.error('Erro ao buscar CNPJ:', error);
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Erro ao buscar dados do CNPJ', 'error');
                    }
                } finally {
                    this.loading = false;
                }
            },

            async fetchCEPData() {
                const cepClean = this.zip_code.replace(/\D/g, '');
                
                if (cepClean.length !== 8) {
                    return;
                }

                this.loadingCEP = true;

                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cepClean}/json/`);
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        if (!data.erro) {
                            this.address = data.logradouro || this.address;
                            this.city = data.localidade || this.city;
                            this.state = data.uf || this.state;

                            if (typeof window.showNotification === 'function') {
                                window.showNotification('Endereço encontrado!', 'success');
                            }
                        }
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                } finally {
                    this.loadingCEP = false;
                }
            },

            buildAddress(data) {
                let parts = [];
                if (data.logradouro) parts.push(data.logradouro);
                if (data.numero) parts.push(data.numero);
                if (data.complemento) parts.push(data.complemento);
                if (data.bairro) parts.push(data.bairro);
                return parts.join(', ');
            },

            formatPhoneFromAPI(phone) {
                let value = phone.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                return value;
            }
        }
    }
</script>
@endsection
