@props([
    'field' => null,
    'value' => '',
    'onChange' => 'calculatePrice()',
    'prefix' => 'extra_fields'
])

@if($field)
<div class="extra-field-container mb-6">
    <div class="field-header mb-3">
        <label class="block text-sm font-semibold text-gray-800 mb-1" for="field_{{ $field->id }}">
            {{ $field->name }}
            @if($field->pivot->is_required ?? false)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
        @if($field->description)
            <p class="text-xs text-gray-600 mb-2">{{ $field->description }}</p>
        @endif
    </div>
    
    <div class="field-input">
        @if($field->type === 'text' || $field->type === 'number')
            <input type="{{ $field->type }}" 
                   id="field_{{ $field->id }}"
                   name="{{ $prefix }}[{{ $field->slug }}]"
                   value="{{ $value }}"
                   {{ ($field->pivot->is_required ?? false) ? 'required' : '' }}
                   @if($field->type === 'number')
                       min="{{ $field->settings['min'] ?? 0 }}"
                       max="{{ $field->settings['max'] ?? '' }}"
                       step="{{ $field->settings['step'] ?? 1 }}"
                   @endif
                   placeholder="{{ $field->settings['placeholder'] ?? '' }}"
                   x-model="extraFields['{{ $field->slug }}']"
                   @input="{{ $onChange }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            @if($field->settings['unit'] ?? false)
                <span class="text-sm text-gray-500 mt-1 block">{{ $field->settings['unit'] }}</span>
            @endif

        @elseif($field->type === 'textarea')
            <textarea id="field_{{ $field->id }}"
                      name="{{ $prefix }}[{{ $field->slug }}]"
                      {{ ($field->pivot->is_required ?? false) ? 'required' : '' }}
                      placeholder="{{ $field->settings['placeholder'] ?? '' }}"
                      rows="{{ $field->settings['rows'] ?? 3 }}"
                      x-model="extraFields['{{ $field->slug }}']"
                      @input="{{ $onChange }}"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"></textarea>

        @elseif($field->type === 'select')
            <select id="field_{{ $field->id }}"
                    name="{{ $prefix }}[{{ $field->slug }}]"
                    {{ ($field->pivot->is_required ?? false) ? 'required' : '' }}
                    x-model="extraFields['{{ $field->slug }}']"
                    @change="{{ $onChange }}"
                    data-field-slug="{{ $field->slug }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all bg-white">
                <option value="">{{ $field->settings['placeholder'] ?? 'Selecione uma opção' }}</option>
                @foreach($field->activeOptions as $option)
                    <option value="{{ $option->value }}" 
                            data-price="{{ $option->price }}" 
                            data-price-type="{{ $option->price_type }}"
                            data-description="{{ $option->description ?? '' }}">
                        {{ $option->label }}
                        @if($option->price > 0)
                            @if($option->price_type === 'percentage')
                                (+{{ number_format($option->price, 0) }}%)
                            @else
                                (+ R$ {{ number_format($option->price, 2, ',', '.') }})
                            @endif
                        @endif
                    </option>
                @endforeach
            </select>

        @elseif($field->type === 'radio')
            <div class="space-y-3">
                @foreach($field->activeOptions as $option)
                    <label class="flex items-start cursor-pointer p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-gray-50 transition-all duration-200"
                           :class="extraFields['{{ $field->slug }}'] === '{{ $option->value }}' ? 'border-primary bg-primary/5' : ''">
                        <input type="radio"
                               name="{{ $prefix }}[{{ $field->slug }}]"
                               value="{{ $option->value }}"
                               data-price="{{ $option->price }}"
                               data-price-type="{{ $option->price_type }}"
                               data-field-slug="{{ $field->slug }}"
                               {{ ($field->pivot->is_required ?? false) ? 'required' : '' }}
                               x-model="extraFields['{{ $field->slug }}']"
                               @change="{{ $onChange }}"
                               class="w-5 h-5 text-primary border-gray-300 focus:ring-primary mt-0.5">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-800">{{ $option->label }}</span>
                                @if($option->price > 0)
                                    <span class="text-sm font-semibold text-primary">
                                        @if($option->price_type === 'percentage')
                                            +{{ number_format($option->price, 0) }}%
                                        @else
                                            + R$ {{ number_format($option->price, 2, ',', '.') }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                            @if($option->description)
                                <p class="text-xs text-gray-600 mt-1">{{ $option->description }}</p>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

        @elseif($field->type === 'checkbox')
            <label class="flex items-start cursor-pointer p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-gray-50 transition-all duration-200"
                   :class="extraFields['{{ $field->slug }}'] ? 'border-primary bg-primary/5' : ''">
                <input type="checkbox"
                       name="{{ $prefix }}[{{ $field->slug }}]"
                       value="1"
                       data-price="{{ $field->activeOptions->first()->price ?? 0 }}"
                       data-price-type="{{ $field->activeOptions->first()->price_type ?? 'fixed' }}"
                       data-field-slug="{{ $field->slug }}"
                       {{ ($field->pivot->is_required ?? false) ? 'required' : '' }}
                       x-model="extraFields['{{ $field->slug }}']"
                       @change="{{ $onChange }}"
                       class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary mt-0.5">
                <div class="ml-3 flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800">
                            {{ $field->activeOptions->first()->label ?? $field->name }}
                        </span>
                        @if(($field->activeOptions->first()->price ?? 0) > 0)
                            <span class="text-sm font-semibold text-primary">
                                @if(($field->activeOptions->first()->price_type ?? 'fixed') === 'percentage')
                                    +{{ number_format($field->activeOptions->first()->price, 0) }}%
                                @else
                                    + R$ {{ number_format($field->activeOptions->first()->price, 2, ',', '.') }}
                                @endif
                            </span>
                        @endif
                    </div>
                    @if($field->activeOptions->first()->description ?? false)
                        <p class="text-xs text-gray-600 mt-1">{{ $field->activeOptions->first()->description }}</p>
                    @endif
                </div>
            </label>
        @endif
    </div>
</div>
@endif
