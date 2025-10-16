<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Retorna as notificações do usuário.
     */
    public function get(): JsonResponse
    {
        $notifications = CustomerNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = CustomerNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Marca uma notificação como lida.
     */
    public function markAsRead(int $id): JsonResponse
    {
        $notification = CustomerNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marca todas as notificações do usuário como lidas.
     */
    public function markAllAsRead(): JsonResponse
    {
        CustomerNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}