@props([
    'index' => 0,
    'measurement' => null,
    'productType' => null,
    'material' => null
])

<div class="measurement-item border border-gray-200 rounded-lg p-4 bg-white">
    <div class="flex justify-between items-center mb-4">
        <h5 class="measurement-number text-sm font-medium text-gray-700">Medida #{{ $index + 1 }}</h5>
        <div class="flex items-center space-x-2">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="measurements[{{ $index }}][is_default]" 
                       value="1"
                       {{ ($measurement && $measurement->is_default) ? 'checked' : '' }}
                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                       onchange="setAsDefault(this)">
                <span class="ml-2 text-xs text-gray-600">Padrão</span>
            </label>
            @if($index > 0)
                <button type="button" 
                        onclick="removeMeasurement(this)" 
                        class="text-red-600 hover:text-red-800">
                    <i class="bi bi-trash"></i>
                </button>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Medida</label>
            <input type="text" 
                   name="measurements[{{ $index }}][name]" 
                   value="{{ $measurement ? $measurement->name : '' }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   placeholder="Ex: Padrão, Pequeno, Grande">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
            <input type="text" 
                   name="measurements[{{ $index }}][description]" 
                   value="{{ $measurement ? $measurement->description : '' }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   placeholder="Descrição opcional">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Largura (cm)</label>
            <input type="number" 
                   name="measurements[{{ $index }}][width]" 
                   value="{{ $measurement ? $measurement->width : '' }}"
                   step="0.1"
                   min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   onchange="calculateMeasurements(this)">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Altura (cm)</label>
            <input type="number" 
                   name="measurements[{{ $index }}][height]" 
                   value="{{ $measurement ? $measurement->height : '' }}"
                   step="0.1"
                   min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   onchange="calculateMeasurements(this)">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Profundidade (cm)</label>
            <input type="number" 
                   name="measurements[{{ $index }}][depth]" 
                   value="{{ $measurement ? $measurement->depth : '' }}"
                   step="0.1"
                   min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   onchange="calculateMeasurements(this)">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Espessura (mm)</label>
            <select name="measurements[{{ $index }}][thickness]" 
                    id="thickness-{{ $index }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    onchange="calculateMeasurements(this)">
                <option value="">Selecione a espessura</option>
                @if($material && $material->available_thicknesses)
                    @foreach($material->getAvailableThicknesses() as $thickness)
                        <option value="{{ $thickness }}" 
                                {{ ($measurement && $measurement->thickness == $thickness) ? 'selected' : '' }}>
                            {{ $thickness }}{{ str_contains($thickness, 'mm') ? '' : ' mm' }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    
    <!-- Cálculos Automáticos -->
    <div class="mt-4 p-3 bg-gray-50 rounded-md">
        <h6 class="text-sm font-medium text-gray-700 mb-2">Cálculos Automáticos</h6>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Área:</span>
                <span class="font-medium" id="area-{{ $index }}">{{ $measurement ? number_format($measurement->area, 4) : '0,0000' }} m²</span>
            </div>
            <div>
                <span class="text-gray-600">Volume:</span>
                <span class="font-medium" id="volume-{{ $index }}">{{ $measurement ? number_format($measurement->volume, 6) : '0,000000' }} m³</span>
            </div>
            <div>
                <span class="text-gray-600">Peso:</span>
                <span class="font-medium" id="weight-{{ $index }}">{{ $measurement ? number_format($measurement->weight, 3) : '0,000' }} kg</span>
            </div>
            <div>
                <span class="text-gray-600">Status:</span>
                <span class="font-medium text-green-600" id="status-{{ $index }}">✓ Válida</span>
            </div>
        </div>
    </div>
    
    <!-- Campos Hidden para Cálculos -->
    <input type="hidden" name="measurements[{{ $index }}][area]" id="hidden-area-{{ $index }}" value="{{ $measurement ? $measurement->area : '0' }}">
    <input type="hidden" name="measurements[{{ $index }}][volume]" id="hidden-volume-{{ $index }}" value="{{ $measurement ? $measurement->volume : '0' }}">
    <input type="hidden" name="measurements[{{ $index }}][weight]" id="hidden-weight-{{ $index }}" value="{{ $measurement ? $measurement->weight : '0' }}">
    <input type="hidden" name="measurements[{{ $index }}][is_active]" value="1">
    <input type="hidden" name="measurements[{{ $index }}][sort_order]" value="{{ $index }}">
    
    @if($measurement)
        <input type="hidden" name="measurements[{{ $index }}][id]" value="{{ $measurement->id }}">
    @endif
</div>
