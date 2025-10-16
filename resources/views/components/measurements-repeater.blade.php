@props([
    'measurements' => [],
    'productType' => null,
    'material' => null
])

<div class="measurements-repeater">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-medium text-gray-900">Medidas do Produto</h4>
        <button type="button" 
                onclick="addMeasurement()" 
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="bi bi-plus-lg mr-1"></i>
            Adicionar Medida
        </button>
    </div>

    <div id="measurements-container" class="space-y-4">
        @if(count($measurements) > 0)
            @foreach($measurements as $index => $measurement)
                @include('components.measurement-item', [
                    'index' => $index,
                    'measurement' => $measurement,
                    'productType' => $productType,
                    'material' => $material
                ])
            @endforeach
        @else
            @include('components.measurement-item', [
                'index' => 0,
                'measurement' => null,
                'productType' => $productType,
                'material' => $material
            ])
        @endif
    </div>
</div>

<script>
let measurementIndex = {{ count($measurements) }};

function addMeasurement() {
    console.log('addMeasurement chamada, índice atual:', measurementIndex);
    const container = document.getElementById('measurements-container');
    const measurementHtml = generateMeasurementHtml(measurementIndex);
    
    const div = document.createElement('div');
    div.innerHTML = measurementHtml;
    const newMeasurement = div.firstElementChild;
    container.appendChild(newMeasurement);
    
    // Incrementar o índice APÓS criar a medida
    measurementIndex++;
    updateMeasurementNumbers();
    
    // Aguardar um tick para garantir que o DOM foi atualizado
    setTimeout(() => {
        // Verificar se os elementos foram criados corretamente
        const areaElement = document.getElementById(`area-${measurementIndex - 1}`);
        const volumeElement = document.getElementById(`volume-${measurementIndex - 1}`);
        const weightElement = document.getElementById(`weight-${measurementIndex - 1}`);
        const statusElement = document.getElementById(`status-${measurementIndex - 1}`);
        
        if (!areaElement || !volumeElement || !weightElement || !statusElement) {
            console.warn(`Alguns elementos da medida ${measurementIndex - 1} não foram encontrados após inserção`);
        }
        
        // Espessuras são configuradas no tipo de produto
    }, 0);
}

function removeMeasurement(button) {
    const measurementDiv = button.closest('.measurement-item');
    measurementDiv.remove();
    updateMeasurementNumbers();
}

