@extends('admin.layout')

@section('title', 'Novo Campo de Fórmula')
@section('page-title', 'Novo Campo de Fórmula')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.formula-fields.store') }}">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Campo *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="formula" class="block text-sm font-medium text-gray-700 mb-2">
                        Fórmula Matemática *
                    </label>
                    <div class="space-y-3">
                        <textarea id="formula" 
                                  name="formula" 
                                  rows="4"
                                  placeholder="Ex: {quantity} * {product_price} + {area} * 10"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('formula') border-red-500 @enderror font-mono"
                                  required>{{ old('formula') }}</textarea>
                        
                        <div class="flex items-center space-x-4">
                            <button type="button" 
                                    onclick="testFormula()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                                <i class="bi bi-play-circle mr-1"></i>
                                Testar Fórmula
                            </button>
                            <button type="button" 
                                    onclick="validateFormula()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                                <i class="bi bi-check-circle mr-1"></i>
                                Validar
                            </button>
                        </div>
                        
                        <div id="formula-result" class="hidden p-3 rounded-lg text-sm"></div>
                    </div>
                    @error('formula')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Variáveis Disponíveis</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($availableVariables as $var => $label)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="font-mono text-sm text-gray-800">{ {{ $var }} }</div>
                                <div class="text-xs text-gray-600">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordem
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', 0) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Campo Ativo
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ route('admin.formula-fields.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                Salvar Campo
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function testFormula() {
    const formula = document.getElementById('formula').value;
    const resultDiv = document.getElementById('formula-result');
    
    if (!formula.trim()) {
        showResult('Digite uma fórmula para testar', 'error');
        return;
    }
    
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="flex items-center"><div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary mr-2"></div>Testando fórmula...</div>';
    
    fetch('{{ route("admin.formula-fields.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            formula: formula,
            test_variables: {
                quantity: 2,
                product_price: 100.00,
                area: 1.5,
                width: 50,
                height: 30,
                thickness: 3,
                weight: 0.5,
                material_price: 25.00,
                finishing_price: 15.00
            }
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showResult(`✅ Fórmula válida! Resultado: R$ ${data.result.toFixed(2)}`, 'success');
        } else {
            showResult(`❌ Erro na fórmula: ${data.error}`, 'error');
        }
    })
    .catch(error => {
        showResult(`❌ Erro ao testar: ${error.message}`, 'error');
    });
}

function validateFormula() {
    const formula = document.getElementById('formula').value;
    const resultDiv = document.getElementById('formula-result');
    
    if (!formula.trim()) {
        showResult('Digite uma fórmula para validar', 'error');
        return;
    }
    
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="flex items-center"><div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary mr-2"></div>Validando fórmula...</div>';
    
    fetch('{{ route("admin.formula-fields.validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            formula: formula
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            showResult('✅ Fórmula válida!', 'success');
        } else {
            showResult(`❌ Fórmula inválida: ${data.errors.join(', ')}`, 'error');
        }
    })
    .catch(error => {
        showResult(`❌ Erro ao validar: ${error.message}`, 'error');
    });
}

function showResult(message, type) {
    const resultDiv = document.getElementById('formula-result');
    const bgClass = type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    
    resultDiv.className = `p-3 rounded-lg text-sm ${bgClass}`;
    resultDiv.innerHTML = message;
    resultDiv.classList.remove('hidden');
}

// Adicionar variáveis ao clicar
document.addEventListener('DOMContentLoaded', function() {
    const formulaTextarea = document.getElementById('formula');
    const variableDivs = document.querySelectorAll('.bg-gray-50');
    
    variableDivs.forEach(div => {
        div.addEventListener('click', function() {
            const variable = this.querySelector('.font-mono').textContent;
            const currentValue = formulaTextarea.value;
            const cursorPos = formulaTextarea.selectionStart;
            
            const newValue = currentValue.slice(0, cursorPos) + variable + currentValue.slice(cursorPos);
            formulaTextarea.value = newValue;
            formulaTextarea.focus();
            formulaTextarea.setSelectionRange(cursorPos + variable.length, cursorPos + variable.length);
        });
        
        div.style.cursor = 'pointer';
    });
});
</script>
@endpush
@endsection
