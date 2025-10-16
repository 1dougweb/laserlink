@extends('admin.layout')

@section('title', 'Fornecedor: ' . $supplier->name)
@section('page-title', 'Fornecedor: ' . $supplier->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.suppliers.index') }}" class="text-sm text-primary hover:text-red-700">
            <i class="bi bi-arrow-left mr-1"></i> Voltar para Fornecedores
        </a>
        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
            <i class="bi bi-pencil mr-1"></i> Editar
        </a>
    </div>

    <!-- Informações do Fornecedor -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informações do Fornecedor</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Nome</label>
                    <p class="mt-1 text-base text-gray-900">{{ $supplier->name }}</p>
                </div>

                @if($supplier->company_name)
                <div>
                    <label class="text-sm font-medium text-gray-500">Razão Social</label>
                    <p class="mt-1 text-base text-gray-900">{{ $supplier->company_name }}</p>
                </div>
                @endif

                @if($supplier->cnpj)
                <div>
                    <label class="text-sm font-medium text-gray-500">CNPJ</label>
                    <p class="mt-1 text-base text-gray-900">{{ $supplier->cnpj }}</p>
                </div>
                @endif

                @if($supplier->email)
                <div>
                    <label class="text-sm font-medium text-gray-500">E-mail</label>
                    <p class="mt-1 text-base text-gray-900">
                        <a href="mailto:{{ $supplier->email }}" class="text-primary hover:text-red-700">{{ $supplier->email }}</a>
                    </p>
                </div>
                @endif

                @if($supplier->phone)
                <div>
                    <label class="text-sm font-medium text-gray-500">Telefone</label>
                    <p class="mt-1 text-base text-gray-900">{{ $supplier->phone }}</p>
                </div>
                @endif

                @if($supplier->whatsapp)
                <div>
                    <label class="text-sm font-medium text-gray-500">WhatsApp</label>
                    <p class="mt-1 text-base text-gray-900">
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $supplier->whatsapp) }}" target="_blank" class="text-green-600 hover:text-green-700">
                            {{ $supplier->whatsapp }}
                        </a>
                    </p>
                </div>
                @endif

                @if($supplier->website)
                <div>
                    <label class="text-sm font-medium text-gray-500">Website</label>
                    <p class="mt-1 text-base text-gray-900">
                        <a href="{{ $supplier->website }}" target="_blank" class="text-primary hover:text-red-700">{{ $supplier->website }}</a>
                    </p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $supplier->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $supplier->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </p>
                </div>
            </div>

            @if($supplier->address || $supplier->city)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Endereço</h4>
                <p class="text-base text-gray-900">
                    {{ $supplier->address }}
                    @if($supplier->city || $supplier->state)
                        <br>{{ $supplier->city }}@if($supplier->state), {{ $supplier->state }}@endif
                    @endif
                    @if($supplier->zip_code)
                        <br>CEP: {{ $supplier->zip_code }}
                    @endif
                </p>
            </div>
            @endif

            @if($supplier->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Observações</h4>
                <p class="text-base text-gray-900">{{ $supplier->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Produtos do Fornecedor -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Produtos ({{ $supplier->products->count() }})</h3>
        </div>
        @if($supplier->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($supplier->products as $product)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->sku ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->stock_quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-primary hover:text-red-700">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6">
                <p class="text-gray-500 text-center">Nenhum produto vinculado a este fornecedor</p>
            </div>
        @endif
    </div>
</div>
@endsection