function updateMeasurementNumbers() {
    const measurements = document.querySelectorAll('.measurement-item');
    measurements.forEach((measurement, index) => {
        const numberElement = measurement.querySelector('.measurement-number');
        if (numberElement) {
            numberElement.textContent = `Medida #${index + 1}`;
        }
        
        // Atualizar índices dos inputs
        const inputs = measurement.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/measurements\[\d+\]/, `measurements[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

function generateMeasurementHtml(index) {
    return `
        <div class="measurement-item border border-gray-200 rounded-lg p-4 bg-white">
            <div class="flex justify-between items-center mb-4">
                <h5 class="measurement-number text-sm font-medium text-gray-700">Medida #${index + 1}</h5>
                <div class="flex items-center space-x-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="measurements[${index}][is_default]" 
                               value="1"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                               onchange="setAsDefault(this)">
                        <span class="ml-2 text-xs text-gray-600">Padrão</span>
                    </label>
                    <button type="button" 
                            onclick="removeMeasurement(this)" 
                            class="text-red-600 hover:text-red-800">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Medida</label>
                    <input type="text" 
                           name="measurements[${index}][name]" 
                           value=""
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           placeholder="Ex: Padrão, Pequeno, Grande">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <input type="text" 
                           name="measurements[${index}][description]" 
                           value=""
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           placeholder="Descrição opcional">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Largura (cm)</label>
                    <input type="number" 
                           name="measurements[${index}][width]" 
                           value=""
                           step="0.1"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onchange="calculateMeasurements(this)">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Altura (cm)</label>
                    <input type="number" 
                           name="measurements[${index}][height]" 
                           value=""
                           step="0.1"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onchange="calculateMeasurements(this)">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profundidade (cm)</label>
                    <input type="number" 
                           name="measurements[${index}][depth]" 
                           value=""
                           step="0.1"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onchange="calculateMeasurements(this)">
                </div>
                
                <!-- Campo de espessura removido - configurado no tipo de produto -->
            </div>
            
            <!-- Cálculos Automáticos -->
            <div class="mt-4 p-3 bg-gray-50 rounded-md">
                <h6 class="text-sm font-medium text-gray-700 mb-2">Cálculos Automáticos</h6>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Área:</span>
                        <span class="font-medium" id="area-${index}">0,00 m²</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Volume:</span>
                        <span class="font-medium" id="volume-${index}">0,00 m³</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Peso:</span>
                        <span class="font-medium" id="weight-${index}">0,00 kg</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium text-green-600" id="status-${index}">✓ Válida</span>
                    </div>
                </div>
            </div>
            
            <!-- Campos Hidden para Cálculos -->
            <input type="hidden" name="measurements[${index}][area]" id="hidden-area-${index}" value="0">
            <input type="hidden" name="measurements[${index}][volume]" id="hidden-volume-${index}" value="0">
            <input type="hidden" name="measurements[${index}][weight]" id="hidden-weight-${index}" value="0">
            <input type="hidden" name="measurements[${index}][is_active]" value="1">
            <input type="hidden" name="measurements[${index}][sort_order]" value="${index}">
        </div>
    `;
}

function setAsDefault(checkbox) {
    if (checkbox.checked) {
        // Desmarcar outras checkboxes
        const allCheckboxes = document.querySelectorAll('input[name$="[is_default]"]');
        allCheckboxes.forEach(cb => {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    }
}

function calculateMeasurements(input) {
    const measurementDiv = input.closest('.measurement-item');
    if (!measurementDiv) {
        console.error('Elemento measurement-item não encontrado');
        return;
    }
    
    // Buscar inputs diretamente dentro do measurementDiv
    const widthInput = measurementDiv.querySelector('input[name*="[width]"]');
    const heightInput = measurementDiv.querySelector('input[name*="[height]"]');
    const depthInput = measurementDiv.querySelector('input[name*="[depth]"]');
    
    if (!widthInput || !heightInput || !depthInput) {
        console.error('Inputs de dimensões não encontrados');
        return;
    }
    
    // Garantir que valores vazios sejam tratados como 0
    const width = parseFloat(widthInput.value) || 0;
    const height = parseFloat(heightInput.value) || 0;
    const depth = parseFloat(depthInput.value) || 0;
    
    // Atualizar os valores dos inputs para garantir que não sejam enviados como strings vazias
    widthInput.value = width;
    heightInput.value = height;
    depthInput.value = depth;
    
    // Calcular área (cm² para m²)
    const area = (width * height) / 10000;
    
    // Calcular volume (cm³ para m³)
    const volume = (width * height * depth) / 1000000;
    
    // Calcular peso (assumindo densidade do material)
    const materialDensity = {{ $material ? $material->density_g_cm3 : 1.19 }};
    // Peso calculado baseado no volume e densidade (sem espessura específica)
    const weight = volume * materialDensity;
    
    // Buscar elementos de exibição diretamente dentro do measurementDiv
    const areaElement = measurementDiv.querySelector('[id^="area-"]');
    const volumeElement = measurementDiv.querySelector('[id^="volume-"]');
    const weightElement = measurementDiv.querySelector('[id^="weight-"]');
    const statusElement = measurementDiv.querySelector('[id^="status-"]');
    
    // Atualizar exibição
    if (areaElement) {
        areaElement.textContent = area.toFixed(4) + ' m²';
    }
    
    if (volumeElement) {
        volumeElement.textContent = volume.toFixed(6) + ' m³';
    }
    
    if (weightElement) {
        weightElement.textContent = weight.toFixed(3) + ' kg';
    }
    
    // Atualizar campos hidden
    const hiddenAreaElement = measurementDiv.querySelector('[id^="hidden-area-"]');
    const hiddenVolumeElement = measurementDiv.querySelector('[id^="hidden-volume-"]');
    const hiddenWeightElement = measurementDiv.querySelector('[id^="hidden-weight-"]');
    
    if (hiddenAreaElement) hiddenAreaElement.value = area;
    if (hiddenVolumeElement) hiddenVolumeElement.value = volume;
    if (hiddenWeightElement) hiddenWeightElement.value = weight;
    
    // Validar medida
    const isValid = width > 0 && height > 0;
    if (statusElement) {
        if (isValid) {
            statusElement.textContent = '✓ Válida';
            statusElement.className = 'font-medium text-green-600';
        } else {
            statusElement.textContent = '⚠ Incompleta';
            statusElement.className = 'font-medium text-yellow-600';
        }
    }
}

// Função removida - espessuras são configuradas no tipo de produto

// Inicializar cálculos para medidas existentes
document.addEventListener('DOMContentLoaded', function() {
    const measurements = document.querySelectorAll('.measurement-item');
    measurements.forEach(measurement => {
        const inputs = measurement.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            if (input.value) {
                calculateMeasurements(input);
            }
        });
    });
});
</script>
