@props([
    'formulaField' => null,
    'value' => '',
    'onChange' => 'calculatePrice()',
    'prefix' => 'formula_fields'
])

@if($formulaField)
<div class="tmcp-field-wrap">
    <div class="tmcp-field-wrap-inner">
        <div class="tc-col tc-field-label-wrap">
            <label class="tc-col tm-epo-field-label" for="formula_field_{{ $formulaField->id }}">
                {{ $formulaField->name }}
                @if($formulaField->pivot->is_required ?? false)
                    <span class="text-red-500">*</span>
                @endif
            </label>

            @if($formulaField->type === 'text' || $formulaField->type === 'number')
                <input type="{{ $formulaField->type }}" 
                       id="formula_field_{{ $formulaField->id }}"
                       name="{{ $prefix }}[{{ $formulaField->slug }}]"
                       value="{{ $value }}"
                       {{ ($formulaField->pivot->is_required ?? false) ? 'required' : '' }}
                       @if($formulaField->type === 'number')
                           min="{{ $formulaField->settings['min'] ?? 0 }}"
                           max="{{ $formulaField->settings['max'] ?? '' }}"
                           step="{{ $formulaField->settings['step'] ?? 1 }}"
                       @endif
                       placeholder="{{ $formulaField->settings['placeholder'] ?? '' }}"
                       x-model="formulaFields['{{ $formulaField->slug }}']"
                       @input="{{ $onChange }}"
                       class="tm-epo-field tmcp-textfield w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                @if($formulaField->settings['unit'] ?? false)
                    <span class="text-sm text-gray-500">{{ $formulaField->settings['unit'] }}</span>
                @endif

            @elseif($formulaField->type === 'textarea')
                <textarea id="formula_field_{{ $formulaField->id }}"
                          name="{{ $prefix }}[{{ $formulaField->slug }}]"
                          {{ ($formulaField->pivot->is_required ?? false) ? 'required' : '' }}
                          placeholder="{{ $formulaField->settings['placeholder'] ?? '' }}"
                          rows="{{ $formulaField->settings['rows'] ?? 3 }}"
                          x-model="formulaFields['{{ $formulaField->slug }}']"
                          @input="{{ $onChange }}"
                          class="tm-epo-field tmcp-textarea w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>

            @elseif($formulaField->type === 'select')
                <select id="formula_field_{{ $formulaField->id }}"
                        name="{{ $prefix }}[{{ $formulaField->slug }}]"
                        {{ ($formulaField->pivot->is_required ?? false) ? 'required' : '' }}
                        x-model="formulaFields['{{ $formulaField->slug }}']"
                        @change="{{ $onChange }}"
                        class="tm-epo-field tmcp-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Selecione uma opção</option>
                    @foreach($formulaField->activeOptions as $option)
                        <option value="{{ $option->value }}" 
                                data-price="{{ $option->price }}" 
                                data-price-type="{{ $option->price_type }}">
                            {{ $option->label }}
                            @if($option->price > 0)
                                (+ R$ {{ number_format($option->price, 2, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>

            @elseif($formulaField->type === 'radio')
                <div class="space-y-2">
                    @foreach($formulaField->activeOptions as $option)
                        <label class="flex items-center cursor-pointer p-2 border border-gray-200 rounded-lg hover:bg-white transition-colors">
                            <input type="radio"
                                   name="{{ $prefix }}[{{ $formulaField->slug }}]"
                                   value="{{ $option->value }}"
                                   data-price="{{ $option->price }}"
                                   data-price-type="{{ $option->price_type }}"
                                   {{ ($formulaField->pivot->is_required ?? false) ? 'required' : '' }}
                                   x-model="formulaFields['{{ $formulaField->slug }}']"
                                   @change="{{ $onChange }}"
                                   class="tm-epo-field tmcp-radio w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                            <span class="ml-3 text-sm text-gray-700">
                                {{ $option->label }}
                                @if($option->price > 0)
                                    (+ R$ {{ number_format($option->price, 2, ',', '.') }})
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>

            @elseif($formulaField->type === 'checkbox')
                <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-white transition-colors">
                    <input type="checkbox"
                           name="{{ $prefix }}[{{ $formulaField->slug }}]"
                           value="1"
                           data-price="{{ $formulaField->activeOptions->first()->price ?? 0 }}"
                           data-price-type="{{ $formulaField->activeOptions->first()->price_type ?? 'fixed' }}"
                           {{ ($formulaField->pivot->is_required ?? false) ? 'required' : '' }}
                           x-model="formulaFields['{{ $formulaField->slug }}']"
                           @change="{{ $onChange }}"
                           class="tm-epo-field tmcp-checkbox w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <div class="ml-3">
                        <span class="text-sm font-medium text-gray-700">
                            {{ $formulaField->name }}
                            @if($formulaField->pivot->is_required ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </span>
                    </div>
                </label>
            @endif

            @if($formulaField->description)
                <p class="text-xs text-gray-500 mt-1">{{ $formulaField->description }}</p>
            @endif

            <!-- Formula display -->
            <div class="tm-element-formula" style="display: none;">
                <span class="formula">{{ $formulaField->formula }}</span>
            </div>
        </div>
    </div>
</div>
@endif
