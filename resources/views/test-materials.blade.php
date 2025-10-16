<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Materiais</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Teste de Funcionalidade dos Materiais</h1>
        
        <!-- Teste de Espessuras -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Teste de Espessuras</h2>
            <div id="thickness-container" class="space-y-2">
                <div class="flex">
                    <input type="number" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent thickness-input" 
                           name="available_thicknesses[]" 
                           placeholder="Ex: 2" 
                           step="0.1" 
                           min="0">
                    <button type="button" 
                            class="px-3 py-2 bg-red-600 text-white rounded-r-lg hover:bg-red-700 remove-thickness hidden">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
            <button type="button" 
                    onclick="addThickness()" 
                    class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                <i class="bi bi-plus mr-2"></i>Adicionar Espessura
            </button>
        </div>
        
        <!-- Teste de Configurações Extras -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Teste de Configurações Extras</h2>
            <div id="extras-container" class="space-y-2">
                <div class="flex">
                    <input type="text" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent extra-input" 
                           name="config_extras[]" 
                           placeholder="Ex: Corte a laser">
                    <button type="button" 
                            class="px-3 py-2 bg-red-600 text-white rounded-r-lg hover:bg-red-700 remove-extra hidden">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
            <button type="button" 
                    onclick="addExtra()" 
                    class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                <i class="bi bi-plus mr-2"></i>Adicionar Configuração
            </button>
        </div>
    </div>

    <script>
    let thicknessIndex = 0;
    let extraIndex = 0;

    // Funções globais para onclick
    window.addThickness = function() {
        console.log('addThickness chamada');
        const container = document.getElementById('thickness-container');
        const thicknessHtml = `
            <div class="flex mb-2" data-index="${thicknessIndex}">
                <input type="number" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent thickness-input" 
                       name="available_thicknesses[]" 
                       placeholder="Ex: 2" 
                       step="0.1" 
                       min="0">
                <button type="button" 
                        onclick="removeThickness(this)" 
                        class="px-3 py-2 bg-red-600 text-white rounded-r-lg hover:bg-red-700 remove-thickness">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', thicknessHtml);
        thicknessIndex++;
        updateRemoveButtons();
    }

    window.removeThickness = function(button) {
        console.log('removeThickness chamada');
        button.closest('.flex').remove();
        updateRemoveButtons();
    }

    window.addExtra = function() {
        console.log('addExtra chamada');
        const container = document.getElementById('extras-container');
        const extraHtml = `
            <div class="flex mb-2" data-index="${extraIndex}">
                <input type="text" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent extra-input" 
                       name="config_extras[]" 
                       placeholder="Ex: Corte a laser">
                <button type="button" 
                        onclick="removeExtra(this)" 
                        class="px-3 py-2 bg-red-600 text-white rounded-r-lg hover:bg-red-700 remove-extra">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', extraHtml);
        extraIndex++;
        updateRemoveButtons();
    }

    window.removeExtra = function(button) {
        console.log('removeExtra chamada');
        button.closest('.flex').remove();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const thicknessInputs = document.querySelectorAll('.thickness-input');
        const extraInputs = document.querySelectorAll('.extra-input');
        
        // Mostrar/ocultar botões de remover espessuras
        thicknessInputs.forEach((input) => {
            const removeBtn = input.parentElement.querySelector('.remove-thickness');
            if (removeBtn) {
                if (thicknessInputs.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            }
        });
        
        // Mostrar/ocultar botões de remover extras
        extraInputs.forEach((input) => {
            const removeBtn = input.parentElement.querySelector('.remove-extra');
            if (removeBtn) {
                if (extraInputs.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            }
        });
    }

    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página carregada');
        updateRemoveButtons();
    });
    </script>
</body>
</html>

