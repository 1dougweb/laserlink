@extends('layouts.store')

@section('title', 'Personalizar ' . $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Início</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Produto e Configurações -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Personalizar {{ $product->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form id="customizationForm">
                        @csrf
                        <input type="hidden" name="customizable_product_id" value="{{ $product->id }}">
                        
                        <!-- Material -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Material</label>
                            <select name="material_id" id="materialSelect" class="form-select" required>
                                <option value="">Selecione o material</option>
                                @foreach($config['materials'] as $material)
                                    <option value="{{ $material['id'] }}" 
                                            data-thicknesses="{{ json_encode($material['available_thicknesses']) }}">
                                        {{ $material['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Escolha o material que melhor atende suas necessidades</div>
                        </div>

                        <!-- Espessura -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Espessura (mm)</label>
                            <select name="thickness" id="thicknessSelect" class="form-select" required disabled>
                                <option value="">Selecione a espessura</option>
                            </select>
                            <div class="form-text">A espessura afeta a resistência e o peso do produto</div>
                        </div>

                        <!-- Dimensões -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Largura (cm)</label>
                                <input type="number" name="width" id="widthInput" class="form-control" 
                                       min="1" step="0.1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Altura (cm)</label>
                                <input type="number" name="height" id="heightInput" class="form-control" 
                                       min="1" step="0.1" required>
                            </div>
                        </div>

                        <!-- Acabamento -->
                        @if(!empty($config['finishes']))
                        <div class="mb-4">
                            <label class="form-label fw-bold">Acabamento</label>
                            <select name="finish" id="finishSelect" class="form-select">
                                <option value="">Selecione o acabamento</option>
                                @foreach($config['finishes'] as $finish)
                                    <option value="{{ $finish }}">{{ ucfirst($finish) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Personalização de Texto -->
                        @if($config['text_customization']['enabled'] ?? false)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Texto Personalizado</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="text_content" id="textContent" class="form-control" 
                                           placeholder="Digite o texto desejado" maxlength="500">
                                </div>
                                <div class="col-md-4">
                                    <select name="font_size" id="fontSize" class="form-select">
                                        <option value="12">12px</option>
                                        <option value="14">14px</option>
                                        <option value="16" selected>16px</option>
                                        <option value="18">18px</option>
                                        <option value="20">20px</option>
                                        <option value="24">24px</option>
                                        <option value="28">28px</option>
                                        <option value="32">32px</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-text">Personalize o texto que será aplicado no produto</div>
                        </div>
                        @endif

                        <!-- Impressão Adesiva -->
                        @if($config['adhesive_printing']['enabled'] ?? false)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Impressão Adesiva</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="adhesive_printing" id="adhesivePrinting" class="form-select">
                                        <option value="">Sem impressão adesiva</option>
                                        @foreach($config['adhesive_printing']['types'] ?? [] as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" name="adhesive_area" id="adhesiveArea" class="form-control" 
                                           placeholder="Área em m²" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Extras -->
                        @if(!empty($config['extras']))
                        <div class="mb-4">
                            <label class="form-label fw-bold">Extras</label>
                            <div class="row">
                                @foreach($config['extras'] as $extra)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="extras[]" value="{{ $extra }}" id="extra_{{ $loop->index }}">
                                            <label class="form-check-label" for="extra_{{ $loop->index }}">
                                                {{ ucfirst(str_replace('_', ' ', $extra)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Base/Suporte -->
                        @if(!empty($config['base_support_options']))
                        <div class="mb-4">
                            <label class="form-label fw-bold">Base/Suporte</label>
                            <select name="base_support" id="baseSupport" class="form-select">
                                <option value="">Sem base/suporte</option>
                                @foreach($config['base_support_options'] as $option)
                                    <option value="{{ $option }}">{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Observações -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Observações</label>
                            <textarea name="custom_notes" id="customNotes" class="form-control" 
                                      rows="3" placeholder="Alguma observação especial sobre seu pedido?"></textarea>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex gap-2">
                            <button type="button" id="calculatePriceBtn" class="btn btn-outline-primary">
                                <i class="fas fa-calculator me-2"></i>Calcular Preço
                            </button>
                            <button type="button" id="saveCustomizationBtn" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Salvar Personalização
                            </button>
                            <button type="button" id="requestQuoteBtn" class="btn btn-warning">
                                <i class="fas fa-file-alt me-2"></i>Solicitar Orçamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumo e Preço -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Resumo do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div id="priceSummary">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calculator fa-2x mb-2"></i>
                            <p>Configure seu produto para ver o preço</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Produto -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Informações do Produto</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">{{ $product->description }}</p>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <strong class="text-primary">Base</strong>
                                <div>R$ {{ number_format($product->base_price, 2, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <strong class="text-success">Personalizável</strong>
                            <div>100%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const materialSelect = document.getElementById('materialSelect');
    const thicknessSelect = document.getElementById('thicknessSelect');
    const calculateBtn = document.getElementById('calculatePriceBtn');
    const saveBtn = document.getElementById('saveCustomizationBtn');
    const quoteBtn = document.getElementById('requestQuoteBtn');
    const priceSummary = document.getElementById('priceSummary');
    const form = document.getElementById('customizationForm');

    // Atualizar espessuras quando material muda
    materialSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const thicknesses = JSON.parse(selectedOption.dataset.thicknesses || '[]');
        
        thicknessSelect.innerHTML = '<option value="">Selecione a espessura</option>';
        thicknessSelect.disabled = thicknesses.length === 0;
        
        thicknesses.forEach(thickness => {
            const option = document.createElement('option');
            option.value = thickness;
            option.textContent = thickness + 'mm';
            thicknessSelect.appendChild(option);
        });
    });

    // Calcular preço
    calculateBtn.addEventListener('click', function() {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Converter arrays para formato correto
        data.extras = Array.from(document.querySelectorAll('input[name="extras[]"]:checked')).map(cb => cb.value);

        fetch('{{ route("api.customization.calculate-price") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                displayPriceSummary(result.data);
            } else {
                alert('Erro ao calcular preço: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            // Erro
            alert('Erro ao calcular preço');
        });
    });

    // Salvar personalização
    saveBtn.addEventListener('click', function() {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.extras = Array.from(document.querySelectorAll('input[name="extras[]"]:checked')).map(cb => cb.value);

        fetch('{{ route("api.customization.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Personalização salva com sucesso!');
                // Aqui você pode redirecionar ou mostrar uma mensagem de sucesso
            } else {
                alert('Erro ao salvar: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            // Erro
            alert('Erro ao salvar personalização');
        });
    });

    // Solicitar orçamento
    quoteBtn.addEventListener('click', function() {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.extras = Array.from(document.querySelectorAll('input[name="extras[]"]:checked')).map(cb => cb.value);
        data.is_quote_request = true;

        fetch('{{ route("api.customization.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Solicitação de orçamento enviada com sucesso!');
            } else {
                alert('Erro ao solicitar orçamento: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            // Erro
            alert('Erro ao solicitar orçamento');
        });
    });

    function displayPriceSummary(data) {
        const { dimensions, costs, pricing, customization } = data;
        
        priceSummary.innerHTML = `
            <div class="mb-3">
                <h6 class="text-primary">Dimensões</h6>
                <div class="row small">
                    <div class="col-6">Largura:</div>
                    <div class="col-6">${dimensions.width}cm</div>
                    <div class="col-6">Altura:</div>
                    <div class="col-6">${dimensions.height}cm</div>
                    <div class="col-6">Espessura:</div>
                    <div class="col-6">${dimensions.thickness}mm</div>
                    <div class="col-6">Área:</div>
                    <div class="col-6">${dimensions.area}m²</div>
                    <div class="col-6">Peso:</div>
                    <div class="col-6">${dimensions.weight}g</div>
                </div>
            </div>
            
            <div class="mb-3">
                <h6 class="text-success">Custos</h6>
                <div class="row small">
                    <div class="col-6">Material:</div>
                    <div class="col-6">R$ ${costs.material_cost.toFixed(2)}</div>
                    <div class="col-6">Acabamento:</div>
                    <div class="col-6">R$ ${costs.finish_cost.toFixed(2)}</div>
                    <div class="col-6">Adesivo:</div>
                    <div class="col-6">R$ ${costs.adhesive_cost.toFixed(2)}</div>
                    <div class="col-6">Texto:</div>
                    <div class="col-6">R$ ${costs.text_cost.toFixed(2)}</div>
                    <div class="col-6">Extras:</div>
                    <div class="col-6">R$ ${costs.extras_cost.toFixed(2)}</div>
                    <div class="col-6">Base:</div>
                    <div class="col-6">R$ ${costs.base_support_cost.toFixed(2)}</div>
                    <div class="col-6">Mão de obra:</div>
                    <div class="col-6">R$ ${costs.labor_cost.toFixed(2)}</div>
                </div>
                <hr class="my-2">
                <div class="row small fw-bold">
                    <div class="col-6">Subtotal:</div>
                    <div class="col-6">R$ ${costs.subtotal.toFixed(2)}</div>
                </div>
            </div>
            
            <div class="mb-3">
                <h6 class="text-warning">Preço Final</h6>
                <div class="row small">
                    <div class="col-6">Margem (${pricing.margin_percentage}%):</div>
                    <div class="col-6">R$ ${pricing.margin_amount.toFixed(2)}</div>
                </div>
                <hr class="my-2">
                <div class="row h5 text-primary fw-bold">
                    <div class="col-6">Total:</div>
                    <div class="col-6">R$ ${pricing.final_price.toFixed(2)}</div>
                </div>
            </div>
            
            <div class="mt-3">
                <h6 class="text-info">Personalização</h6>
                <div class="small">
                    <div><strong>Material:</strong> ${customization.material}</div>
                    ${customization.finish ? `<div><strong>Acabamento:</strong> ${customization.finish}</div>` : ''}
                    ${customization.text_content ? `<div><strong>Texto:</strong> ${customization.text_content}</div>` : ''}
                    ${customization.extras.length > 0 ? `<div><strong>Extras:</strong> ${customization.extras.join(', ')}</div>` : ''}
                    ${customization.base_support ? `<div><strong>Base:</strong> ${customization.base_support}</div>` : ''}
                </div>
            </div>
        `;
    }
});
</script>
@endpush