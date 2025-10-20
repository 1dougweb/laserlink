<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;

// Sitemap público
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    
    if (!file_exists($path)) {
        // Gerar sitemap se não existir
        $sitemapService = app(\App\Services\SitemapService::class);
        $sitemapService->generate();
    }
    
    return response()->file($path, [
        'Content-Type' => 'application/xml'
    ]);
})->name('sitemap');

// Sitemap do Blog
Route::get('/blog-sitemap.xml', function () {
    $path = public_path('blog-sitemap.xml');
    
    if (!file_exists($path)) {
        // Gerar sitemap do blog se não existir
        $sitemapService = app(\App\Services\SitemapService::class);
        $sitemapService->generateBlogSitemap();
    }
    
    return response()->file($path, [
        'Content-Type' => 'application/xml'
    ]);
})->name('blog.sitemap');

// Rotas da loja virtual
Route::group([], function () {
    Route::get('/test-minimal', function() { return view('store.test-minimal'); });
    
    Route::get('/', [StoreController::class, 'index'])->name('store.index');
    Route::get('/produtos', [StoreController::class, 'products'])->name('store.products');
    Route::get('/produtos/buscar', [StoreController::class, 'searchProducts'])->name('store.search-products');
    Route::get('/categoria/{slug}', [StoreController::class, 'category'])->name('store.category');
    Route::get('/produto/{slug}', [StoreController::class, 'product'])->name('store.product');
    // Página de configuração/customização do produto
    Route::get('/store/{slug}/configurator', [StoreController::class, 'configurator'])
    ->name('store.configurator');
    Route::get('/carrinho', [StoreController::class, 'cart'])->name('store.cart');
    Route::get('/checkout', [StoreController::class, 'checkout'])->name('store.checkout');
    Route::post('/checkout/processar', [StoreController::class, 'processCheckout'])->name('store.checkout.process');
    Route::get('/checkout/sucesso', [StoreController::class, 'checkoutSuccess'])->name('store.checkout.success');
    Route::get('/meus-pedidos', [App\Http\Controllers\CheckoutController::class, 'userOrders'])->name('store.user-orders');
    Route::get('/pedido/{id}', [App\Http\Controllers\CheckoutController::class, 'orderDetails'])->name('store.order-details');
    // Favoritos
    Route::get('/favoritos', [StoreController::class, 'favorites'])->name('store.favorites');
    // Contato
    Route::get('/contato', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contato', [ContactController::class, 'submit'])->name('contact.submit');
});

// API da loja (AJAX)
Route::post('/api/cart/add', [StoreController::class, 'addToCart'])->name('api.cart.add');
Route::post('/api/cart/update', [StoreController::class, 'updateCart'])->name('api.cart.update');
Route::post('/api/cart/update-quantity', [StoreController::class, 'updateCartQuantity'])->name('api.cart.update-quantity');
Route::post('/api/cart/remove', [StoreController::class, 'removeFromCart'])->name('api.cart.remove');
Route::get('/api/cart/get', [StoreController::class, 'getCart'])->name('api.cart.get');

// API de customização (usada na página de personalização)
Route::post('/api/customization/calculate-price', [StoreController::class, 'calculatePrice'])->name('api.customization.calculate-price');
Route::post('/api/customization/save', [StoreController::class, 'saveCustomization'])->name('api.customization.save');

// API de Reviews
Route::get('/api/products/{product}/reviews', [App\Http\Controllers\ProductReviewController::class, 'index'])->name('api.reviews.index');
Route::post('/api/products/{product}/reviews', [App\Http\Controllers\ProductReviewController::class, 'store'])->name('api.reviews.store');
Route::post('/api/reviews/{review}/helpful', [App\Http\Controllers\ProductReviewController::class, 'markHelpful'])->name('api.reviews.helpful');

// Google OAuth
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('oauth.google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('oauth.google.callback');

// API de Favoritos (client-side via localStorage)
Route::get('/api/favorites', [StoreController::class, 'getFavorites'])->name('api.favorites');

// Rotas do Blog (públicas)
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/categoria/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Comentários do Blog
Route::post('/blog/{post}/comentarios', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

// Dashboard do usuário (apenas clientes) - sem rastreamento (não conta como online da loja)
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'client'])
    ->name('dashboard');

// Rotas de autenticação do admin (sem middleware auth)
Route::prefix('admin')->name('admin.')->group(function () {
    // Login do admin
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    
    // Registro do admin (condicional)
    Route::get('/register', [App\Http\Controllers\Admin\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Admin\AuthController::class, 'register'])->name('register.post');
});

// Rotas do admin (apenas admins)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.only'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // API de Notificações
    Route::get('/api/notifications/orders', [App\Http\Controllers\NotificationController::class, 'getOrderNotifications'])->name('api.notifications.orders');
    Route::post('/api/notifications/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('api.notifications.mark-as-read');
    Route::post('/api/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-as-read');

    // Categorias
    Route::get('/categorias', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index')->middleware('permission:categories.view');
    Route::get('/categorias/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create')->middleware('permission:categories.create');
    Route::post('/categorias', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store')->middleware('permission:categories.create');
    Route::get('/categorias/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('categories.show')->middleware('permission:categories.view');
    Route::get('/categorias/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:categories.edit');
    Route::put('/categorias/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update')->middleware('permission:categories.edit');
    Route::delete('/categorias/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:categories.delete');

    // Changelog/Atualizações (apenas webmaster)
    Route::middleware('webmaster')->group(function () {
        Route::get('/atualizacoes', [App\Http\Controllers\Admin\ChangelogController::class, 'index'])->name('changelogs.index');
        Route::get('/atualizacoes/criar', [App\Http\Controllers\Admin\ChangelogController::class, 'create'])->name('changelogs.create');
        Route::post('/atualizacoes', [App\Http\Controllers\Admin\ChangelogController::class, 'store'])->name('changelogs.store');
        Route::get('/atualizacoes/{changelog}', [App\Http\Controllers\Admin\ChangelogController::class, 'show'])->name('changelogs.show');
        Route::get('/atualizacoes/{changelog}/editar', [App\Http\Controllers\Admin\ChangelogController::class, 'edit'])->name('changelogs.edit');
        Route::put('/atualizacoes/{changelog}', [App\Http\Controllers\Admin\ChangelogController::class, 'update'])->name('changelogs.update');
        Route::delete('/atualizacoes/{changelog}', [App\Http\Controllers\Admin\ChangelogController::class, 'destroy'])->name('changelogs.destroy');
    });

    // Produtos
    Route::get('/produtos', [AdminController::class, 'products'])->name('products')->middleware('permission:products.view');
    Route::get('/produtos/criar', [AdminController::class, 'createProduct'])->name('products.create')->middleware('permission:products.create');
    Route::post('/produtos', [AdminController::class, 'storeProduct'])->name('products.store')->middleware('permission:products.create');
    Route::post('/produtos/gerar-descricao', [AdminController::class, 'generateDescription'])->name('products.generate-description')->middleware('permission:products.create|products.edit');
    Route::get('/produtos/{product}', [AdminController::class, 'showProduct'])->name('products.show')->middleware('permission:products.view');
    Route::get('/produtos/{product}/editar', [AdminController::class, 'editProduct'])->name('products.edit')->middleware('permission:products.edit');
    Route::put('/produtos/{product}', [AdminController::class, 'updateProduct'])->name('products.update')->middleware('permission:products.edit');
    Route::patch('/produtos/{product}/toggle-status', [AdminController::class, 'toggleProductStatus'])->name('products.toggle-status')->middleware('permission:products.edit');
    Route::post('/produtos/{product}/duplicar', [AdminController::class, 'duplicateProduct'])->name('products.duplicate')->middleware('permission:products.create');
    Route::post('/produtos/excluir-multiplos', [AdminController::class, 'deleteMultipleProducts'])->name('products.delete-multiple')->middleware('permission:products.delete');
    Route::delete('/produtos/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete')->middleware('permission:products.delete');

    // Estoque de Produtos
    Route::get('/estoque', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index');
    Route::get('/estoque/movimentacoes', [App\Http\Controllers\StockController::class, 'movements'])->name('stock.movements');
    Route::get('/estoque/criar', [App\Http\Controllers\StockController::class, 'create'])->name('stock.create');
    Route::post('/estoque', [App\Http\Controllers\StockController::class, 'store'])->name('stock.store');

    // Matéria-Prima
    Route::prefix('materia-prima')->name('raw-materials.')->group(function() {
        Route::get('/', [App\Http\Controllers\RawMaterialController::class, 'index'])->name('index');
        Route::get('/criar', [App\Http\Controllers\RawMaterialController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\RawMaterialController::class, 'store'])->name('store');
        Route::get('/{rawMaterial}', [App\Http\Controllers\RawMaterialController::class, 'show'])->name('show');
        Route::get('/{rawMaterial}/editar', [App\Http\Controllers\RawMaterialController::class, 'edit'])->name('edit');
        Route::put('/{rawMaterial}', [App\Http\Controllers\RawMaterialController::class, 'update'])->name('update');
        Route::delete('/{rawMaterial}', [App\Http\Controllers\RawMaterialController::class, 'destroy'])->name('destroy');
        
        // Movimentações
        Route::get('/movimentacoes/listar', [App\Http\Controllers\RawMaterialController::class, 'movements'])->name('movements');
        Route::get('/movimentacoes/criar', [App\Http\Controllers\RawMaterialController::class, 'createMovement'])->name('movements.create');
        Route::post('/movimentacoes', [App\Http\Controllers\RawMaterialController::class, 'storeMovement'])->name('movements.store');
    });

    // Fornecedores (dentro de Matéria-Prima)
    Route::post('/fornecedores/excluir-multiplos', [App\Http\Controllers\SupplierController::class, 'bulkDelete'])
        ->name('suppliers.bulk-delete');
    
    Route::resource('fornecedores', App\Http\Controllers\SupplierController::class)
        ->names('suppliers')
        ->parameters(['fornecedores' => 'supplier']);

    // Pedidos
    Route::get('/pedidos', [AdminController::class, 'orders'])->name('orders')->middleware('permission:orders.view');
    Route::get('/pedidos/{order}', [AdminController::class, 'showOrder'])->name('orders.show')->middleware('permission:orders.view');
    Route::put('/pedidos/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus')->middleware('permission:orders.edit');

    // Orçamentos
    Route::get('/orcamentos', [App\Http\Controllers\BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/orcamentos/criar', [App\Http\Controllers\BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/orcamentos', [App\Http\Controllers\BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/orcamentos/{budget}', [App\Http\Controllers\BudgetController::class, 'show'])->name('budgets.show');
    Route::get('/orcamentos/{budget}/editar', [App\Http\Controllers\BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/orcamentos/{budget}', [App\Http\Controllers\BudgetController::class, 'update'])->name('budgets.update');

    // WhatsApp Management
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        // Settings
        Route::get('settings', [App\Http\Controllers\Admin\WhatsAppSettingsController::class, 'index'])->name('settings');
        Route::post('settings', [App\Http\Controllers\Admin\WhatsAppSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/test-connection', [App\Http\Controllers\Admin\WhatsAppSettingsController::class, 'testConnection'])->name('settings.test-connection');
        
        // Instances
        Route::resource('instances', App\Http\Controllers\Admin\WhatsAppController::class)->except(['edit', 'update']);
        Route::post('instances/{instance}/refresh-qr', [App\Http\Controllers\Admin\WhatsAppController::class, 'refreshQrCode'])->name('instances.refresh-qr');
        Route::get('instances/{instance}/status', [App\Http\Controllers\Admin\WhatsAppController::class, 'checkStatus'])->name('instances.status');
        Route::post('instances/{instance}/toggle', [App\Http\Controllers\Admin\WhatsAppController::class, 'toggleActive'])->name('instances.toggle');
        
        // Templates
        Route::resource('templates', App\Http\Controllers\Admin\WhatsAppTemplateController::class);
        Route::post('templates/preview', [App\Http\Controllers\Admin\WhatsAppTemplateController::class, 'preview'])->name('templates.preview');
        Route::post('templates/{template}/toggle', [App\Http\Controllers\Admin\WhatsAppTemplateController::class, 'toggleActive'])->name('templates.toggle');
        Route::post('templates/{template}/duplicate', [App\Http\Controllers\Admin\WhatsAppTemplateController::class, 'duplicate'])->name('templates.duplicate');
        
        // Notifications
        Route::get('notifications', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{notification}', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'show'])->name('notifications.show');
        Route::get('notifications/send/promotion', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'sendPromotion'])->name('notifications.send-promotion');
        Route::post('notifications/send/promotion', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'storePromotion'])->name('notifications.store-promotion');
        Route::post('notifications/{notification}/resend', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'resend'])->name('notifications.resend');
        Route::get('notifications/export', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'export'])->name('notifications.export');
        Route::get('customers/search', [App\Http\Controllers\Admin\WhatsAppNotificationController::class, 'getCustomers'])->name('customers.search');
    });
    Route::delete('/orcamentos/{budget}', [App\Http\Controllers\BudgetController::class, 'destroy'])->name('budgets.destroy');
    Route::get('/orcamentos/{budget}/pdf', [App\Http\Controllers\BudgetController::class, 'pdf'])->name('budgets.pdf');
    Route::post('/orcamentos/{budget}/enviar', [App\Http\Controllers\BudgetController::class, 'send'])->name('budgets.send');
    Route::post('/orcamentos/{budget}/aprovar', [App\Http\Controllers\BudgetController::class, 'approve'])->name('budgets.approve');
    Route::post('/orcamentos/{budget}/rejeitar', [App\Http\Controllers\BudgetController::class, 'reject'])->name('budgets.reject');
    Route::post('/orcamentos/{budget}/duplicar', [App\Http\Controllers\BudgetController::class, 'duplicate'])->name('budgets.duplicate');
    Route::post('/orcamentos/calcular-precos', [App\Http\Controllers\BudgetController::class, 'calculatePrices'])->name('budgets.calculate-prices');
    Route::get('/orcamentos/buscar-produtos', [App\Http\Controllers\BudgetController::class, 'searchProducts'])->name('budgets.search-products');

    // Relatórios
    Route::middleware('permission:reports.view')->group(function () {
        Route::get('/relatorios', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/relatorios/vendas', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/relatorios/produtos', [App\Http\Controllers\ReportController::class, 'products'])->name('reports.products');
    });
    
    Route::middleware('permission:reports.export')->group(function () {
        Route::get('/relatorios/vendas/exportar', [App\Http\Controllers\ReportController::class, 'exportSales'])->name('reports.sales.export');
        Route::get('/relatorios/produtos/exportar', [App\Http\Controllers\ReportController::class, 'exportProducts'])->name('reports.products.export');
    });

    // Campos Extras
    Route::resource('extra-fields', App\Http\Controllers\Admin\ExtraFieldController::class);
    Route::get('extra-fields/{extraField}/options', [App\Http\Controllers\Admin\ExtraFieldController::class, 'options'])->name('extra-fields.options');
    Route::post('extra-fields/{extraField}/save-options', [App\Http\Controllers\Admin\ExtraFieldController::class, 'saveOptions'])->name('extra-fields.save-options');
    Route::patch('extra-fields/{extraField}/toggle', [App\Http\Controllers\Admin\ExtraFieldController::class, 'toggle'])->name('extra-fields.toggle');
    Route::post('extra-fields/reorder', [App\Http\Controllers\Admin\ExtraFieldController::class, 'reorder'])->name('extra-fields.reorder');
    
           // Campos Extras dos Produtos
           Route::get('products/{product}/extra-fields', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'index'])->name('products.extra-fields');
           Route::post('products/{product}/extra-fields', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'store'])->name('products.extra-fields.store');
           Route::put('products/{product}/extra-fields/{fieldId}', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'update'])->name('products.extra-fields.update');
           Route::delete('products/{product}/extra-fields/{fieldId}', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'destroy'])->name('products.extra-fields.destroy');
           Route::post('products/{product}/extra-fields/reorder', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'reorder'])->name('products.extra-fields.reorder');
           
           // Configurações dos Campos
           Route::get('products/{product}/extra-fields/{fieldId}/options', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'getFieldOptions'])->name('products.extra-fields.options');
           Route::get('products/{product}/extra-fields/{fieldId}/settings', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'settings'])->name('products.extra-fields.settings');
           Route::post('products/{product}/extra-fields/{fieldId}/settings', [App\Http\Controllers\Admin\ProductExtraFieldController::class, 'saveSettings'])->name('products.extra-fields.save-settings');
    
    // Campos de Fórmula
    Route::resource('formula-fields', App\Http\Controllers\Admin\FormulaFieldController::class);
    Route::post('formula-fields/test', [App\Http\Controllers\Admin\FormulaFieldController::class, 'testFormula'])->name('formula-fields.test');
    Route::post('formula-fields/validate', [App\Http\Controllers\Admin\FormulaFieldController::class, 'validateFormula'])->name('formula-fields.validate');
    Route::post('formula-fields/reorder', [App\Http\Controllers\Admin\FormulaFieldController::class, 'reorder'])->name('formula-fields.reorder');

    // Posts do Blog
    // IMPORTANTE: Rotas específicas devem vir ANTES do resource
    Route::post('/posts/gerar-conteudo', [App\Http\Controllers\Admin\PostAIController::class, 'generateContent'])->name('posts.generate-content')->middleware('permission:posts.create|posts.edit');
    Route::get('/posts/debug-gemini', [App\Http\Controllers\Admin\PostAIController::class, 'debugConfig'])->name('posts.debug-gemini')->middleware('permission:posts.view');
    
    Route::patch('posts/{post}/toggle-status', [App\Http\Controllers\Admin\PostController::class, 'toggleStatus'])->name('posts.toggle-status')->middleware('permission:posts.edit');
    
    // Resource routes com permissões
    Route::get('/posts', [App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.index')->middleware('permission:posts.view');
    Route::get('/posts/create', [App\Http\Controllers\Admin\PostController::class, 'create'])->name('posts.create')->middleware('permission:posts.create');
    Route::post('/posts', [App\Http\Controllers\Admin\PostController::class, 'store'])->name('posts.store')->middleware('permission:posts.create');
    Route::get('/posts/{post}', [App\Http\Controllers\Admin\PostController::class, 'show'])->name('posts.show')->middleware('permission:posts.view');
    Route::get('/posts/{post}/edit', [App\Http\Controllers\Admin\PostController::class, 'edit'])->name('posts.edit')->middleware('permission:posts.edit');
    Route::put('/posts/{post}', [App\Http\Controllers\Admin\PostController::class, 'update'])->name('posts.update')->middleware('permission:posts.edit');
    Route::delete('/posts/{post}', [App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('posts.destroy')->middleware('permission:posts.delete');
    
    // Comentários do Blog
    Route::get('/comentarios', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index')->middleware('permission:posts.view');
    Route::patch('/comentarios/{comment}/aprovar', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve')->middleware('permission:posts.edit');
    Route::patch('/comentarios/{comment}/rejeitar', [App\Http\Controllers\Admin\CommentController::class, 'reject'])->name('comments.reject')->middleware('permission:posts.edit');
    Route::delete('/comentarios/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy')->middleware('permission:posts.delete');
    Route::get('/api/comentarios/pendentes', [App\Http\Controllers\Admin\CommentController::class, 'pending'])->name('api.comments.pending')->middleware('permission:posts.view');
    
    // Páginas
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    
    
    // Upload de imagens para o editor TinyMCE
    Route::post('/upload-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])->name('upload-image');

    // Configurações (apenas admin pode editar, vendedor pode ver)
    Route::get('/configuracoes', [AdminController::class, 'settings'])->name('settings')->middleware('permission:settings.view');
    Route::put('/configuracoes', [AdminController::class, 'updateSettings'])->name('settings.update')->middleware('permission:settings.edit');
    
    // Configurações separadas
    Route::get('/configuracoes/geral', [AdminController::class, 'settingsGeneral'])->name('settings.general')->middleware('permission:settings.view');
    Route::put('/configuracoes/geral', [AdminController::class, 'updateSettingsGeneral'])->name('settings.general.update')->middleware('permission:settings.edit');
    
    Route::get('/configuracoes/whatsapp', [AdminController::class, 'settingsWhatsApp'])->name('settings.whatsapp')->middleware('permission:settings.view');
    Route::put('/configuracoes/whatsapp', [AdminController::class, 'updateSettingsWhatsApp'])->name('settings.whatsapp.update')->middleware('permission:settings.edit');
    
    Route::get('/configuracoes/aparencia', [AdminController::class, 'settingsAppearance'])->name('settings.appearance')->middleware('permission:settings.view');
    Route::put('/configuracoes/aparencia', [AdminController::class, 'updateSettingsAppearance'])->name('settings.appearance.update')->middleware('permission:settings.edit');
    
    Route::get('/configuracoes/seo', [AdminController::class, 'settingsSeo'])->name('settings.seo')->middleware('permission:settings.view');
    Route::put('/configuracoes/seo', [AdminController::class, 'updateSettingsSeo'])->name('settings.seo.update')->middleware('permission:settings.edit');
    
    Route::get('/configuracoes/gemini', [AdminController::class, 'settingsGemini'])->name('settings.gemini')->middleware('permission:settings.view');
    Route::put('/configuracoes/gemini', [AdminController::class, 'updateSettingsGemini'])->name('settings.gemini.update')->middleware('permission:settings.edit');
    Route::post('/configuracoes/gemini/test', [AdminController::class, 'testGeminiConnection'])->name('settings.gemini.test')->middleware('permission:settings.edit');
    Route::get('/configuracoes/gemini/models', [AdminController::class, 'listGeminiModels'])->name('settings.gemini.models')->middleware('permission:settings.view');
    
    
    Route::get('/configuracoes/email', [AdminController::class, 'settingsEmail'])->name('settings.email')->middleware('permission:settings.view');
    Route::put('/configuracoes/email', [AdminController::class, 'updateSettingsEmail'])->name('settings.email.update')->middleware('permission:settings.edit');
    Route::post('/configuracoes/email/test', [AdminController::class, 'testEmailConnection'])->name('settings.email.test')->middleware('permission:settings.edit');
    
    Route::get('/configuracoes/sitemap', [AdminController::class, 'settingsSitemap'])->name('settings.sitemap')->middleware('permission:settings.view');
    Route::post('/configuracoes/sitemap/generate', [AdminController::class, 'generateSitemap'])->name('settings.sitemap.generate')->middleware('permission:settings.edit');

    // Configurações de Cache
    Route::get('/configuracoes/cache', [AdminController::class, 'settingsCache'])->name('settings.cache')->middleware('permission:settings.view');
    Route::post('/configuracoes/cache/clear', [AdminController::class, 'clearCache'])->name('settings.cache.clear')->middleware('permission:settings.edit');
    Route::post('/configuracoes/cache/toggle', [AdminController::class, 'toggleCache'])->name('settings.cache.toggle')->middleware('permission:settings.edit');

    // Configurações da Loja Virtual
    Route::get('/loja-virtual', [AdminController::class, 'storeSettings'])->name('store-settings');
    Route::put('/loja-virtual', [AdminController::class, 'updateStoreSettings'])->name('store-settings.update');
    Route::post('/loja-virtual/menu/reorder', [AdminController::class, 'reorderMenuItems'])->name('store-settings.menu.reorder');
    
    // Gerenciamento de Usuários (apenas admin)
    Route::middleware('permission:users.view')->group(function () {
        Route::get('usuarios', [App\Http\Controllers\UserManagementController::class, 'index'])->name('users.index');
        Route::get('usuarios/{user}', [App\Http\Controllers\UserManagementController::class, 'show'])->name('users.show');
    });
    
    Route::middleware('permission:users.create')->group(function () {
        Route::get('usuarios/create', [App\Http\Controllers\UserManagementController::class, 'create'])->name('users.create');
        Route::post('usuarios', [App\Http\Controllers\UserManagementController::class, 'store'])->name('users.store');
    });
    
    Route::middleware('permission:users.edit')->group(function () {
        Route::get('usuarios/{user}/edit', [App\Http\Controllers\UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('usuarios/{user}', [App\Http\Controllers\UserManagementController::class, 'update'])->name('users.update');
    });
    
    Route::delete('usuarios/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('permission:users.delete');
    
    // Gerenciamento de Roles (apenas admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/funcoes', [App\Http\Controllers\RolePermissionController::class, 'indexRoles'])->name('roles.index');
        Route::get('/funcoes/criar', [App\Http\Controllers\RolePermissionController::class, 'createRole'])->name('roles.create');
        Route::post('/funcoes', [App\Http\Controllers\RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::get('/funcoes/{role}/editar', [App\Http\Controllers\RolePermissionController::class, 'editRole'])->name('roles.edit');
        Route::put('/funcoes/{role}', [App\Http\Controllers\RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/funcoes/{role}', [App\Http\Controllers\RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
        
        // Gerenciamento de Permissões
        Route::get('/permissoes', [App\Http\Controllers\RolePermissionController::class, 'indexPermissions'])->name('permissions.index');
        Route::post('/permissoes/seed', [App\Http\Controllers\RolePermissionController::class, 'seedPermissions'])->name('permissions.seed');
    });

    // Perfil do Admin
    Route::get('/perfil', [AdminController::class, 'profile'])->name('profile');
    Route::put('/perfil', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/perfil/senha', [AdminController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/perfil', [AdminController::class, 'deleteProfile'])->name('profile.delete');

    // File Manager
    Route::get('/file-manager', [App\Http\Controllers\FileManagerController::class, 'page'])->name('admin.file-manager');
    Route::get('/gerenciador-arquivos', [App\Http\Controllers\FileManagerController::class, 'page']); // Alias em português
    Route::get('/file-manager/index', [App\Http\Controllers\FileManagerController::class, 'index'])->name('admin.file-manager.index');
    Route::post('/file-manager/upload', [App\Http\Controllers\FileManagerController::class, 'upload'])->name('admin.file-manager.upload');
    Route::post('/file-manager/create-directory', [App\Http\Controllers\FileManagerController::class, 'createDirectory'])->name('admin.file-manager.create-directory');
    Route::put('/file-manager/rename', [App\Http\Controllers\FileManagerController::class, 'rename'])->name('admin.file-manager.rename');
    Route::delete('/file-manager/delete', [App\Http\Controllers\FileManagerController::class, 'delete'])->name('admin.file-manager.delete');
    Route::post('/file-manager/move', [App\Http\Controllers\FileManagerController::class, 'move'])->name('admin.file-manager.move');
});

// Rotas de perfil para usuários regulares (apenas clientes)
Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications', [App\Http\Controllers\Store\NotificationsController::class, 'index'])->name('store.notifications');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Páginas customizadas (DEVE SER A ÚLTIMA ROTA - catch-all)
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');