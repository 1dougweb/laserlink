<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Post;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Events\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index(): View
    {
            $featuredProducts = Product::where('is_active', true)
            ->orderBy('is_featured', 'desc') // Produtos em destaque primeiro
            ->orderBy('created_at', 'desc')   // Depois os mais recentes
            ->limit(8)
            ->get()
            ->map(function ($product) {
                // Calcular preço final com base nas diferentes possibilidades
                $finalPrice = 0;
                $originalPrice = null;
                $discountPercentage = 0;

                if ($product->auto_calculate_price) {
                    if ($product->min_price > 0) {
                        $finalPrice = (float) $product->min_price;
                    }
                } else {
                    if ($product->sale_price > 0) {
                        $finalPrice = (float) $product->sale_price;
                        if ($product->price > 0) {
                            $originalPrice = (float) $product->price;
                            $discountPercentage = round((($product->price - $product->sale_price) / $product->price) * 100);
                        }
                    } elseif ($product->price > 0) {
                        $finalPrice = (float) $product->price;
                    }
                }                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price > 0 ? (float) $product->price : null,
                    'sale_price' => $product->sale_price > 0 ? (float) $product->sale_price : null,
                    'final_price' => $finalPrice > 0 ? $finalPrice : ($product->auto_calculate_price ? 'Sob consulta' : null),
                    'original_price' => $originalPrice,
                    'discount_percentage' => $discountPercentage,
                    'featured_image' => $product->featured_image ? url('images/' . $product->featured_image) : null,
                    'first_image' => $product->featured_image ? url('images/' . $product->featured_image) : null,
                    'is_new' => (bool) $product->is_new,
                    'is_featured' => (bool) $product->is_featured,
                    'is_on_sale' => (bool) $product->is_on_sale,
                    'auto_calculate_price' => (bool) $product->auto_calculate_price,
                    'min_price' => $product->min_price ? (float) $product->min_price : null,
                    'stock_quantity' => $product->stock_quantity ?? 0,
                    'rating_average' => $product->rating_average ?? 0,
                    'created_at' => $product->created_at->toIso8601String(),
                ];
            });

        $banners = [
            'main' => \App\Models\Setting::get('banner_main') ? url('images/' . \App\Models\Setting::get('banner_main')) : null,
            'mobile' => \App\Models\Setting::get('banner_mobile') ? url('images/' . \App\Models\Setting::get('banner_mobile')) : null,
        ];

        // Carregar categorias ativas
        $categories = Category::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        // SEO Meta Tags
        $seoTitle = 'Loja Virtual - Especialistas em Comunicação Visual';
        $seoDescription = 'Laser Link - Especialistas em acrílicos, troféus, medalhas, placas e letreiros. Comunicação visual de qualidade com entrega rápida e preços competitivos.';
        $seoKeywords = 'comunicação visual, acrílico, troféus, medalhas, placas, letreiros, brindes corporativos, laser, corte a laser';
        $ogImage = \App\Models\Setting::get('site_logo') ? url('storage/' . \App\Models\Setting::get('site_logo')) : asset('images/logos/logo.png');

        return view('store.index', compact('featuredProducts', 'banners', 'categories', 'seoTitle', 'seoDescription', 'seoKeywords', 'ogImage'));
    }

    public function products(Request $request): View
    {
        $query = Product::where('is_active', true);
        
        // Filtro por categoria (aceita 'categoria' ou 'category')
        // Agora suporta múltiplas categorias como array
        $categories = $request->filled('categoria') ? $request->categoria : $request->category;
        if ($categories) {
            // Converte para array se for string única
            $categorySlugs = is_array($categories) ? $categories : [$categories];
            
            // Buscar IDs das categorias selecionadas
            $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id')->toArray();
            
            // Também incluir IDs das subcategorias das categorias selecionadas
            $subcategoryIds = Category::whereIn('parent_id', $categoryIds)->pluck('id')->toArray();
            
            // Combinar todos os IDs
            $allCategoryIds = array_merge($categoryIds, $subcategoryIds);
            
            // Filtrar produtos por essas categorias
            if (!empty($allCategoryIds)) {
                $query->whereIn('category_id', $allCategoryIds);
            }
        }
        
        // Filtro por busca (aceita 'busca' ou 'search')
        $search = $request->filled('busca') ? $request->busca : $request->search;
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por preço (aceita 'preco_min'/'preco_max' ou 'min_price'/'max_price')
        $minPrice = $request->filled('preco_min') ? $request->preco_min : $request->min_price;
        $maxPrice = $request->filled('preco_max') ? $request->preco_max : $request->max_price;
        
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Filtro por tipo (aceita 'tipo' ou 'type')
        $type = $request->filled('tipo') ? $request->tipo : $request->type;
        if ($type) {
            switch ($type) {
                case 'novos':
                case 'new':
                    $query->whereRaw('DATEDIFF(NOW(), created_at) <= 30');
                    break;
                case 'promocoes':
                case 'sale':
                    $query->whereNotNull('sale_price')
                          ->where('sale_price', '>', 0)
                          ->whereRaw('sale_price < price');
                    break;
                case 'destaques':
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }
        
        // Ordenação (aceita 'ordenar' ou 'sort')
        $sortBy = $request->filled('ordenar') ? $request->ordenar : $request->get('sort', 'recentes');
        switch ($sortBy) {
            case 'menor_preco':
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'maior_preco':
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'nome_az':
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'nome_za':
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'recentes':
            case 'mais_recentes':
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $products = $query->paginate(12)->withQueryString();
        
        // Carregar categorias principais com suas subcategorias ativas
        $parentCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['activeChildren'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        // Também carregar todas as categorias para compatibilidade
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        // Calcular faixas de preço para os filtros
        $allProducts = Product::where('is_active', true)->get();
        $minPrice = $allProducts->min('price') ?? 0;
        $maxPrice = $allProducts->max('price') ?? 1000;

        return view('store.products', compact(
            'products', 
            'categories',
            'parentCategories',
            'minPrice', 
            'maxPrice'
        ));
    }

    public function product(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'approvedReviews' => function($query) {
                $query->with('user')->latest()->limit(5);
            }])
            ->firstOrFail();

        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(4)
            ->get();

        // Carregar campos extras ativos do produto com suas opções ativas
        $extraFields = $product->extraFields()
            ->where('extra_fields.is_active', true)
            ->with(['options' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('product_extra_fields.sort_order')
            ->get();

        // Estatísticas de reviews
        $reviewsStats = [
            'total' => $product->rating_count ?? 0,
            'average' => $product->rating_average ?? 0,
            '5_star' => $product->approvedReviews()->where('rating', 5)->count(),
            '4_star' => $product->approvedReviews()->where('rating', 4)->count(),
            '3_star' => $product->approvedReviews()->where('rating', 3)->count(),
            '2_star' => $product->approvedReviews()->where('rating', 2)->count(),
            '1_star' => $product->approvedReviews()->where('rating', 1)->count(),
        ];

        // SEO Meta Tags
        $seoTitle = $product->name;
        $seoDescription = $product->short_description 
            ? $product->short_description 
            : Str::limit(strip_tags($product->description), 160);
        $seoKeywords = $product->category->name . ', ' . $product->name . ', comunicação visual, laser, ' . ($product->sku ? $product->sku : 'produto personalizado');
        $ogImage = $product->featured_image ? url('images/' . $product->featured_image) : asset('images/general/callback-image.svg');

        return view('store.product', compact('product', 'relatedProducts', 'extraFields', 'reviewsStats', 'seoTitle', 'seoDescription', 'seoKeywords', 'ogImage'));
    }

    public function category(string $slug)
    {
        try {
            \Log::info('Acessando categoria', ['slug' => $slug]);
            
            $category = Category::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
            
            \Log::info('Categoria encontrada', ['category' => $category->toArray()]);
            
            // Redirecionar para a URL direta ao invés de usar route()
            return redirect('/produtos?categoria=' . urlencode($slug));
            
        } catch (\Exception $e) {
            \Log::error('Erro ao processar categoria', [
                'slug' => $slug,
                'error' => $e->getMessage()
            ]);
            
            // Em caso de erro, redirecionar para a página de produtos
            return redirect('/produtos');
        }
    }

    public function searchProducts(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->with('category')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {
                // Determinar a URL da imagem
                $imageUrl = url('images/general/callback-image.svg');
                if ($product->featured_image) {
                    $imageUrl = url('images/' . $product->featured_image);
                }
                
                // Determinar o preço a ser exibido
                $displayPrice = $product->sale_price ?? $product->price;
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => (float) $displayPrice,
                    'price_formatted' => 'R$ ' . number_format((float) $displayPrice, 2, ',', '.'),
                    'image_url' => $imageUrl,
                    'category' => $product->category ? [
                        'name' => $product->category->name
                    ] : null,
                    'url' => route('store.product', $product->slug),
                ];
            });
        
        return response()->json($products);
    }

    public function cart(): View
    {
        return view('store.cart');
    }

    public function checkout(): View
    {
        return view('store.checkout');
    }

    public function processCheckout(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            // Validar dados
            $request->validate([
                'customer.name' => 'required|string|max:255',
                'customer.email' => 'required|email|max:255',
                'customer.phone' => 'required|string|max:20',
                'customer.cep' => 'required|string|max:10',
                'customer.street' => 'required|string|max:255',
                'customer.number' => 'required|string|max:10',
                'customer.neighborhood' => 'required|string|max:255',
                'customer.city' => 'required|string|max:255',
                'customer.state' => 'required|string|max:2',
                'items' => 'required|array|min:1',
                'total' => 'required|numeric|min:0.01'
            ]);

            // Criar ou encontrar usuário usando o service
            $userService = app(\App\Services\UserRegistrationService::class);
            $user = $userService->findUserByEmail($data['customer']['email']);
            
            $userWasCreated = false;
            if (!$user) {
                // Criar novo usuário e enviar email com credenciais
                $user = $userService->createUserAndSendCredentials([
                    'customer_name' => $data['customer']['name'],
                    'customer_email' => $data['customer']['email'],
                    'customer_phone' => $data['customer']['phone'],
                    'customer_cpf' => $data['customer']['cpf'] ?? null,
                ]);
                $userWasCreated = true;
            }
            
            // Atualizar endereço do usuário
            $userService->updateUserAddress($user, [
                'street' => $data['customer']['street'],
                'number' => $data['customer']['number'],
                'complement' => $data['customer']['complement'] ?? null,
                'neighborhood' => $data['customer']['neighborhood'],
                'city' => $data['customer']['city'],
                'state' => $data['customer']['state'],
                'cep' => $data['customer']['cep'],
            ]);
            
            // Fazer login automático do usuário se não estiver autenticado
            $wasAutoLoggedIn = false;
            if (!auth()->check()) {
                auth()->login($user);
                $user->update(['last_login_at' => now()]);
                $wasAutoLoggedIn = true;
                \Log::info('Usuário autenticado automaticamente após checkout', ['user_id' => $user->id]);
            }

            // Gerar número do pedido
            $orderNumber = 'LL' . date('Ymd') . str_pad((string)(Order::count() + 1), 4, '0', STR_PAD_LEFT);

            // Montar endereço completo
            $shippingAddress = $data['customer']['street'] . ', ' . $data['customer']['number'];
            if (!empty($data['customer']['complement'])) {
                $shippingAddress .= ' - ' . $data['customer']['complement'];
            }
            $shippingAddress .= "\n" . $data['customer']['neighborhood'];
            $shippingAddress .= "\n" . $data['customer']['city'] . ' - ' . $data['customer']['state'];
            $shippingAddress .= "\nCEP: " . $data['customer']['cep'];

            // Criar pedido
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'customer_name' => $data['customer']['name'],
                'customer_email' => $data['customer']['email'],
                'customer_phone' => $data['customer']['phone'],
                'customer_cpf' => $data['customer']['cpf'] ?? null,
                'shipping_address' => $shippingAddress,
                'shipping_cep' => $data['customer']['cep'],
                'shipping_zip' => $data['customer']['cep'],
                'shipping_street' => $data['customer']['street'],
                'shipping_number' => $data['customer']['number'],
                'shipping_complement' => $data['customer']['complement'] ?? null,
                'shipping_neighborhood' => $data['customer']['neighborhood'],
                'shipping_city' => $data['customer']['city'],
                'shipping_state' => $data['customer']['state'],
                'subtotal' => $data['subtotal'],
                'shipping_cost' => $data['shipping'] ?? 0,
                'total' => $data['total'],
                'total_amount' => $data['total'],
                'notes' => $data['customer']['notes'] ?? null,
                'status' => 'pending'
            ]);

            // Criar itens do pedido
            foreach ($data['items'] as $item) {
                // Log completo do item para debug
                \Log::info('Processando item do carrinho', ['item' => $item]);
                
                // Tentar encontrar o product_id de várias formas
                $productId = $item['product_id'] ?? $item['id'] ?? null;
                
                // Se product_id for uma string (do localStorage), tentar extrair o ID
                if (is_string($productId) && str_starts_with($productId, 'custom_')) {
                    // Extrair ID do formato: custom_543_timestamp_hash
                    $parts = explode('_', $productId);
                    $productId = isset($parts[1]) && is_numeric($parts[1]) ? (int)$parts[1] : null;
                }
                
                // Se ainda não temos ID, tentar buscar pelo nome do produto
                if (!$productId && isset($item['name'])) {
                    $product = Product::where('name', $item['name'])->first();
                    $productId = $product?->id;
                } else {
                    $product = $productId ? Product::find($productId) : null;
                }
                
                // Se ainda não temos product_id, usar um fallback
                if (!$productId) {
                    \Log::error('Product ID não encontrado para item', ['item' => $item]);
                    $productId = 'unknown'; // Usar string fallback já que a coluna é VARCHAR
                }
                
                // Preparar dados de customização
                $customizationData = null;
                if (isset($item['customization']) && is_array($item['customization']) && !empty($item['customization'])) {
                    $customizationData = $item['customization'];
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => (string)($product?->id ?? $productId), // Garantir que é string
                    'product_name' => $item['name'] ?? $item['product_name'] ?? 'Produto',
                    'product_description' => $item['description'] ?? null,
                    'product_price' => $item['unit_price'] ?? $item['price'] ?? 0,
                    'unit_price' => $item['unit_price'] ?? $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                    'total_price' => $item['total_price'] ?? (($item['unit_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1)),
                    'product_image' => $item['image'] ?? $item['product_image'] ?? null,
                    'customization' => $customizationData ? json_encode($customizationData) : null,
                    'extra_cost' => $item['extra_cost'] ?? null,
                    'base_price' => $item['base_price'] ?? ($item['unit_price'] ?? $item['price'] ?? 0)
                ]);
            }

            // Disparar evento para enviar WhatsApp na criação do pedido
            event(new OrderStatusChanged($order, '', 'pending'));

            // Gerar mensagem do WhatsApp
            $whatsappMessage = $this->generateWhatsAppMessage($order);
            $order->update(['whatsapp_message' => $whatsappMessage]);

            // Enviar email de confirmação
            $this->sendConfirmationEmail($order);

            // Salvar dados na sessão para a página de obrigado
            session([
                'order_number' => $order->order_number,
                'order_total' => $order->total,
                'whatsapp_url' => $this->generateWhatsAppUrl($order)
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'whatsapp_url' => $this->generateWhatsAppUrl($order),
                'user_created' => $userWasCreated,
                'user_authenticated' => $wasAutoLoggedIn,
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateWhatsAppMessage(Order $order): string
    {
        $message = "*NOVO PEDIDO - {$order->order_number}*\n\n";
        $message .= "*Cliente:* {$order->customer_name}\n";
        $message .= "*Email:* {$order->customer_email}\n";
        $message .= "*Telefone:* {$order->customer_phone}\n\n";

        $message .= "*Itens do Pedido:*\n";
        foreach ($order->items as $index => $item) {
            $itemNumber = $index + 1;
            $message .= "\n*{$itemNumber}.* {$item->product_name}\n";
            $message .= "Quantidade: {$item->quantity}\n";
            
            // Adicionar customizações se existirem
            if ($item->customization) {
                $customization = is_string($item->customization) ? json_decode($item->customization, true) : $item->customization;
                if (is_array($customization) && !empty($customization)) {
                    $message .= "*Configurações:*\n";
                    foreach ($customization as $key => $value) {
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                        $message .= "      • {$key}: {$value}\n";
                    }
                }
            }
            
            // Adicionar preços
            if ($item->extra_cost && $item->extra_cost > 0) {
                $message .= "Preço base: R$ " . number_format((float)$item->base_price, 2, ',', '.') . "\n";
                $message .= "Extras: R$ " . number_format((float)$item->extra_cost, 2, ',', '.') . "\n";
            }
            $message .= "   💵 Total: R$ " . number_format((float)$item->total_price, 2, ',', '.') . "\n";
        }

        $message .= "\n*Resumo:*\n";
        $message .= "Subtotal: R$ " . number_format((float)$order->subtotal, 2, ',', '.') . "\n";
        if ($order->shipping_cost > 0) {
            $message .= "Frete: R$ " . number_format((float)$order->shipping_cost, 2, ',', '.') . "\n";
        }
        $message .= "*Total: R$ " . number_format((float)$order->total, 2, ',', '.') . "*\n\n";

        if ($order->notes) {
            $message .= "*Observações:* {$order->notes}\n\n";
        }

        $message .= "Pedido criado em: " . $order->created_at->format('d/m/Y H:i');

        return $message;
    }

    /**
     * Adiciona produto ao carrinho (localStorage via API)
     */
    public function addToCart(Request $request): JsonResponse
    {
        try {
            $validator = \Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'customization' => 'nullable|array',
                'extra_cost' => 'nullable|numeric|min:0',
                'total_price' => 'required|numeric|min:0.01',
                'base_price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($request->product_id);
            
            // Validar campos extras obrigatórios
            if ($request->has('customization') && $request->customization) {
                $extraFields = $product->extraFields()
                    ->where('extra_fields.is_active', true)
                    ->where('product_extra_fields.is_required', true)
                    ->get();

                foreach ($extraFields as $field) {
                    $fieldSlug = $field->slug;
                    if (!isset($request->customization[$fieldSlug]) || 
                        (is_array($request->customization[$fieldSlug]) && empty($request->customization[$fieldSlug])) ||
                        (is_string($request->customization[$fieldSlug]) && empty($request->customization[$fieldSlug]))) {
                        return response()->json([
                            'success' => false,
                            'message' => "Campo obrigatório não preenchido: {$field->name}"
                        ], 422);
                    }
                }
            }

            // Preparar dados do item do carrinho
            $cartItem = [
                'id' => uniqid('cart_', true),
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_image' => $product->featured_image ? url('images/' . $product->featured_image) : url('images/general/callback-image.svg'),
                'base_price' => $request->base_price,
                'extra_cost' => $request->extra_cost ?? 0,
                'unit_price' => $request->total_price,
                'quantity' => $request->quantity,
                'total_price' => $request->total_price * $request->quantity,
                'customization' => $request->customization ?? [],
                'added_at' => now()->toISOString(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Produto adicionado ao carrinho!',
                'cart_item' => $cartItem
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar produto ao carrinho: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Remove produto do carrinho
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'cart_item_id' => 'required|string'
            ]);

            // Como o carrinho está no localStorage, apenas retornamos sucesso
            // A lógica de remoção será feita no frontend
            return response()->json([
                'success' => true,
                'message' => 'Produto removido do carrinho!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover produto do carrinho'
            ], 500);
        }
    }

    /**
     * Atualiza quantidade do produto no carrinho
     */
    public function updateCartQuantity(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'cart_item_id' => 'required|string',
                'quantity' => 'required|integer|min:1'
            ]);

            // Como o carrinho está no localStorage, apenas retornamos sucesso
            // A lógica de atualização será feita no frontend
            return response()->json([
                'success' => true,
                'message' => 'Quantidade atualizada!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar quantidade'
            ], 500);
        }
    }

    /**
     * Calcula preço baseado nas customizações
     */
    public function calculatePrice(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'customization' => 'nullable|array',
                'base_price' => 'required|numeric|min:0'
            ]);

            $product = Product::findOrFail($request->product_id);
            $basePrice = $request->base_price;
            $extraCost = 0;

            if ($request->has('customization') && $request->customization) {
                $extraFields = $product->extraFields()
                    ->where('extra_fields.is_active', true)
                    ->with(['options' => function($query) {
                        $query->where('is_active', true)->orderBy('sort_order');
                    }])
                    ->get();

                foreach ($extraFields as $field) {
                    $fieldSlug = $field->slug;
                    
                    if (isset($request->customization[$fieldSlug])) {
                        $selectedValue = $request->customization[$fieldSlug];
                        
                        // Verificar se há opções customizadas no produto
                        $fieldSettings = json_decode($field->pivot->field_settings ?? '{}', true);
                        $customOptions = $fieldSettings['custom_options'] ?? null;
                        
                        if ($customOptions && count($customOptions) > 0) {
                            $optionsToCheck = $customOptions;
                        } else {
                            $optionsToCheck = $field->options->map(function($option) {
                                return [
                                    'value' => $option->value,
                                    'price' => $option->price,
                                    'price_type' => $option->price_type
                                ];
                            })->toArray();
                        }

                        if (is_array($selectedValue)) {
                            // Checkbox - múltiplas seleções
                            foreach ($selectedValue as $value) {
                                $option = collect($optionsToCheck)->firstWhere('value', $value);
                                if ($option && $option['price'] > 0) {
                                    if ($option['price_type'] === 'percentage') {
                                        $extraCost += ($basePrice * $option['price']) / 100;
                                    } else {
                                        $extraCost += $option['price'];
                                    }
                                }
                            }
                        } else {
                            // Select/Radio - seleção única
                            $option = collect($optionsToCheck)->firstWhere('value', $selectedValue);
                            if ($option && $option['price'] > 0) {
                                if ($option['price_type'] === 'percentage') {
                                    $extraCost += ($basePrice * $option['price']) / 100;
                                } else {
                                    $extraCost += $option['price'];
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'base_price' => $basePrice,
                'extra_cost' => $extraCost,
                'total_price' => $basePrice + $extraCost
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao calcular preço: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular preço'
            ], 500);
        }
    }

    private function generateWhatsAppUrl(Order $order): string
    {
        try {
            \Log::debug('Iniciando generateWhatsAppUrl', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'order_status' => $order->status
            ]);
            
            // Forçar teste de erro para debug
            \Log::debug('Dados do pedido:', [
                'order' => $order->toArray(),
                'items' => $order->items->toArray()
            ]);

            // Tentar obter uma instância ativa do WhatsApp
            $query = \App\Models\WhatsAppInstance::where('is_active', true)
                ->where('status', 'connected');
            
            \Log::debug('Query de busca de instância:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
            
            $instance = $query->first();
            
            \Log::debug('Resultado da busca de instância:', [
                'instance' => $instance ? $instance->toArray() : null
            ]);
            
            if ($instance) {
                \Log::debug('Instância WhatsApp encontrada', [
                    'order_id' => $order->id,
                    'instance_id' => $instance->id,
                    'instance_name' => $instance->name,
                    'instance_status' => $instance->status
                ]);

                // Não enviar diretamente aqui para evitar duplicidade com o listener de eventos
                // Apenas retornar vazio pois a mensagem será tratada pelo evento OrderStatusChanged
                return '';
            }
            
            // Fallback para o método tradicional se não houver instância conectada
            \Log::debug('Nenhuma instância WhatsApp conectada, usando fallback', [
                'order_id' => $order->id
            ]);
            
            $phone = \App\Models\Setting::get('whatsapp_number', '5511999999999');
            $message = urlencode($order->whatsapp_message);
            $url = "https://wa.me/{$phone}?text={$message}";
            
            \Log::debug('URL de fallback gerada', [
                'order_id' => $order->id,
                'phone' => $phone,
                'url' => $url
            ]);
            
            return $url;
        } catch (\Exception $e) {
            \Log::error('Erro ao processar notificação WhatsApp', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback para o método tradicional em caso de erro
            $phone = \App\Models\Setting::get('whatsapp_number', '5511999999999');
            $message = urlencode($order->whatsapp_message);
            $url = "https://wa.me/{$phone}?text={$message}";
            
            \Log::debug('URL de fallback gerada após erro', [
                'order_id' => $order->id,
                'phone' => $phone,
                'url' => $url
            ]);
            
            return $url;
        }
    }

    private function sendConfirmationEmail(Order $order): void
    {
        try {
            Mail::send('emails.order-confirmation', compact('order'), function ($message) use ($order) {
                $message->to($order->customer_email, $order->customer_name)
                        ->subject("Pedido {$order->order_number} - Laser Link");
            });
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de confirmação: ' . $e->getMessage());
        }
    }

    public function checkoutSuccess(Request $request): View
    {
        // A view thank-you já tem acesso aos dados da sessão
        // que foram definidos no processCheckout
        return view('store.thank-you');
    }

    public function favorites(): View
    {
        return view('store.favorites');
    }

    public function getFavorites(Request $request): JsonResponse
    {
        $ids = $request->input('ids');
        
        if (!$ids) {
            return response()->json(['products' => []]);
        }
        
        $idsArray = array_filter(array_map('intval', explode(',', $ids)));
        
        if (empty($idsArray)) {
            return response()->json(['products' => []]);
        }
        
        $products = Product::whereIn('id', $idsArray)
            ->where('is_active', true)
            ->with(['category'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price_formatted' => number_format((float) $product->price, 2, ',', '.'),
                    'price' => (float) $product->price,
                    'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
                    'image_url' => $product->featured_image 
                        ? url('images/' . $product->featured_image) 
                        : url('images/general/callback-image.svg'),
                    'rating_average' => $product->rating_average ?? 0,
                    'is_new' => (bool) $product->is_new,
                    'is_on_sale' => (bool) $product->is_on_sale,
                    'is_featured' => (bool) $product->is_featured,
                    'discount_percentage' => $product->is_on_sale && $product->sale_price ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0,
                ];
            });
        
        return response()->json(['products' => $products]);
    }

    public function customizableProducts(): View
    {
        $products = Product::where('is_active', true)
            ->where('is_customizable', true)
            ->get();

        return view('store.customizable-products', compact('products'));
    }

    /**
     * Mostra a página de configuração (configurador) do produto
     */
    public function configurator(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'extraFields.options'])
            ->firstOrFail();

        // Montar uma configuração básica que a view espera
        $config = [
            'materials' => [],
            'finishes' => [],
            'text_customization' => ['enabled' => false],
            'adhesive_printing' => ['enabled' => false, 'types' => []],
            'extras' => [],
            'base_support_options' => []
        ];

        // Tentar preencher com dados do produto se existirem
        if ($product->attributes && is_array($product->attributes)) {
            // exemplo: attributes may contain customization info
            $config = array_merge($config, $product->attributes['customization'] ?? []);
        }

        return view('store.product-customization', compact('product', 'config'));
    }

    /**
     * Salva a personalização (API) — aqui apenas valida e retorna sucesso para o frontend
     */
    public function saveCustomization(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            // Validação mínima
            $validator = \Validator::make($data, [
                'customizable_product_id' => 'required|integer',
                'quantity' => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Em um fluxo real, salvaríamos a personalização associada ao usuário ou criamos um item temporário.
            // Para não alterar a estrutura do projeto, apenas retornamos sucesso e o payload recebido.

            return response()->json([
                'success' => true,
                'message' => 'Personalização salva (simulada)',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao salvar personalização: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno ao salvar personalização'], 500);
        }
    }

    // Blog methods
    public function blog(): View
    {
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $recentPosts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('blog.index', compact('posts', 'recentPosts'));
    }

    public function blogPost(string $slug): View
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('id', $post->categories->pluck('id'));
            })
            ->limit(3)
            ->get();

        $recentPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'recentPosts'));
    }

    public function blogCategory(string $slug): View
    {
        $category = \App\Models\BlogCategory::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $posts = $category->posts()
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $recentPosts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('blog.category', compact('category', 'posts', 'recentPosts'));
    }
}