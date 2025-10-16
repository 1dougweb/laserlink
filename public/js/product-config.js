// Configurações globais para o gerenciador de produtos
window.categoriesData = [];
window.fileManagerRoute = '';
window.categoryStoreRoute = '';
window.generateDescriptionRoute = '';

// Função para inicializar as configurações
function initProductConfig(categories, fileManagerRoute, categoryStoreRoute, generateDescriptionRoute) {
    window.categoriesData = categories;
    window.fileManagerRoute = fileManagerRoute;
    window.categoryStoreRoute = categoryStoreRoute;
    window.generateDescriptionRoute = generateDescriptionRoute;
}

