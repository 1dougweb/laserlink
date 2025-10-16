/**
 * Helper para calcular preços de campos extras
 */
class ExtraFieldsCalculator {
    constructor() {
        this.fieldsData = {};
    }

    /**
     * Inicializa os dados dos campos extras
     */
    init(fieldsConfig) {
        this.fieldsData = fieldsConfig || {};
    }

    /**
     * Calcula o preço adicional dos campos extras selecionados
     */
    calculateExtraFieldsPrice(extraFieldsValues, basePrice, area = 1, quantity = 1) {
        let totalExtraPrice = 0;
        const breakdown = [];

        // Itera sobre os valores dos campos extras selecionados
        for (const [fieldSlug, value] of Object.entries(extraFieldsValues || {})) {
            if (!value) continue; // Ignora valores vazios

            // Encontra o campo nos dados configurados
            const field = this.findFieldBySlug(fieldSlug);
            if (!field) {
                console.warn(`Campo extra não encontrado: ${fieldSlug}`);
                continue;
            }

            // Encontra a opção selecionada
            const option = this.findOptionByValue(field, value);
            if (!option) {
                console.warn(`Opção não encontrada para campo ${fieldSlug}:`, value);
                continue;
            }

            // Calcula o preço da opção
            const optionPrice = this.calculateOptionPrice(option, basePrice, area, quantity);

            if (optionPrice > 0) {
                totalExtraPrice += optionPrice;
                breakdown.push({
                    field_name: field.name,
                    option_label: option.label,
                    price: optionPrice,
                    price_type: option.price_type,
                    base_price: option.price
                });
            }
        }

        return {
            total: totalExtraPrice,
            breakdown: breakdown
        };
    }

    /**
     * Calcula o preço de uma opção baseado no tipo de preço
     */
    calculateOptionPrice(option, basePrice, area, quantity) {
        const price = parseFloat(option.price) || 0;
        
        switch (option.price_type) {
            case 'fixed':
                return price;
                
            case 'percentage':
                return basePrice * (price / 100);
                
            case 'per_unit':
                return price * quantity;
                
            case 'per_area':
                return price * area;
                
            default:
                return price;
        }
    }

    /**
     * Encontra um campo pelo slug
     */
    findFieldBySlug(slug) {
        if (!this.fieldsData.extra_fields) return null;
        
        return this.fieldsData.extra_fields.find(fieldConfig => {
            return fieldConfig.field && fieldConfig.field.slug === slug;
        })?.field;
    }

    /**
     * Encontra uma opção pelo valor
     */
    findOptionByValue(field, value) {
        if (!field || !field.activeOptions) return null;
        
        return field.activeOptions.find(option => {
            return String(option.value) === String(value);
        });
    }

    /**
     * Formata o preço para exibição em BRL
     */
    formatPrice(price) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(price);
    }

    /**
     * Renderiza o breakdown de preços extras no HTML
     */
    renderBreakdown(breakdown, containerId = 'extra-fields-breakdown') {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (!breakdown || breakdown.length === 0) {
            container.innerHTML = '';
            container.style.display = 'none';
            return;
        }

        let html = '<div class="extra-fields-breakdown mt-4 p-4 bg-gray-50 rounded-lg">';
        html += '<h4 class="text-sm font-semibold text-gray-700 mb-3">Campos Extras:</h4>';
        html += '<div class="space-y-2">';

        breakdown.forEach(item => {
            html += `
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">
                        ${item.field_name}: <span class="font-medium">${item.option_label}</span>
                    </span>
                    <span class="font-semibold text-primary">
                        ${this.formatPrice(item.price)}
                    </span>
                </div>
            `;
        });

        html += '</div></div>';
        container.innerHTML = html;
        container.style.display = 'block';
    }
}

// Exporta uma instância global
window.extraFieldsCalculator = new ExtraFieldsCalculator();

