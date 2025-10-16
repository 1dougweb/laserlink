<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CustomerNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerNotificationController extends Controller
{
    /**
     * Marca uma notificação específica como lida
     */
    public function markAsRead(int $id): JsonResponse
    {
        $notification = CustomerNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marca todas as notificações do usuário como lidas
     */
    public function markAllAsRead(): JsonResponse
    {
        CustomerNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Retorna o componente de notificações
     */
    public function getNotificationsComponent()
    {
        $notifications = CustomerNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = CustomerNotification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return view('components.customer-notifications', compact('notifications', 'unreadCount'));
    }
}