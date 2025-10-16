<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ProductCustomization;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\UserRegistrationService;
use App\Services\WhatsAppNotificationService;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    protected UserRegistrationService $userRegistrationService;
    protected WhatsAppNotificationService $whatsappNotificationService;

    public function __construct(
        UserRegistrationService $userRegistrationService,
        WhatsAppNotificationService $whatsappNotificationService
    ) {
        $this->userRegistrationService = $userRegistrationService;
        $this->whatsappNotificationService = $whatsappNotificationService;
    }

    /**
     * Obtém o ID do usuário autenticado ou null
     */
    private function getUserId(): ?int
    {
        return auth()->check() ? auth()->id() : null;
    }
    /**
     * Exibe a página de checkout para produto personalizado
     */
    public function show(int $customizationId): View
    {
        $customization = ProductCustomization::with(['customizableProduct', 'material'])
            ->findOrFail($customizationId);

        // Verifica se o usuário tem acesso a esta personalização
        $sessionId = Session::getId();
        $userId = $this->getUserId();
        
        if ($customization->session_id !== $sessionId && $customization->user_id !== $userId) {
            abort(403, 'Acesso negado');
        }

        return view('store.checkout', compact('customization'));
    }

    /**
     * Exibe a página de checkout para carrinho de compras
     */
    public function cartCheckout()
    {
        // O carrinho agora está no localStorage do navegador, não na sessão PHP
        // A view vai carregar os dados do localStorage via JavaScript
        return view('store.cart-checkout');
    }

    /**
     * Processa o pedido do carrinho via WhatsApp
     */
    public function processCartOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_neighborhood' => 'required|string|max:100',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:2',
            'shipping_zip' => 'required|string|max:10',
            'shipping_complement' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cartDataJson = $request->input('cart_data');
            \Log::info('Cart data received:', ['cart_data' => $cartDataJson]);
            
            $cartData = json_decode($cartDataJson, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('JSON decode error:', ['error' => json_last_error_msg(), 'data' => $cartDataJson]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar dados do carrinho'
                ], 400);
            }
            
            if (empty($cartData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrinho vazio'
                ], 400);
            }

            // Verifica se WhatsApp está habilitado
            if (!$this->whatsappNotificationService->hasActiveInstance()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sistema de mensagens está temporariamente indisponível'
                ], 400);
            }

            // Verifica se usuário já existe ou cria um novo
            $user = null;
            $temporaryPassword = null;
            try {
                $user = $this->userRegistrationService->findUserByEmail($request->customer_email);
                if (!$user) {
                    $user = $this->userRegistrationService->createUserAndSendCredentials([
                        'customer_name' => $request->customer_name,
                        'customer_email' => $request->customer_email,
                        'customer_phone' => $request->customer_phone,
                    ]);
                    // Se criou um novo usuário, faz login automático
                    if ($user->wasRecentlyCreated) {
                        auth()->login($user);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao criar/buscar usuário: ' . $e->getMessage());
                // Continua o processo mesmo se falhar ao criar usuário
                $user = null;
            }

            // Prepara dados do pedido para WhatsApp
            $orderData = [
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_neighborhood' => $request->shipping_neighborhood,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'shipping_complement' => $request->shipping_complement,
                'notes' => $request->notes,
                'total_amount' => $request->total_amount,
                'items' => $cartData
            ];

            \Log::info('Order data prepared:', $orderData);

            // Cria o pedido no banco de dados
            $order = $this->createOrder($orderData, $user);
            
            // Dispara evento para enviar mensagem via WhatsApp
            event(new OrderStatusChanged($order, '', 'pending'));
            
            // Salva dados do pedido na sessão para possível recuperação
            session()->put('last_order_data', $orderData);

            return response()->json([
                'success' => true,
                'user_created' => $user ? $user->wasRecentlyCreated : false,
                'user_id' => $user ? $user->id : null,
                'order_id' => $order->id,
                'redirect_url' => route('store.thank-you'),
                'message' => 'Pedido recebido com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processa o pedido personalizado via WhatsApp
     */
    public function processOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customization_id' => 'required|exists:product_customizations,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:2',
            'shipping_zip' => 'required|string|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customization = ProductCustomization::with(['customizableProduct', 'material'])
                ->findOrFail($request->customization_id);

            // Verifica se o usuário tem acesso a esta personalização
            $sessionId = Session::getId();
            $userId = $this->getUserId();
            
            if ($customization->session_id !== $sessionId && $customization->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            // Verifica se WhatsApp está habilitado
            if (!$this->whatsappNotificationService->hasActiveInstance()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sistema de mensagens está temporariamente indisponível'
                ], 400);
            }

            // Prepara dados do pedido para WhatsApp
            $orderData = [
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'notes' => $request->notes,
                'total_amount' => $customization->total_price,
                'items' => [[
                    'product_name' => $customization->customizableProduct->name,
                    'measurement_description' => $customization->measurement_description,
                    'configuration' => $customization->configuration ?? [],
                    'quantity' => $customization->quantity,
                    'unit_price' => $customization->unit_price,
                    'total_price' => $customization->total_price,
                ]]
            ];

            // Cria o pedido e dispara o evento para enviar mensagem
            $order = $this->createOrder($orderData, $user);
            event(new OrderStatusChanged($order, '', 'pending'));

            // Salva dados do pedido na sessão para possível recuperação
            session()->put('last_order_data', $orderData);

            return response()->json([
                'success' => true,
                'redirect_url' => route('store.thank-you'),
                'message' => 'Pedido recebido com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe página de obrigado
     */
    public function thankYou(): View|RedirectResponse
    {
        $orderData = session('last_order_data', []);
        
        if (empty($orderData)) {
            return redirect()->route('store.index');
        }

        return view('store.thank-you', compact('orderData'));
    }

    /**
     * Limpa dados da sessão
     */
    public function clearSession(): JsonResponse
    {
        session()->forget('last_order_data');
        
        return response()->json(['success' => true]);
    }

    /**
     * Obtém dados de uma personalização para checkout
     */
    public function getCustomizationData(int $id): JsonResponse
    {
        try {
            $customization = ProductCustomization::with(['customizableProduct', 'material'])
                ->findOrFail($id);

            // Verifica se o usuário tem acesso a esta personalização
            $sessionId = Session::getId();
            $userId = $this->getUserId();
            
            if ($customization->session_id !== $sessionId && $customization->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'customization' => $customization,
                    'summary' => $customization->getSummary(),
                    'price_breakdown' => $customization->price_breakdown
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista pedidos do usuário
     */
    public function userOrders(): View
    {
        $userId = auth()->id();
        
        if (!$userId) {
            abort(401, 'Usuário não autenticado');
        }

        $orders = Order::where('user_id', $userId)
            ->with(['orderItems'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('store.user-orders', compact('orders'));
    }

    /**
     * Exibe detalhes de um pedido
     */
    public function orderDetails(int $id): View
    {
        $userId = auth()->id();
        
        if (!$userId) {
            abort(401, 'Usuário não autenticado');
        }

        $order = Order::where('id', $id)
            ->where('user_id', $userId)
            ->with(['orderItems'])
            ->firstOrFail();

        return view('store.order-details', compact('order'));
    }

    /**
     * Cria um pedido no banco de dados
     */
    private function createOrder(array $orderData, ?\App\Models\User $user): Order
    {
        return DB::transaction(function () use ($orderData, $user) {
            // Gera número do pedido
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
            
            // Cria o pedido
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user ? $user->id : null,
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'customer_phone' => $orderData['customer_phone'],
                'shipping_address' => $orderData['shipping_address'],
                'shipping_neighborhood' => $orderData['shipping_neighborhood'],
                'shipping_city' => $orderData['shipping_city'],
                'shipping_state' => $orderData['shipping_state'],
                'shipping_zip' => $orderData['shipping_zip'],
                'shipping_complement' => $orderData['shipping_complement'] ?? null,
                'notes' => $orderData['notes'],
                'total' => (float)$orderData['total_amount'],
                'status' => 'pending',
            ]);

            // Cria os itens do pedido
            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'measurement_id' => $item['measurement_id'] ?? null,
                    'measurement_description' => $item['measurement_description'] ?? null,
                    'configuration' => $item['configuration'] ?? null,
                    'price_breakdown' => $item['price_breakdown'] ?? null,
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => (float)$item['unit_price'],
                    'total_price' => (float)$item['total_price'],
                ]);
            }

            return $order;
        });
    }
}
