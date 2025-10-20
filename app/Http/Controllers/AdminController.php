<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Setting;
use App\Models\StoreMenuItem;
use App\Http\Requests\AppearanceSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Período atual (mês atual)
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        
        // Período anterior (mês passado)
        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();
        
        // Totais gerais
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        // Métricas do mês atual
        $currentMonthOrders = Order::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $currentMonthRevenue = Order::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        // Métricas do mês anterior
        $previousMonthOrders = Order::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $previousMonthRevenue = Order::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        // Calcular percentuais de crescimento
        $ordersGrowth = $previousMonthOrders > 0 
            ? (($currentMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100 
            : ($currentMonthOrders > 0 ? 100 : 0);
            
        $revenueGrowth = $previousMonthRevenue > 0 
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : ($currentMonthRevenue > 0 ? 100 : 0);
        
        $recentOrders = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $stats = [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'total_products' => $totalProducts,
            'total_categories' => $totalCategories,
            'total_revenue' => $totalRevenue,
            'current_month_orders' => $currentMonthOrders,
            'current_month_revenue' => $currentMonthRevenue,
            'orders_growth' => round($ordersGrowth, 1),
            'revenue_growth' => round($revenueGrowth, 1),
        ];

        // Contar notificações não lidas
        $unreadCount = 0; // Por enquanto, sem sistema de notificações implementado
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'unreadCount'));
    }

    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Gerar slug único
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $count = 1;
        
        // Verificar se o slug já existe
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }
        
        $validated['slug'] = $slug;
        Category::create($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Gerar slug único apenas se o nome foi alterado
        if ($validated['name'] !== $category->name) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $count = 1;
            
            // Verificar se o slug já existe (excluindo a categoria atual)
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            
            $validated['slug'] = $slug;
        }
        
        $category->update($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories')
            ->with('success', 'Categoria excluída com sucesso!');
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'extraFields']);
        
        // Filtro por nome ou SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtro por status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filtro por destaque
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === '1');
        }
        
        // Filtro por faixa de preço
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $products = $query->paginate(20)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function createProduct()
    {
        $categories = Category::active()->orderBy('name')->get();
        
        // Prepare categories data with fields structure for JavaScript
        $categoriesData = [];
        foreach ($categories as $category) {
            $categoriesData[$category->id] = [
                'name' => $category->name,
                'fields' => [] // Empty for now, can be populated with dynamic fields later
            ];
        }
        
        // Carregar arquivos para o gerenciador
        $files = $this->loadFilesForManager();
        
        return view('admin.products.create', compact('categories', 'categoriesData', 'files'));
    }

    public function storeProduct(Request $request)
    {
        try {
            // Debug: Verificar o que está chegando
            \Log::info('📥 Request recebido - Imagens:', [
                'featured_image' => $request->input('featured_image'),
                'gallery_images' => $request->input('gallery_images'),
                'all_inputs' => $request->except(['_token', 'password'])
            ]);
            
            $validated = $request->validate([
                'categories' => 'required|array|min:1',
                'categories.*' => 'exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'short_description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
            'quantity_discount_enabled' => 'boolean',
            'quantity_discount_rules' => 'nullable|string',
            'whatsapp_quote_enabled' => 'boolean',
            'whatsapp_quote_text' => 'nullable|string|max:500',
                'sku' => 'required|string|unique:products,sku',
                'stock_quantity' => 'required|integer|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'featured_image' => 'nullable|string',
                'gallery_images' => 'nullable|array',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string',
            ], [
                'price.required' => 'O campo preço é obrigatório.',
                'price.numeric' => 'O preço deve ser um número válido.',
                'price.min' => 'O preço não pode ser negativo.',
                'sale_price.numeric' => 'O preço de promoção deve ser um número válido.',
                'sale_price.min' => 'O preço de promoção não pode ser negativo.',
                'sku.required' => 'O campo SKU é obrigatório.',
                'sku.unique' => 'Este SKU já está sendo usado por outro produto.',
                'categories.required' => 'Por favor, selecione pelo menos uma categoria.',
                'categories.min' => 'Por favor, selecione pelo menos uma categoria.',
                'stock_quantity.required' => 'O campo quantidade em estoque é obrigatório.',
                'stock_quantity.integer' => 'A quantidade em estoque deve ser um número inteiro.',
                'stock_quantity.min' => 'A quantidade em estoque não pode ser negativa.',
            ]);

            // Gerar slug único
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $count = 1;
            
            // Verificar se o slug já existe
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            
            $validated['slug'] = $slug;
            
            // Pegar a primeira categoria como category_id principal
            $validated['category_id'] = $validated['categories'][0];
            
            // Garantir que is_active e is_featured sejam booleanos
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['quantity_discount_enabled'] = $request->has('quantity_discount_enabled');
            
            // Processar regras de desconto por quantidade
            if ($request->filled('quantity_discount_rules')) {
                $rules = json_decode($request->quantity_discount_rules, true);
                if (is_array($rules)) {
                    // Validar e limpar regras
                    $validated['quantity_discount_rules'] = array_filter($rules, function($rule) {
                        return isset($rule['min_quantity']) && isset($rule['discount_percentage']) &&
                               $rule['min_quantity'] > 0 && $rule['discount_percentage'] >= 0 && $rule['discount_percentage'] <= 100;
                    });
                } else {
                    $validated['quantity_discount_rules'] = null;
                }
            } else {
                $validated['quantity_discount_rules'] = null;
            }
            
            // Remover 'categories' antes de criar (será tratado depois)
            $categories = $validated['categories'];
            unset($validated['categories']);
            
            // Tratar remoção de imagem destacada (se enviar string vazia, salvar como null)
            if (isset($validated['featured_image']) && empty($validated['featured_image'])) {
                $validated['featured_image'] = null;
            }
            
            // Tratar remoção de galeria (se enviar array vazio, salvar como null)
            if (isset($validated['gallery_images']) && (empty($validated['gallery_images']) || count($validated['gallery_images']) === 0)) {
                $validated['gallery_images'] = null;
            }
            
            // Debug: Ver o que vai ser salvo
            \Log::info('💾 Dados validados para criar produto:', [
                'featured_image' => $validated['featured_image'] ?? null,
                'gallery_images' => $validated['gallery_images'] ?? null,
            ]);
            
            $product = Product::create($validated);
            
            // Debug: Ver o que foi salvo
            \Log::info('✅ Produto criado:', [
                'id' => $product->id,
                'featured_image' => $product->featured_image,
                'gallery_images' => $product->gallery_images,
            ]);
            
            // Sincronizar categorias adicionais se houver
            // (Por enquanto apenas usando a primeira, mas preparado para múltiplas)

            return redirect()->route('admin.products.edit', $product)
                ->with('success', 'Produto criado com sucesso! Agora você pode adicionar campos extras.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor, corrija os erros abaixo.');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar produto: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    public function showProduct(Product $product)
    {
        $product->load('category', 'extraFields.options');
        return view('admin.products.show', compact('product'));
    }

    public function editProduct(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        
        // Prepare categories data with fields structure for JavaScript
        $categoriesData = [];
        foreach ($categories as $category) {
            $categoriesData[$category->id] = [
                'name' => $category->name,
                'fields' => [] // Empty for now, can be populated with dynamic fields later
            ];
        }
        
        // Carregar arquivos para o gerenciador
        $files = $this->loadFilesForManager();
        
        return view('admin.products.edit', compact('product', 'categories', 'categoriesData', 'files'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'nullable|array',
                'category_id.*' => 'exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'short_description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
            'quantity_discount_enabled' => 'boolean',
            'quantity_discount_rules' => 'nullable|string',
            'whatsapp_quote_enabled' => 'boolean',
            'whatsapp_quote_text' => 'nullable|string|max:500',
                'sku' => 'required|string|unique:products,sku,' . $product->id,
                'stock_quantity' => 'required|integer|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'featured_image' => 'nullable|string',
                'gallery_images' => 'nullable|array',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string',
            ], [
                'price.required' => 'O campo preço é obrigatório.',
                'price.numeric' => 'O preço deve ser um número válido.',
                'price.min' => 'O preço não pode ser negativo.',
                'sale_price.numeric' => 'O preço de promoção deve ser um número válido.',
                'sale_price.min' => 'O preço de promoção não pode ser negativo.',
                'sku.required' => 'O campo SKU é obrigatório.',
                'sku.unique' => 'Este SKU já está sendo usado por outro produto.',
            ]);

            // Gerar slug único apenas se o nome foi alterado
            if ($validated['name'] !== $product->name) {
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $count = 1;
                
                // Verificar se o slug já existe (excluindo o produto atual)
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $count;
                    $count++;
                }
                
                $validated['slug'] = $slug;
            }
            
            // Garantir que is_active e is_featured sejam booleanos
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['quantity_discount_enabled'] = $request->has('quantity_discount_enabled');
            
            // Processar regras de desconto por quantidade
            if ($request->filled('quantity_discount_rules')) {
                $rules = json_decode($request->quantity_discount_rules, true);
                if (is_array($rules)) {
                    // Validar e limpar regras
                    $validated['quantity_discount_rules'] = array_filter($rules, function($rule) {
                        return isset($rule['min_quantity']) && isset($rule['discount_percentage']) &&
                               $rule['min_quantity'] > 0 && $rule['discount_percentage'] >= 0 && $rule['discount_percentage'] <= 100;
                    });
                } else {
                    $validated['quantity_discount_rules'] = null;
                }
            } else {
                $validated['quantity_discount_rules'] = null;
            }
            
            // Usar a primeira categoria como category_id principal (se houver)
            if (isset($validated['category_id']) && is_array($validated['category_id']) && count($validated['category_id']) > 0) {
                $mainCategoryId = $validated['category_id'][0];
                $validated['category_id'] = $mainCategoryId;
            } else {
                // Se não houver categoria selecionada, manter a categoria atual
                unset($validated['category_id']);
            }
            
            // Tratar remoção de imagem destacada (se enviar string vazia, salvar como null)
            if (isset($validated['featured_image']) && empty($validated['featured_image'])) {
                $validated['featured_image'] = null;
            }
            
            // Tratar remoção de galeria (se enviar array vazio, salvar como null)
            if (isset($validated['gallery_images']) && (empty($validated['gallery_images']) || count($validated['gallery_images']) === 0)) {
                $validated['gallery_images'] = null;
            }
            
            \Log::info('💾 Atualizando produto:', [
                'product_id' => $product->id,
                'featured_image' => $validated['featured_image'] ?? 'não enviado',
                'gallery_images' => $validated['gallery_images'] ?? 'não enviado',
            ]);
            
            $product->update($validated);
            
            // Sincronizar categorias (se houver relacionamento many-to-many e categorias foram enviadas)
            if (method_exists($product, 'categories') && $request->has('category_id')) {
                $categoriesArray = $request->input('category_id', []);
                if (is_array($categoriesArray)) {
                    $product->categories()->sync($categoriesArray);
                }
            }

            return redirect()->route('admin.products.edit', $product)
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor, corrija os erros abaixo.');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function toggleProductStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'ativado' : 'desativado';
        return redirect()->back()
            ->with('success', "Produto {$status} com sucesso!");
    }

    public function duplicateProduct(Product $product)
    {
        try {
            // Criar uma cópia do produto
            $newProduct = $product->replicate();
            
            // Modificar alguns campos para evitar conflitos
            $newProduct->name = $product->name . ' (Cópia)';
            $newProduct->is_active = false; // Criar como inativo por segurança
            
            // Gerar novo slug único
            $baseSlug = Str::slug($newProduct->name);
            $slug = $baseSlug;
            $count = 1;
            
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            
            $newProduct->slug = $slug;
            
            // Gerar novo SKU único
            $lastProduct = Product::orderBy('id', 'desc')->first();
            $lastSkuNumber = 1;
            
            if ($lastProduct && $lastProduct->sku) {
                if (preg_match('/(\d+)/', $lastProduct->sku, $matches)) {
                    $lastSkuNumber = (int) $matches[1] + 1;
                }
            }
            
            $newProduct->sku = 'LSK-' . str_pad($lastSkuNumber, 7, '0', STR_PAD_LEFT);
            
            // Zerar estatísticas
            $newProduct->rating_average = 0;
            $newProduct->rating_count = 0;
            
            $newProduct->save();
            
            // Duplicar campos extras se existirem
            if ($product->extraFields()->exists()) {
                foreach ($product->extraFields as $extraField) {
                    $newProduct->extraFields()->attach($extraField->id, [
                        'is_required' => $extraField->pivot->is_required,
                        'sort_order' => $extraField->pivot->sort_order,
                        'field_settings' => $extraField->pivot->field_settings,
                    ]);
                }
            }
            
            // Duplicar campos de fórmula se existirem
            if ($product->formulaFields()->exists()) {
                foreach ($product->formulaFields as $formulaField) {
                    $newProduct->formulaFields()->attach($formulaField->id, [
                        'is_required' => $formulaField->pivot->is_required,
                        'sort_order' => $formulaField->pivot->sort_order,
                        'field_settings' => $formulaField->pivot->field_settings,
                    ]);
                }
            }
            
            return redirect()->route('admin.products.edit', $newProduct)
                ->with('success', 'Produto duplicado com sucesso! Revise as informações e ative quando estiver pronto.');
                
        } catch (\Exception $e) {
            Log::error('Erro ao duplicar produto: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao duplicar produto: ' . $e->getMessage());
        }
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')
            ->with('success', 'Produto excluído com sucesso!');
    }

    public function deleteMultipleProducts(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'exists:products,id'
            ]);

            $count = Product::whereIn('id', $validated['product_ids'])->delete();

            return redirect()->route('admin.products')
                ->with('success', "{$count} produto(s) excluído(s) com sucesso!");
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir múltiplos produtos: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao excluir produtos: ' . $e->getMessage());
        }
    }

    public function orders()
    {
        $orders = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load('orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // Disparar evento para notificação WhatsApp
        event(new \App\Events\OrderStatusChanged($order, $oldStatus, $validated['status']));

        return redirect()->back()
            ->with('success', 'Status do pedido atualizado e notificação enviada!');
    }

    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()
            ->with('success', 'Configurações salvas com sucesso!');
    }

    public function settingsGeneral()
    {
        $settings = Setting::whereIn('key', [
            'site_name', 'site_description', 'site_email', 'site_phone', 'site_address', 'footer_extra_text', 'admin_register_enabled'
        ])->pluck('value', 'key')->toArray();

        // Garantir que todas as chaves existam com valores padrão
        $defaultSettings = [
            'site_name' => 'Laser Link',
            'site_description' => 'Especialistas em Acrílicos, Troféus, Medalhas, Placas e Letreiros',
            'site_email' => 'contato@laserlink.com.br',
            'site_phone' => '(11) 99999-9999',
            'site_address' => '',
            'footer_extra_text' => '',
            'admin_register_enabled' => false
        ];

        $settings = array_merge($defaultSettings, $settings);

        return view('admin.settings.general', compact('settings'));
    }

    public function updateSettingsGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_email' => 'nullable|email',
            'site_phone' => 'nullable|string',
            'site_address' => 'nullable|string|max:500',
            'footer_extra_text' => 'nullable|string|max:1000',
            'admin_register_enabled' => 'boolean',
        ]);

        // Processar checkbox (se não marcado, não vem no request)
        $validated['admin_register_enabled'] = $request->has('admin_register_enabled') ? true : false;

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()
            ->with('success', 'Configurações gerais salvas!');
    }

    public function settingsGemini()
    {
        $settings = Setting::whereIn('key', [
            'gemini_api_key', 'gemini_model', 'gemini_temperature', 'gemini_max_tokens', 'gemini_enabled'
        ])->pluck('value', 'key')->toArray();

        // Valores padrão
        $defaultSettings = [
            'gemini_api_key' => '',
            'gemini_model' => 'gemini-2.5-flash',
            'gemini_temperature' => '0.7',
            'gemini_max_tokens' => '1024',
            'gemini_enabled' => '0'
        ];

        $settings = array_merge($defaultSettings, $settings);

        return view('admin.settings.gemini', compact('settings'));
    }

    public function updateSettingsGemini(Request $request)
    {
        $validated = $request->validate([
            'gemini_api_key' => 'nullable|string',
            'gemini_model' => 'required|string|in:gemini-2.5-flash,gemini-2.5-pro,gemini-2.0-flash,gemini-2.0-flash-001,gemini-2.5-flash-lite,gemini-2.0-flash-lite,gemini-2.0-flash-lite-001',
            'gemini_temperature' => 'required|numeric|between:0,1',
            'gemini_max_tokens' => 'required|integer|min:1|max:8192',
            'gemini_enabled' => 'boolean'
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()
            ->with('success', 'Configurações do Gemini AI salvas!');
    }

    public function listGeminiModels(Request $request)
    {
        try {
            $apiKey = Setting::get('gemini_api_key');
            
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chave da API não configurada.'
                ], 422);
            }
            
            $client = new \GuzzleHttp\Client();
            
            // Listar modelos disponíveis
            $response = $client->get('https://generativelanguage.googleapis.com/v1/models', [
                'query' => [
                    'key' => $apiKey,
                    'pageSize' => 50
                ],
                'verify' => false
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            // Filtrar apenas modelos que suportam generateContent
            $models = [];
            if (isset($data['models'])) {
                foreach ($data['models'] as $model) {
                    if (isset($model['supportedGenerationMethods']) && 
                        in_array('generateContent', $model['supportedGenerationMethods'])) {
                        $models[] = [
                            'name' => str_replace('models/', '', $model['name']),
                            'display_name' => $model['displayName'] ?? $model['name'],
                            'description' => $model['description'] ?? ''
                        ];
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'models' => $models
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar modelos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testGeminiConnection(Request $request)
    {
        try {
            // Verificar se Gemini está habilitado
            $geminiEnabled = Setting::get('gemini_enabled', false);
            $apiKey = Setting::get('gemini_api_key');
            $model = Setting::get('gemini_model', 'gemini-2.5-flash');
            
            // Diagnóstico detalhado
            $diagnostics = [
                'enabled' => (bool) $geminiEnabled,
                'has_api_key' => !empty($apiKey),
                'model' => $model
            ];
            
            if (!$geminiEnabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gemini AI está desabilitado. Ative a opção "Habilitar Gemini AI" e salve as configurações.',
                    'diagnostics' => $diagnostics
                ], 422);
            }
            
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chave da API não configurada. Insira sua chave do Google Gemini e salve as configurações.',
                    'diagnostics' => $diagnostics
                ], 422);
            }
            
            // Fazer teste direto com a API do Gemini
            $client = new \GuzzleHttp\Client();
            $temperature = (float) Setting::get('gemini_temperature', 0.7);
            $maxTokens = (int) Setting::get('gemini_max_tokens', 1024);
            
            $response = $client->post("https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent", [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $apiKey
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'Responda apenas com "OK" se você está funcionando.']
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => 100,
                    ]
                ],
                'verify' => false
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conexão com Gemini AI funcionando perfeitamente!',
                    'diagnostics' => $diagnostics,
                    'response' => $data['candidates'][0]['content']['parts'][0]['text']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Resposta inesperada da API do Gemini.',
                    'diagnostics' => $diagnostics,
                    'raw_response' => $data
                ], 422);
            }
            
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            $errorData = json_decode($errorBody, true);
            
            $errorMessage = 'Erro na API do Gemini: ';
            if (isset($errorData['error']['message'])) {
                $errorMessage .= $errorData['error']['message'];
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            Log::error('Erro ao testar Gemini (ClientException):', [
                'message' => $e->getMessage(),
                'error_body' => $errorBody,
                'status_code' => $e->getResponse()->getStatusCode()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error_details' => $errorData ?? null
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Erro ao testar Gemini (Exception): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function settingsWhatsApp()
    {
        $settings = Setting::whereIn('key', [
            'whatsapp_number', 'whatsapp_message'
        ])->pluck('value', 'key');

        return view('admin.settings.whatsapp', compact('settings'));
    }

    public function updateSettingsWhatsApp(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => 'required|string',
            'whatsapp_message' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()
            ->with('success', 'Configurações do WhatsApp salvas!');
    }

    public function settingsAppearance()
    {
        $settings = Setting::whereIn('key', [
            'primary_color', 'secondary_color', 'accent_color', 
            'logo_path', 'sidebar_logo_path', 'site_logo_path', 'blog_logo_path', 'footer_logo_path', 'favicon_path',
            'social_facebook', 'social_instagram', 'social_twitter', 'social_linkedin', 'social_youtube', 'social_tiktok', 'social_pinterest'
        ])->pluck('value', 'key')->toArray();
        
        // Garantir que todas as chaves existam com valores padrão
        $defaultSettings = [
            'primary_color' => '#EE0000',
            'secondary_color' => '#f8f9fa',
            'accent_color' => '#ffc107',
            'logo_path' => null,
            'sidebar_logo_path' => null,
            'site_logo_path' => null,
            'blog_logo_path' => null,
            'footer_logo_path' => null,
            'favicon_path' => null,
            // Redes Sociais
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_linkedin' => '',
            'social_youtube' => '',
            'social_tiktok' => '',
            'social_pinterest' => '',
        ];
        
        $settings = array_merge($defaultSettings, $settings);
        
        // Carregar arquivos para o gerenciador
        $files = $this->loadFilesForManager();
        
        return view('admin.settings.appearance', compact('settings', 'files'));
    }
    
    private function loadFilesForManager()
    {
        try {
            // Carregar todos os arquivos recursivamente
            $allFiles = collect();
            $this->loadAllFilesRecursively('', $allFiles);
            
            Log::info('Total de arquivos encontrados: ' . $allFiles->count());
            
            return $allFiles->sortBy('name')->values()->toArray();
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar arquivos: ' . $e->getMessage());
            return [];
        }
    }
    
    private function loadAllFilesRecursively($directory, &$allFiles)
    {
        try {
            // Listar todos os arquivos no diretório atual
            $files = Storage::disk('public')->allFiles($directory);
            
            foreach ($files as $file) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                
                // Filtrar apenas imagens
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'])) {
                    // Determinar pasta
                    $folder = 'Raiz';
                    if (strpos($file, '/') !== false) {
                        $pathParts = explode('/', $file);
                        $folder = $pathParts[0]; // Primeira pasta
                    }
                    
                    $allFiles->push([
                        'name' => basename($file),
                        'path' => $file,
                        'type' => 'file',
                        'url' => url('images/' . $file),
                        'size' => Storage::disk('public')->size($file),
                        'modified' => Storage::disk('public')->lastModified($file),
                        'extension' => $extension,
                        'folder' => $folder,
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar arquivos recursivamente: ' . $e->getMessage());
        }
    }
    

    public function updateSettingsAppearance(AppearanceSettingsRequest $request)
    {
        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoPath = $logoFile->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $logoPath]);
        }

        // Handle sidebar logo upload
        if ($request->hasFile('sidebar_logo')) {
            $sidebarLogoFile = $request->file('sidebar_logo');
            $sidebarLogoPath = $sidebarLogoFile->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'sidebar_logo_path'], ['value' => $sidebarLogoPath]);
        }

        // Handle public site logo upload
        if ($request->hasFile('site_logo')) {
            $siteLogoFile = $request->file('site_logo');
            $siteLogoPath = $siteLogoFile->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo_path'], ['value' => $siteLogoPath]);
        }

        // Handle blog logo upload
        if ($request->hasFile('blog_logo')) {
            $blogLogoFile = $request->file('blog_logo');
            $blogLogoPath = $blogLogoFile->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'blog_logo_path'], ['value' => $blogLogoPath]);
        }

        // Handle footer logo upload
        if ($request->hasFile('footer_logo')) {
            $footerLogoFile = $request->file('footer_logo');
            $footerLogoPath = $footerLogoFile->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'footer_logo_path'], ['value' => $footerLogoPath]);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconPath = $faviconFile->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'favicon_path'], ['value' => $faviconPath]);
        }

        // Handle image URL from file manager
        if ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
            $relativePath = $this->extractRelativePathFromUrl($imageUrl);
            
            if ($relativePath) {
                Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $relativePath]);
            }
        }

        // Handle sidebar image URL from file manager
        if ($request->filled('sidebar_image_url')) {
            $sidebarImageUrl = $request->sidebar_image_url;
            $relativePath = $this->extractRelativePathFromUrl($sidebarImageUrl);
            
            if ($relativePath) {
                Setting::updateOrCreate(['key' => 'sidebar_logo_path'], ['value' => $relativePath]);
            }
        }

        // Handle site logo image URL from file manager
        if ($request->filled('site_image_url')) {
            $siteImageUrl = $request->site_image_url;
            $relativePath = $this->extractRelativePathFromUrl($siteImageUrl);
            
            if ($relativePath) {
                Setting::updateOrCreate(['key' => 'site_logo_path'], ['value' => $relativePath]);
            }
        }

        // Handle blog logo image URL from file manager
        if ($request->filled('blog_image_url')) {
            $blogImageUrl = $request->blog_image_url;
            $relativePath = $this->extractRelativePathFromUrl($blogImageUrl);
            
            if ($relativePath) {
                Setting::updateOrCreate(['key' => 'blog_logo_path'], ['value' => $relativePath]);
            }
        }

        // Handle footer logo image URL from file manager
        if ($request->filled('footer_image_url')) {
            $footerImageUrl = $request->footer_image_url;
            $relativePath = $this->extractRelativePathFromUrl($footerImageUrl);
            
            if ($relativePath) {
                Setting::updateOrCreate(['key' => 'footer_logo_path'], ['value' => $relativePath]);
            }
        }

        // Handle favicon image URL from file manager
        if ($request->filled('favicon_image_url')) {
            $faviconImageUrl = $request->favicon_image_url;
            $relativePath = $this->extractRelativePathFromUrl($faviconImageUrl);
            
            if ($relativePath) {
                Setting::updateOrCreate(['key' => 'favicon_path'], ['value' => $relativePath]);
            }
        }

        // Handle other settings
        foreach ($validated as $key => $value) {
            // Ignore file inputs and helper URL fields that are processed above
            if (in_array($key, [
                'logo', 'sidebar_logo', 'site_logo', 'blog_logo', 'footer_logo', 'favicon',
                'image_url', 'sidebar_image_url', 'site_image_url', 'blog_image_url', 'footer_image_url', 'favicon_image_url',
            ], true)) {
                continue;
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()
            ->with('success', 'Configurações de aparência salvas!');
    }

    private function extractRelativePathFromUrl(string $url): ?string
    {
        // Se for uma URL completa (http/https), extrair caminho
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            $path = parse_url($url, PHP_URL_PATH) ?? '';
            if ($path === '') {
                return null;
            }
            // Se vier com /storage/, retornar relativo ao disco public
            if (strpos($path, '/storage/') !== false) {
                $parts = explode('/storage/', $path, 2);
                return $parts[1] ?? null;
            }
            // Caso contrário, use o caminho limpo (ex.: /logos/arquivo.png ou logos/arquivo.png)
            return ltrim($path, '/');
        }

        // Se for um caminho que já começa com /storage/
        if (strpos($url, '/storage/') === 0) {
            return ltrim(str_replace('/storage/', '', $url), '/');
        }

        // Se já for um caminho relativo direto
        return ltrim($url, '/');
    }

    private function storeExternalImageToPublic(string $url, string $prefix = 'logos'): ?string
    {
        try {
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $response = $client->get($url);
            if ($response->getStatusCode() !== 200) {
                Log::warning('Falha ao baixar imagem externa', ['url' => $url, 'status' => $response->getStatusCode()]);
                return null;
            }

            $path = parse_url($url, PHP_URL_PATH) ?? '';
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'png');
            if (!in_array($extension, ['jpg','jpeg','png','gif','svg','webp','bmp','ico'])) {
                $extension = 'png';
            }

            $filename = rtrim($prefix, '/').'/'.uniqid('logo_', true).'.'.$extension;
            Storage::disk('public')->put($filename, $response->getBody()->getContents());
            return $filename;
        } catch (\Exception $e) {
            Log::error('Erro ao baixar imagem externa', ['url' => $url, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function settingsSeo()
    {
        $settings = Setting::whereIn('key', [
            'meta_title', 'meta_description', 'meta_keywords'
        ])->pluck('value', 'key');

        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSettingsSeo(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'required|string|max:60',
            'meta_description' => 'required|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'google_analytics' => 'nullable|string',
            'google_search_console' => 'nullable|string',
            // Schema.org fields
            'schema_company_name' => 'required|string|max:255',
            'schema_phone' => 'required|string|max:50',
            'schema_street' => 'required|string|max:255',
            'schema_city' => 'required|string|max:100',
            'schema_state' => 'required|string|max:2',
            'schema_postal_code' => 'required|string|max:20',
            'schema_facebook' => 'nullable|url',
            'schema_instagram' => 'nullable|url',
            'schema_opening_hours_start' => 'nullable|string',
            'schema_opening_hours_end' => 'nullable|string',
            'schema_price_range' => 'nullable|string|in:$,$$,$$$,$$$$',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()
            ->with('success', 'Configurações de SEO e Schema.org salvas com sucesso!');
    }

    public function generateDescription(Request $request)
    {
        try {
            // Normalizar nome do produto vindo do front ("name" ou "product_name")
            if (!$request->filled('product_name') && $request->filled('name')) {
                $request->merge(['product_name' => $request->input('name')]);
            }

            // Validação com resposta JSON (evita redirecionamento HTML)
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'category_name' => 'nullable|string|max:255',
                'materials' => 'nullable|string',
                'dimensions' => 'nullable|string',
                'use_case' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();

            // Generate AI-powered description based on product information
            $aiData = $this->generateAIDescription($validated);

            if (!$aiData) {
                return response()->json([
                    'success' => false,
                    'message' => 'IA indisponível. Configure a chave do Gemini e habilite a IA em Configurações > Gemini AI.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'short_description' => $aiData['short_description'] ?? '',
                'description' => $aiData['description'] ?? '',
                'seo_tags' => $aiData['seo_tags'] ?? '',
                'meta_title' => $aiData['meta_title'] ?? '',
                'meta_description' => $aiData['meta_description'] ?? '',
                'meta_keywords' => $aiData['meta_keywords'] ?? '',
                'sku' => $aiData['sku'] ?? $this->generateSKU($validated['product_name'])
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no método generateDescription:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Mensagem mais amigável baseada no tipo de erro
            $errorMessage = 'Erro ao gerar descrição. ';
            if (str_contains($e->getMessage(), 'UTF-8')) {
                $errorMessage .= 'Problema de codificação de caracteres. Tente novamente.';
            } elseif (str_contains($e->getMessage(), 'API')) {
                $errorMessage .= 'Problema ao conectar com a IA. Verifique sua chave do Gemini.';
            } elseif (str_contains($e->getMessage(), 'JSON')) {
                $errorMessage .= 'Resposta da IA inválida. Tente novamente.';
            } else {
                $errorMessage .= 'Tente novamente ou entre em contato com o suporte.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno',
                'debug_info' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    private function generateAIDescription(array $data): ?array
    {
        $name = $data['product_name'];
        
        try {
            // Verificar se Gemini está habilitado
            $geminiEnabled = Setting::get('gemini_enabled', false);
            $apiKey = Setting::get('gemini_api_key');
            
            
            
            if ($geminiEnabled && !empty($apiKey)) {
                // Integração com Gemini AI (passando dados de contexto)
                $geminiResponse = $this->callGeminiAI($name, $data);
                
                if ($geminiResponse) {
                    
                    return [
                        'short_description' => $geminiResponse['short_description'] ?? '',
                        'description' => $geminiResponse['description'] ?? '',
                        'seo_tags' => $geminiResponse['seo_tags'] ?? '',
                        'meta_title' => $geminiResponse['meta_title'] ?? $this->generateMetaTitle($name),
                        'meta_description' => $geminiResponse['meta_description'] ?? $geminiResponse['short_description'] ?? '',
                        'meta_keywords' => $geminiResponse['seo_tags'] ?? '',
                        'sku' => $geminiResponse['sku'] ?? $this->generateSKU($name)
                    ];
                } else {
                    
                }
            } else {
                
            }
        } catch (\Exception $e) {
            
        }

        // Se IA não estiver disponível, não retornar conteúdo mockado
        return null;
    }

    private function callGeminiAI(string $productName, array $data = []): ?array
    {
        // Verificar se Gemini está habilitado
        $geminiEnabled = Setting::get('gemini_enabled', false);
        if (!$geminiEnabled) {
            return null;
        }

        $apiKey = Setting::get('gemini_api_key');
        if (!$apiKey) {
            return null;
        }

        // Extrair palavra-chave principal do título
        $mainKeyword = $this->extractMainKeyword($productName);
        
        // Construir contexto específico do produto
        $context = $this->buildProductContext($data);
        
        $prompt = "Você é um ESPECIALISTA em comunicação visual que trabalha com materiais nobres, sua função é gerar conteúdo SEO OTIMIZADO e ESPECÍFICO para este produto de comunicação visual.
        
        CONTEXTO DO PRODUTO:
        - Nome: {$productName}
        - Categoria: {$context['category']}
        - Tipo: {$context['type']}
        - Materiais: {$context['materials']}
        - Dimensões: {$context['dimensions']}
        - Aplicação: {$context['use_case']}
        
        TAREFA: Gerar conteúdo SEO OTIMIZADO e ESPECÍFICO para este produto de comunicação visual.
        
        ESTRATÉGIA SEO ESPECÍFICA:
        1. Foque no contexto real do produto (categoria, materiais, aplicações)
        2. Use linguagem técnica e específica da área
        3. Mencione benefícios reais para o cliente final
        4. Inclua aplicações práticas e casos de uso
        5. Seja específico sobre materiais e acabamentos
        
        INSTRUÇÕES CRÍTICAS:
        - Seja específico sobre o produto, não genérico
        - Mencione materiais, acabamentos e aplicações reais
        - Use linguagem técnica da comunicação visual
        - Foque em benefícios para empresas e profissionais
        - Inclua casos de uso específicos
        - Seja persuasivo mas técnico
        - Use linguagem natural, sem excesso de maiúsculas
        
        Retorne um JSON com:
        - short_description: descrição curta específica (máx 150 chars) - fale sobre o produto real
        - description: descrição completa e técnica - explique materiais, aplicações, benefícios específicos
        - seo_tags: tags SEO específicas da área de comunicação visual
        - meta_title: título otimizado e específico (máx 60 chars)
        - meta_description: descrição convincente e específica (EXATAMENTE 160 chars ou menos)
        - meta_keywords: palavras-chave específicas da comunicação visual
        - sku: código SKU único baseado no título e sequência lógica
        
        IMPORTANTE: Meta Description deve ter EXATAMENTE 160 caracteres ou menos. Seja conciso e direto!
        
        EXEMPLO DE ESTRUTURA ESPECÍFICA:
        - Para placas: 'Placa de Sinalização em Acrílico 3mm | Laser Link'
        - Para troféus: 'Troféu Personalizado em Acrílico | Laser Link'
        - Para displays: 'Display de Balcão Prisma | Laser Link'
        
        Seja específico, técnico e foque no produto real!";

        // Obter configurações do banco de dados
        $model = Setting::get('gemini_model', 'gemini-2.5-flash');
        $temperature = (float) Setting::get('gemini_temperature', 0.7);
        $maxTokens = (int) Setting::get('gemini_max_tokens', 1024);

        $client = new \GuzzleHttp\Client();
        
        Log::info('Chamando Gemini AI:', [
            'model' => $model,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'product_name' => $productName
        ]);
        
        try {
            $response = $client->post("https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent", [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $apiKey
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => $maxTokens,
                    ]
                ],
                'verify' => false  // Desabilitar verificação SSL
            ]);
            
            Log::info('Gemini AI Response Status:', [
                'status' => $response->getStatusCode(),
                'headers' => $response->getHeaders()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro na chamada da API Gemini:', [
                'error' => $e->getMessage(),
                'product_name' => $productName
            ]);
            return null;
        }

        // Pegar o conteúdo da resposta e garantir UTF-8 válido
        $responseBody = $response->getBody()->getContents();
        
        // Limpar e validar UTF-8
        $responseBody = mb_convert_encoding($responseBody, 'UTF-8', 'UTF-8');
        $responseBody = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $responseBody);
        
        $data = json_decode($responseBody, true);
        
        // Verificar erros de JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Erro ao decodificar JSON da resposta Gemini:', [
                'error' => json_last_error_msg(),
                'response_preview' => substr($responseBody, 0, 500)
            ]);
            return null;
        }
        
        Log::info('Gemini AI Raw Response:', [
            'response_data' => $data,
            'has_candidates' => isset($data['candidates']),
            'candidates_count' => isset($data['candidates']) ? count($data['candidates']) : 0
        ]);
        
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
            
            // Limpar UTF-8 da resposta também
            $aiResponse = mb_convert_encoding($aiResponse, 'UTF-8', 'UTF-8');
            $aiResponse = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $aiResponse);
            
            // Tentar extrair JSON da resposta
            if (preg_match('/\{.*\}/s', $aiResponse, $matches)) {
                $jsonString = mb_convert_encoding($matches[0], 'UTF-8', 'UTF-8');
                $jsonData = json_decode($jsonString, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Validar e ajustar limites de caracteres
                    // Limpar todos os campos de caracteres inválidos
                    foreach ($jsonData as $key => $value) {
                        if (is_string($value)) {
                            $jsonData[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                            $jsonData[$key] = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $jsonData[$key]);
                        }
                    }
                    
                    if (isset($jsonData['meta_description']) && mb_strlen($jsonData['meta_description']) > 160) {
                        $jsonData['meta_description'] = mb_substr($jsonData['meta_description'], 0, 157) . '...';
                    }
                    if (isset($jsonData['meta_title']) && mb_strlen($jsonData['meta_title']) > 60) {
                        $jsonData['meta_title'] = mb_substr($jsonData['meta_title'], 0, 57) . '...';
                    }
                    if (isset($jsonData['short_description']) && mb_strlen($jsonData['short_description']) > 150) {
                        $jsonData['short_description'] = mb_substr($jsonData['short_description'], 0, 147) . '...';
                    }
                    
                    Log::info('JSON processado com sucesso:', $jsonData);
                    
                    return $jsonData;
                } else {
                    Log::error('Erro ao decodificar JSON extraído:', [
                        'error' => json_last_error_msg(),
                        'json_preview' => substr($matches[0], 0, 200)
                    ]);
                }
            } else {
                Log::warning('Não foi possível extrair JSON da resposta do Gemini');
            }
            
            // Se não conseguir extrair JSON, retornar apenas SKU e meta title
            Log::warning('Usando fallback para geração de conteúdo');
            
            return [
                'short_description' => '',
                'description' => '',
                'seo_tags' => '',
                'meta_title' => $this->generateMetaTitle($productName),
                'meta_description' => '',
                'meta_keywords' => '',
                'sku' => $this->generateSKU($productName)
            ];
        }
        
        return null;
    }


    private function extractKeywords(string $productName): string
    {
        $keywords = [
            'comunicação visual',
            'sinalização',
            'qualidade',
            'profissional',
            'durabilidade'
        ];

        // Adicionar palavras do nome do produto
        $words = explode(' ', strtolower($productName));
        $keywords = array_merge($keywords, array_filter($words, function($word) {
            return strlen($word) > 3;
        }));

        return implode(', ', array_unique($keywords));
    }

    /**
     * Gera conteúdo padrão quando IA está desabilitada/indisponível
     */
    private function generateFallbackContent(string $productName, array $data): array
    {
        $keywords = $this->extractKeywords($productName);
        $metaTitle = $this->generateMetaTitle($productName);
        $short = sprintf('%s com acabamento profissional e alta durabilidade. Ideal para comunicação visual de alto impacto.', $productName);
        $desc = sprintf('O %s é produzido com materiais de qualidade e acabamento preciso, garantindo excelente apresentação e resistência. Indicado para ambientes corporativos, eventos e pontos de venda. Personalizamos dimensões, cores e aplicações conforme sua necessidade.', $productName);
        $metaDesc = mb_substr($desc, 0, 157).'...';

        return [
            'short_description' => $short,
            'description' => $desc,
            'seo_tags' => $keywords,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'meta_keywords' => $keywords,
        ];
    }

    private function generateMetaTitle(string $productName): string
    {
        $maxLength = 60;
        $suffix = ' | Laser Link';
        $availableLength = $maxLength - strlen($suffix);
        
        if (strlen($productName) <= $availableLength) {
            return $productName . $suffix;
        } else {
            return substr($productName, 0, $availableLength - 3) . '...' . $suffix;
        }
    }

    private function buildProductContext(array $data): array
    {
        $context = [
            'category' => $data['category_name'] ?? 'Comunicação Visual',
            'materials' => $data['materials'] ?? 'Materiais de alta qualidade',
            'dimensions' => $data['dimensions'] ?? 'Dimensões personalizáveis',
            'use_case' => $data['use_case'] ?? 'Aplicação corporativa e profissional'
        ];
        
        // Melhorar contexto baseado na categoria
        if (isset($data['category_name'])) {
            $category = strtolower($data['category_name']);
            
            if (strpos($category, 'placa') !== false) {
                $context['materials'] = 'Acrílico, MDF ou PS com acabamento profissional';
                $context['use_case'] = 'Sinalização corporativa, identificação e comunicação visual';
            } elseif (strpos($category, 'troféu') !== false) {
                $context['materials'] = 'Acrílico, metal ou MDF com gravação personalizada';
                $context['use_case'] = 'Premiação, reconhecimento e eventos corporativos';
            } elseif (strpos($category, 'display') !== false) {
                $context['materials'] = 'Acrílico ou MDF com acabamento premium';
                $context['use_case'] = 'Exposição de produtos, balcão e ponto de venda';
            } elseif (strpos($category, 'letreiro') !== false) {
                $context['materials'] = 'Acrílico, MDF ou PS com iluminação opcional';
                $context['use_case'] = 'Identificação visual, fachadas e comunicação empresarial';
            }
        }
        
        return $context;
    }

    private function extractMainKeyword(string $productName): string
    {
        // Remover palavras comuns e extrair a palavra-chave principal
        $commonWords = ['de', 'da', 'do', 'das', 'dos', 'para', 'com', 'em', 'na', 'no', 'nas', 'nos', 'personalizada', 'personalizado', 'customizada', 'customizado'];
        
        // Converter para minúsculas e dividir em palavras
        $words = explode(' ', strtolower($productName));
        
        // Filtrar palavras comuns e palavras muito curtas
        $keywords = array_filter($words, function($word) use ($commonWords) {
            return strlen($word) > 3 && !in_array($word, $commonWords);
        });
        
        // Se não encontrar palavras-chave, usar a primeira palavra significativa
        if (empty($keywords)) {
            $words = explode(' ', $productName);
            return $words[0] ?? $productName;
        }
        
        // Retornar a primeira palavra-chave encontrada
        return ucfirst(reset($keywords));
    }

    private function generateSKU(string $productName): string
    {
        // Obter o último SKU gerado
        $lastProduct = \App\Models\Product::orderBy('id', 'desc')->first();
        $lastSkuNumber = 1;
        
        if ($lastProduct && $lastProduct->sku) {
            // Extrair número do último SKU (qualquer formato)
            if (preg_match('/(\d+)/', $lastProduct->sku, $matches)) {
                $lastSkuNumber = (int) $matches[1] + 1;
            }
        }
        
        // Gerar SKU com prefixo LSK e sequência numérica (formato: LSK-0015887)
        return 'LSK-' . str_pad($lastSkuNumber, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Exibir página de perfil do admin
     */
    public function profile(Request $request)
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Atualizar informações do perfil do admin
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Atualizar senha do admin
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Senha atualizada com sucesso!');
    }

    /**
     * Excluir conta do admin
     */
    public function deleteProfile(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = $request->user();
        
        Auth::logout();
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Conta excluída com sucesso!');
    }

    /**
     * Exibir configurações da loja virtual
     */
    public function storeSettings()
    {
        $menuItems = StoreMenuItem::ordered()->get();
        $settings = Setting::whereIn('key', [
            'store_bottom_menu_enabled',
            'store_menu_style',
            'store_menu_position',
            'home_banner_image',
            'home_banners',
            'home_banners_mobile',
            'home_categories',
            'contact_map_embed_url',
            'contact_faq',
            'delivery_time_text'
        ])->pluck('value', 'key')->toArray();

        // Valores padrão
        $defaultSettings = [
            'store_bottom_menu_enabled' => true,
            'store_menu_style' => 'modern',
            'store_menu_position' => 'bottom',
            'home_banner_image' => null,
            'home_banners' => '[]',
            'home_banners_mobile' => '[]',
            'home_categories' => '[]',
            'contact_map_embed_url' => '',
            'contact_faq' => '[]',
            'delivery_time_text' => 'O prazo de entrega é de até 5 dias úteis'
        ];

        $settings = array_merge($defaultSettings, $settings);
        
        // Carregar arquivos para o gerenciador
        $files = $this->loadFilesForManager();
        
        // Carregar todas as categorias para seleção
        $allCategories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.settings.store', compact('menuItems', 'settings', 'files', 'allCategories'));
    }

    /**
     * Atualizar configurações da loja virtual
     */
    public function updateStoreSettings(Request $request)
    {
        $validated = $request->validate([
            'store_bottom_menu_enabled' => 'nullable|in:true,false,1,0,on,off',
            'store_menu_style' => 'required|string|in:modern,classic,minimal',
            'store_menu_position' => 'required|string|in:bottom,top',
            'home_banner_images' => 'nullable|array',
            'home_banner_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp,avif|max:5120',
            'home_banners_json' => 'nullable|string',
            'home_banner_mobile_images' => 'nullable|array',
            'home_banner_mobile_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp,avif|max:5120',
            'home_banners_mobile_json' => 'nullable|string',
            'home_categories_json' => 'nullable|string',
            'contact_map_embed_url' => 'nullable|string|max:1000',
            'contact_faq_json' => 'nullable|string',
            'delivery_time_text' => 'nullable|string|max:500',
            'menu_items' => 'array',
            'menu_items.*.name' => 'required|string|max:255',
            'menu_items.*.url' => 'required|string|max:500',
            'menu_items.*.icon' => 'nullable|string|max:100',
            'menu_items.*.is_active' => 'nullable|in:true,false,1,0,on,off',
            'menu_items.*.is_external' => 'nullable|in:true,false,1,0,on,off',
        ]);

        // Converter strings para booleanos
        $validated['store_bottom_menu_enabled'] = filter_var($validated['store_bottom_menu_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // Salvar configurações gerais
        foreach (['store_bottom_menu_enabled', 'store_menu_style', 'store_menu_position', 'contact_map_embed_url', 'delivery_time_text'] as $key) {
            if (isset($validated[$key])) {
                Setting::updateOrCreate(['key' => $key], ['value' => $validated[$key]]);
            }
        }

        // Processar múltiplos banners
        $bannersArray = [];
        
        // 1. Carregar banners existentes do JSON
        if ($request->filled('home_banners_json')) {
            $existingBanners = json_decode($request->input('home_banners_json'), true);
            if (is_array($existingBanners)) {
                foreach ($existingBanners as $banner) {
                    // Manter banners que já existem (não são novos uploads)
                    if (isset($banner['path']) && !isset($banner['isNew'])) {
                        $bannersArray[] = $banner['path'];
                    }
                }
            }
        }
        
        // 2. Processar novos uploads
        if ($request->hasFile('home_banner_images')) {
            foreach ($request->file('home_banner_images') as $file) {
                $path = $file->store('banners', 'public');
                $bannersArray[] = $path;
            }
        }
        
        // 3. Salvar array de banners desktop como JSON
        Setting::updateOrCreate(['key' => 'home_banners'], ['value' => json_encode($bannersArray)]);
        
        // Manter compatibilidade com banner único antigo
        if (count($bannersArray) > 0) {
            Setting::updateOrCreate(['key' => 'home_banner_image'], ['value' => $bannersArray[0]]);
        } else {
            Setting::updateOrCreate(['key' => 'home_banner_image'], ['value' => null]);
        }

        // Processar múltiplos banners mobile
        $bannersMobileArray = [];
        
        // 1. Carregar banners mobile existentes do JSON
        if ($request->filled('home_banners_mobile_json')) {
            $existingBannersMobile = json_decode($request->input('home_banners_mobile_json'), true);
            if (is_array($existingBannersMobile)) {
                foreach ($existingBannersMobile as $banner) {
                    // Manter banners que já existem (não são novos uploads)
                    if (isset($banner['path']) && !isset($banner['isNew'])) {
                        $bannersMobileArray[] = $banner['path'];
                    }
                }
            }
        }
        
        // 2. Processar novos uploads de banners mobile
        if ($request->hasFile('home_banner_mobile_images')) {
            foreach ($request->file('home_banner_mobile_images') as $file) {
                $path = $file->store('banners/mobile', 'public');
                $bannersMobileArray[] = $path;
            }
        }
        
        // 3. Salvar array de banners mobile como JSON
        Setting::updateOrCreate(['key' => 'home_banners_mobile'], ['value' => json_encode($bannersMobileArray)]);

        // Processar categorias da home
        if ($request->filled('home_categories_json')) {
            $categoriesData = json_decode($request->input('home_categories_json'), true);
            if (is_array($categoriesData)) {
                Setting::updateOrCreate(['key' => 'home_categories'], ['value' => json_encode($categoriesData)]);
            }
        }
        
        // Processar FAQs
        if ($request->filled('contact_faq_json')) {
            $faqData = json_decode($request->input('contact_faq_json'), true);
            if (is_array($faqData)) {
                // Filtrar FAQs vazias
                $faqData = array_filter($faqData, function($faq) {
                    return !empty($faq['question']) && !empty($faq['answer']);
                });
                Setting::updateOrCreate(['key' => 'contact_faq'], ['value' => json_encode(array_values($faqData))]);
            }
        }

        // Atualizar itens do menu
        if (isset($validated['menu_items'])) {
            StoreMenuItem::truncate(); // Limpar itens existentes
            
            foreach ($validated['menu_items'] as $index => $item) {
                StoreMenuItem::create([
                    'name' => $item['name'],
                    'url' => $item['url'],
                    'icon' => $item['icon'] ?? null,
                    'is_active' => filter_var($item['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'is_external' => filter_var($item['is_external'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'sort_order' => $index
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Configurações da loja virtual salvas com sucesso!');
    }

    /**
     * Reordenar itens do menu via AJAX
     */
    public function reorderMenuItems(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:store_menu_items,id'
        ]);

        foreach ($validated['items'] as $index => $itemId) {
            StoreMenuItem::where('id', $itemId)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
    

    public function settingsEmail()
    {
        $settings = Setting::whereIn('key', [
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
            'notify_new_user', 'notify_new_order', 'send_welcome_email', 'send_order_confirmation'
        ])->pluck('value', 'key')->toArray();

        // Valores padrão
        $defaultSettings = [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '465',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'ssl',
            'mail_from_address' => 'noreply@seusite.com.br',
            'mail_from_name' => 'Seu Site',
            'notify_new_user' => '1',
            'notify_new_order' => '1',
            'send_welcome_email' => '1',
            'send_order_confirmation' => '1'
        ];

        $settings = array_merge($defaultSettings, $settings);

        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Atualizar configurações de Email/SMTP
     */
    public function updateSettingsEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'notify_new_user' => 'boolean',
            'notify_new_order' => 'boolean',
            'send_welcome_email' => 'boolean',
            'send_order_confirmation' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }
        
        // Atualizar configurações do config para uso imediato
        config(['mail.mailers.smtp.host' => $validated['mail_host']]);
        config(['mail.mailers.smtp.port' => $validated['mail_port']]);
        config(['mail.mailers.smtp.username' => $validated['mail_username']]);
        config(['mail.mailers.smtp.password' => $validated['mail_password']]);
        config(['mail.from.address' => $validated['mail_from_address']]);
        config(['mail.from.name' => $validated['mail_from_name']]);

        return redirect()->route('admin.settings.email')->with('success', 'Configurações de email atualizadas com sucesso!');
    }

    /**
     * Testar conexão SMTP
     */
    public function testEmailConnection(Request $request)
    {
        try {
            $testEmail = $request->input('test_email', Setting::get('mail_from_address'));
            
            // Configurar SMTP com as settings atuais
            config(['mail.default' => Setting::get('mail_mailer', 'smtp')]);
            config(['mail.mailers.smtp.host' => Setting::get('mail_host')]);
            config(['mail.mailers.smtp.port' => Setting::get('mail_port')]);
            config(['mail.mailers.smtp.username' => Setting::get('mail_username')]);
            config(['mail.mailers.smtp.password' => Setting::get('mail_password')]);
            config(['mail.mailers.smtp.encryption' => Setting::get('mail_encryption')]);
            config(['mail.from.address' => Setting::get('mail_from_address')]);
            config(['mail.from.name' => Setting::get('mail_from_name')]);
            
            // Enviar email de teste
            $appName = config('app.name', 'Sistema');
            Mail::raw("Este é um email de teste do sistema {$appName}. Se você recebeu esta mensagem, sua configuração SMTP está funcionando corretamente!", function ($message) use ($testEmail, $appName) {
                $message->to($testEmail)
                        ->subject("Teste de Configuração SMTP - {$appName}");
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Email de teste enviado com sucesso para ' . $testEmail
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Exibir configurações de Sitemap
     */
    public function settingsSitemap()
    {
        $sitemapService = app(\App\Services\SitemapService::class);
        
        $sitemapData = [
            'exists' => $sitemapService->exists(),
            'last_generated' => $sitemapService->getLastGenerated(),
            'file_size' => $sitemapService->getFileSize(),
            'url_count' => $sitemapService->countUrls(),
            'sitemap_url' => url('sitemap.xml'),
        ];
        
        return view('admin.settings.sitemap', compact('sitemapData'));
    }
    
    /**
     * Gerar sitemap
     */
    public function generateSitemap()
    {
        try {
            $sitemapService = app(\App\Services\SitemapService::class);
            $sitemapService->generate();
            
            return redirect()->route('admin.settings.sitemap')
                ->with('success', 'Sitemap gerado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.sitemap')
                ->with('error', 'Erro ao gerar sitemap: ' . $e->getMessage());
        }
    }

    /**
     * Exibe configurações de cache
     */
    public function settingsCache()
    {
        $cacheEnabled = \App\Models\Setting::get('cache_enabled', true);
        
        return view('admin.settings.cache', compact('cacheEnabled'));
    }

    /**
     * Limpa todos os caches
     */
    public function clearCache()
    {
        try {
            \Artisan::call('optimize:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            \Artisan::call('event:clear');
            
            return redirect()->route('admin.settings.cache')
                ->with('success', 'Cache limpo com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.cache')
                ->with('error', 'Erro ao limpar cache: ' . $e->getMessage());
        }
    }

    /**
     * Ativa/Desativa o cache
     */
    public function toggleCache(Request $request)
    {
        try {
            $enabled = $request->input('enabled', false);
            \App\Models\Setting::set('cache_enabled', $enabled);
            
            if ($enabled) {
                // Gera os caches otimizados
                \Artisan::call('config:cache');
                \Artisan::call('route:cache');
                \Artisan::call('view:cache');
                
                $message = 'Cache ativado e otimizado com sucesso!';
            } else {
                // Limpa os caches
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                
                $message = 'Cache desativado com sucesso!';
            }
            
            return redirect()->route('admin.settings.cache')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.cache')
                ->with('error', 'Erro ao alterar cache: ' . $e->getMessage());
        }
    }
}