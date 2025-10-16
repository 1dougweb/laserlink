<?php

use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API da loja (AJAX) - sem CSRF protection
Route::middleware(['api'])->group(function () {
    // Busca de produtos
    Route::get('/search', [StoreController::class, 'searchProducts'])->name('api.search');
    
    Route::post('/calcular-preco', [StoreController::class, 'calculatePrice'])->name('api.calculate-price');
    Route::post('/adicionar-carrinho', [StoreController::class, 'addToCart'])->name('api.add-to-cart');
    Route::post('/remover-carrinho', [StoreController::class, 'removeFromCart'])->name('api.remove-from-cart');
    Route::post('/atualizar-quantidade', [StoreController::class, 'updateCartQuantity'])->name('api.update-cart-quantity');

    // API de checkout
    Route::prefix('checkout')->group(function () {
        Route::post('/process', [App\Http\Controllers\CheckoutController::class, 'processOrder'])->name('api.checkout.process');
        Route::post('/process-cart', [App\Http\Controllers\CheckoutController::class, 'processCartOrder'])->name('api.checkout.process-cart');
        Route::post('/clear-session', [App\Http\Controllers\CheckoutController::class, 'clearSession'])->name('api.checkout.clear-session');
    });

    // API para campos extras
    Route::prefix('extra-fields')->group(function () {
        Route::get('/config', [App\Http\Controllers\ExtraFieldController::class, 'getConfig'])->name('api.extra-fields.config');
        Route::get('/field/{fieldSlug}/options', [App\Http\Controllers\ExtraFieldController::class, 'getFieldOptions'])->name('api.extra-fields.field-options');
        Route::post('/calculate-price', [App\Http\Controllers\ExtraFieldController::class, 'calculatePrice'])->name('api.extra-fields.calculate-price');
        Route::post('/validate', [App\Http\Controllers\ExtraFieldController::class, 'validateSelections'])->name('api.extra-fields.validate');
    });

    // API para campos dinâmicos
    Route::prefix('dynamic-fields')->group(function () {
        Route::get('/products/{product}/fields-config', [App\Http\Controllers\Api\DynamicFieldsController::class, 'getFieldsConfig'])->name('api.dynamic-fields.config');
        Route::post('/products/{product}/validate-fields', [App\Http\Controllers\Api\DynamicFieldsController::class, 'validateFields'])->name('api.dynamic-fields.validate');
        Route::post('/products/{product}/calculate-price', [App\Http\Controllers\Api\DynamicFieldsController::class, 'calculatePrice'])->name('api.dynamic-fields.calculate-price');
    });

    // API de notificações
    Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\NotificationsController::class, 'get'])->name('notifications.get');
        Route::post('/{id}/mark-read', [App\Http\Controllers\Api\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\Api\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });

    // (removido) API para usuários online
});