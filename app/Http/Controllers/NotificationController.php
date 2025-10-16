<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\NotificationRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Obter notificações de pedidos recentes
     */
    public function getOrderNotifications(Request $request): JsonResponse
    {
        $userId = auth()->id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'notifications' => [],
                'unread_count' => 0,
            ]);
        }
        
        // Pedidos dos últimos 7 dias
        $recentOrders = Order::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // IDs dos pedidos já lidos pelo usuário
        $readOrderIds = NotificationRead::where('user_id', $userId)
            ->pluck('order_id')
            ->toArray();
        
        // Contar apenas pedidos não lidos
        $unreadCount = $recentOrders->whereNotIn('id', $readOrderIds)->count();
        
        $notifications = $recentOrders->map(function ($order) use ($readOrderIds) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'status_color' => $order->status_color,
                'created_at' => $order->created_at->diffForHumans(),
                'is_new' => !in_array($order->id, $readOrderIds),
                'is_read' => in_array($order->id, $readOrderIds),
                'url' => route('admin.orders.show', $order),
            ];
        });
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }
    
    /**
     * Marcar notificação como lida
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $orderId = $request->input('order_id');
        
        if (!$userId || !$orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos'
            ], 400);
        }
        
        // Criar ou atualizar registro de leitura
        NotificationRead::updateOrCreate(
            [
                'user_id' => $userId,
                'order_id' => $orderId,
            ],
            [
                'read_at' => now(),
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como lida'
        ]);
    }
    
    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $userId = auth()->id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }
        
        // Buscar todos os pedidos recentes
        $recentOrders = Order::where('created_at', '>=', now()->subDays(7))
            ->pluck('id')
            ->toArray();
        
        // Marcar todos como lidos
        foreach ($recentOrders as $orderId) {
            NotificationRead::updateOrCreate(
                [
                    'user_id' => $userId,
                    'order_id' => $orderId,
                ],
                [
                    'read_at' => now(),
                ]
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Todas as notificações foram marcadas como lidas'
        ]);
    }
}

